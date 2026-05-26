<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Write a Review') }}
            </h2>
            <a href="{{ route('learner.bookings.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
                Back to Bookings
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-6">
                <div class="flex items-center space-x-4 border-b border-slate-100 pb-6">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($booking->tutorProfile->user->name ?? 'T', 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-lg">Review your lesson with {{ $booking->tutorProfile->user->name ?? 'Tutor' }}</h3>
                        <p class="text-sm text-slate-500">Subject: {{ $booking->subject->name }} | Date: {{ \Carbon\Carbon::parse($booking->session_date)->format('M d, Y') }}</p>
                    </div>
                </div>

                <form action="{{ route('learner.reviews.store', $booking) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Rating Select -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                        <div class="grid grid-cols-5 gap-3 max-w-md">
                            @foreach([1, 2, 3, 4, 5] as $val)
                                <label class="relative flex flex-col items-center justify-center p-4 border rounded-2xl cursor-pointer hover:bg-slate-50 transition border-slate-200 group">
                                    <input type="radio" name="rating" value="{{ $val }}" required class="sr-only peer" {{ old('rating') == $val ? 'checked' : '' }}>
                                    <span class="text-2xl font-extrabold text-slate-700 peer-checked:text-indigo-600">{{ $val }}</span>
                                    <div class="flex items-center text-amber-400 mt-1">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </div>
                                    <!-- absolute highlight border -->
                                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-indigo-600 pointer-events-none"></div>
                                </label>
                            @endforeach
                        </div>
                        @error('rating')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment textarea -->
                    <div>
                        <label for="comment" class="block text-sm font-bold text-slate-700 mb-2">Comment (Optional)</label>
                        <textarea name="comment" id="comment" rows="5" placeholder="Share your experience learning with this tutor. What went well? How was their teaching style?" class="w-full rounded-2xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition">
                        Submit Review
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
