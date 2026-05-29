<x-app-layout>
    <div class="py-6 sm:py-12 h-[calc(100vh-65px)] max-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full flex flex-col md:flex-row gap-6">
            
            <!-- Sidebar: Conversation List -->
            <div class="{{ $activeConversation ? 'hidden md:flex' : 'flex' }} w-full md:w-1/3 flex-col bg-white overflow-hidden shadow-xl sm:rounded-xl h-full border border-gray-100">
                <div class="p-5 border-b border-gray-100 bg-white">
                    <h2 class="text-xl font-bold text-gray-800">Messages</h2>
                </div>
                
                <div class="flex-1 overflow-y-auto bg-gray-50/50">
                    @if($conversations->isEmpty())
                        <div class="p-8 text-center flex flex-col items-center text-gray-400">
                            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p class="text-sm">No messages yet.</p>
                        </div>
                    @else
                        <ul role="list" class="divide-y divide-gray-100">
                            @foreach($conversations as $conversation)
                                @php
                                    $isLearner = auth()->id() === $conversation->learner_id;
                                    $otherUser = $isLearner ? $conversation->tutor : $conversation->learner;
                                    $isActive = $activeConversation && $activeConversation->id === $conversation->id;
                                @endphp
                                <li>
                                    <a href="{{ route('inbox.show', $conversation) }}" class="flex items-center p-4 hover:bg-indigo-50 transition duration-150 ease-in-out {{ $isActive ? 'bg-indigo-50 border-l-4 border-indigo-600' : 'border-l-4 border-transparent' }}">
                                        <div class="flex-shrink-0 relative">
                                            <img class="h-12 w-12 rounded-full object-cover shadow-sm" src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}">
                                            @if($conversation->unread_count > 0 && !$isActive)
                                                <span class="absolute top-0 right-0 block h-3.5 w-3.5 rounded-full ring-2 ring-white bg-red-500"></span>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1 min-w-0">
                                            <div class="flex justify-between items-baseline mb-1">
                                                <p class="text-sm font-bold text-gray-900 truncate">
                                                    {{ $otherUser->name }}
                                                </p>
                                                @if($conversation->latestMessage)
                                                    <p class="text-xs text-gray-400 whitespace-nowrap ml-2">{{ $conversation->latestMessage->created_at->shortAbsoluteDiffForHumans() }}</p>
                                                @endif
                                            </div>
                                            <p class="text-sm {{ ($conversation->unread_count > 0 && !$isActive) ? 'font-semibold text-gray-900' : 'text-gray-500' }} truncate">
                                                @if($conversation->latestMessage)
                                                    {{ $conversation->latestMessage->sender_id === auth()->id() ? 'You: ' : '' }}{{ $conversation->latestMessage->body }}
                                                @else
                                                    No messages yet.
                                                @endif
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Main Area: Conversation Thread -->
            <div class="{{ !$activeConversation ? 'hidden md:flex' : 'flex' }} w-full md:w-2/3 flex-col bg-white overflow-hidden shadow-xl sm:rounded-xl h-full border border-gray-100 relative">
                @if($activeConversation)
                    @php
                        $isLearner = auth()->id() === $activeConversation->learner_id;
                        $otherUser = $isLearner ? $activeConversation->tutor : $activeConversation->learner;
                    @endphp
                    <!-- Header -->
                    <div class="flex items-center space-x-3 p-4 border-b border-gray-100 bg-white">
                        <a href="{{ route('inbox.index') }}" class="md:hidden p-2 -ml-2 text-gray-500 hover:text-gray-700 transition rounded-full hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <img src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h2 class="font-bold text-gray-800">{{ $otherUser->name }}</h2>
                            <p class="text-xs text-gray-500 capitalize">{{ $isLearner ? 'Tutor' : 'Learner' }}</p>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 p-6 overflow-y-auto space-y-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-gray-50 flex flex-col-reverse" id="messages-container">
                        @forelse($activeConversation->messages->sortByDesc('created_at') as $message)
                            @php
                                $isSender = $message->sender_id === auth()->id();
                            @endphp
                            <div class="flex {{ $isSender ? 'justify-end' : 'justify-start' }}">
                                <div class="flex items-end max-w-[85%] space-x-2 {{ $isSender ? 'flex-row-reverse space-x-reverse' : '' }}">
                                    @if(!$isSender)
                                        <img src="{{ $message->sender->profile_photo_url }}" alt="{{ $message->sender->name }}" class="w-6 h-6 rounded-full mb-1">
                                    @endif
                                    <div class="px-4 py-2.5 rounded-2xl shadow-sm {{ $isSender ? 'bg-indigo-600 text-white rounded-br-sm' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-sm' }}">
                                        <p class="text-[15px] whitespace-pre-wrap leading-relaxed">{!! nl2br(e($message->body)) !!}</p>
                                        <div class="flex items-center justify-end mt-1 space-x-1">
                                            <p class="text-[10px] {{ $isSender ? 'text-indigo-200' : 'text-gray-400' }}">
                                                {{ $message->created_at->format('g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-400 my-auto pb-10">
                                <p class="text-sm">Start the conversation with {{ $otherUser->name }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Reply Form -->
                    <div class="p-4 bg-white border-t border-gray-100">
                        <form action="{{ route('inbox.messages.store', $activeConversation) }}" method="POST" class="flex items-end space-x-2">
                            @csrf
                            <div class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition shadow-inner">
                                <textarea name="body" rows="1" class="w-full bg-transparent border-none focus:ring-0 resize-none px-4 py-3 text-[15px]" placeholder="Message..." required oninput="this.style.height = '';this.style.height = Math.min(this.scrollHeight, 120) + 'px'"></textarea>
                            </div>
                            <button type="submit" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition shadow-md flex-shrink-0 mb-0.5">
                                <svg class="w-5 h-5 ml-0.5 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                        @error('body')
                            <p class="mt-2 text-xs text-red-500 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="flex-1 flex flex-col items-center justify-center bg-gray-50/50">
                        <div class="w-24 h-24 mb-6 rounded-full bg-indigo-50 flex items-center justify-center">
                            <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Your Messages</h3>
                        <p class="text-gray-500 max-w-sm text-center text-sm">Select a conversation from the list to start messaging or contact a tutor from their profile.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
