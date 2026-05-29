<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\TutorProfile;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    private function getConversationsList($user)
    {
        return Conversation::where('learner_id', $user->id)
            ->orWhere('tutor_id', $user->id)
            ->with(['learner.tutorProfile', 'tutor.tutorProfile', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($query) use ($user) {
                $query->where('is_read', false)->where('sender_id', '!=', $user->id);
            }])
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->latest()
                    ->take(1)
            )
            ->get();
    }

    public function index()
    {
        $user = Auth::user();
        $conversations = $this->getConversationsList($user);
        $activeConversation = null;

        return view('conversations.index', compact('conversations', 'activeConversation'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->learner_id !== $user->id && $conversation->tutor_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Mark unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversation->load(['messages.sender', 'learner.tutorProfile', 'tutor.tutorProfile']);
        
        $conversations = $this->getConversationsList($user);
        $activeConversation = $conversation;

        return view('conversations.index', compact('conversations', 'activeConversation'));
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->learner_id !== $user->id && $conversation->tutor_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        // Touch conversation to update 'updated_at' if we want, but we are ordering by latest message directly.
        $conversation->touch();

        return redirect()->route('inbox.show', $conversation);
    }

    public function storeFromProfile(Request $request, TutorProfile $tutorProfile)
    {
        $user = Auth::user();
        $tutorUser = $tutorProfile->user;

        if ($user->id === $tutorUser->id) {
            return back()->with('error', 'You cannot message yourself.');
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::firstOrCreate(
            ['learner_id' => $user->id, 'tutor_id' => $tutorUser->id]
        );

        $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        $conversation->touch();

        return redirect()->route('inbox.show', $conversation)->with('success', 'Message sent!');
    }
}
