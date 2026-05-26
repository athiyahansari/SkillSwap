<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manage Teaching Subjects') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100 p-8 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Select the Subjects You Teach</h3>
                    <p class="text-sm text-slate-500 mt-1">Select all subjects you are qualified to tutor. These will be visible on your public listing and help learners find you.</p>
                </div>

                <form method="POST" action="{{ route('tutor.subjects.update') }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Selection Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($allSubjects as $subject)
                            <label class="relative flex p-4 rounded-xl border border-slate-200 cursor-pointer select-none hover:bg-slate-50 transition focus-within:ring-2 focus-within:ring-indigo-500">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" class="sr-only peer" 
                                       {{ in_array($subject->id, $assignedSubjectIds) ? 'checked' : '' }}>
                                
                                <div class="flex flex-col w-full">
                                    <span class="font-bold text-slate-800 text-sm peer-checked:text-indigo-900">{{ $subject->name }}</span>
                                    <span class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $subject->description }}</span>
                                </div>
                                
                                <!-- Styled border and highlight on select -->
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent pointer-events-none peer-checked:border-indigo-600 peer-checked:bg-indigo-50/5" aria-hidden="true"></div>
                                
                                <!-- Checkmark Indicator -->
                                <div class="absolute top-3 right-3 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end border-t border-slate-100 pt-6 space-x-3">
                        <a href="{{ route('tutor.profile.show') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                            Cancel
                        </a>
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 transition">
                            {{ __('Save Subjects') }}
                        </x-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
