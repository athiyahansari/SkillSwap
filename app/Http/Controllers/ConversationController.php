<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\TutorProfile;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageReceived;

class ConversationController extends Controller
{
    private function getConversationsList($user)
    {
        $conversations = Conversation::where('learner_id', $user->id)
            ->orWhere('tutor_id', $user->id)
            ->with(['learner.tutorProfile', 'tutor.tutorProfile', 'latestMessage'])
            ->get();

        return $conversations->map(function ($conversation) use ($user) {
            $conversation->unread_count = \App\Models\Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();
            return $conversation;
        })->sortByDesc(function ($conversation) {
            return $conversation->latestMessage ? $conversation->latestMessage->created_at : $conversation->created_at;
        })->values();
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

        $recipientId = ($conversation->learner_id === $user->id) ? $conversation->tutor_id : $conversation->learner_id;
        $recipient = \App\Models\User::find($recipientId);
        if ($recipient) {
            $recipient->notify(new NewMessageReceived($conversation, $user->name));
        }

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

        $recipientId = ($conversation->learner_id === $user->id) ? $conversation->tutor_id : $conversation->learner_id;
        $recipient = \App\Models\User::find($recipientId);
        if ($recipient) {
            $recipient->notify(new NewMessageReceived($conversation, $user->name));
        }

        $conversation->touch();

        return redirect()->route('inbox.show', $conversation)->with('success', 'Message sent!');
    }

    public function initiateFromTutor(\App\Models\User $learner)
    {
        $user = Auth::user();
        $tutorProfile = $user->tutorProfile;

        if (!$tutorProfile) {
            return redirect()->back()->with('error', 'Please create your tutor profile first.');
        }

        // Check if a booking has happened between this tutor and the learner
        $hasBooking = \App\Models\Booking::where('tutor_profile_id', $tutorProfile->id)
            ->where('learner_id', $learner->id)
            ->exists();

        // Check if a conversation already exists
        $conversationExists = Conversation::where('learner_id', $learner->id)
            ->where('tutor_id', $user->id)
            ->exists();

        if (!$hasBooking && !$conversationExists) {
            return redirect()->back()->with('error', 'You can only message a learner if you have a booking with them or if they initiated a conversation first.');
        }

        $conversation = Conversation::firstOrCreate([
            'learner_id' => $learner->id,
            'tutor_id' => $user->id,
        ]);

        return redirect()->route('inbox.show', $conversation);
    }
}
