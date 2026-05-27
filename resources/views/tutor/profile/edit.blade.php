<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Guide Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100 p-8">
                <div class="mb-8 border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Update Profile Details</h3>
                        <p class="text-sm text-slate-500 mt-1">Keep your background, expertise, and session rates up to date for members.</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                        Status: {{ ucfirst($profile->verification_status) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('tutor.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Bio -->
                    <div>
                        <x-label for="bio" value="{{ __('Biography') }}" />
                        <textarea id="bio" name="bio" rows="6" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full text-sm" placeholder="Introduce yourself! What are your hobbies, what skills do you offer, and how can you help others learn?..." required>{{ old('bio', $profile->bio) }}</textarea>
                        <x-input-error for="bio" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hourly Rate -->
                        <div>
                            <x-label for="hourly_rate" value="{{ __('Session Rate ($/hr)') }}" />
                            <x-input id="hourly_rate" class="block mt-1 w-full rounded-xl text-sm" type="number" step="0.01" min="0" max="999.99" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}" required />
                            <x-input-error for="hourly_rate" class="mt-2" />
                        </div>

                        <!-- Profile Photo Upload -->
                        <div>
                            <x-label for="profile_photo" value="{{ __('Profile Photo') }}" />
                            
                            <!-- Existing Photo Preview -->
                            <div class="flex items-center space-x-4 mt-2">
                                @if ($profile->profile_photo)
                                    <img src="{{ $profile->profile_photo_url }}" alt="Current Photo" class="w-16 h-16 rounded-full object-cover border border-slate-200 shadow-sm">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <x-input id="profile_photo" class="block w-full rounded-xl border border-gray-300 bg-white p-2 text-sm" type="file" name="profile_photo" accept="image/*" />
                                    <span class="text-xs text-slate-400">Leave blank to keep current photo. Max 2MB (jpg, png, webp).</span>
                                </div>
                            </div>
                            <x-input-error for="profile_photo" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Education -->
                        <div>
                            <x-label for="education" value="{{ __('Background & Expertise') }}" />
                            <x-input id="education" class="block mt-1 w-full rounded-xl text-sm" type="text" name="education" value="{{ old('education', $profile->education) }}" required />
                            <x-input-error for="education" class="mt-2" />
                        </div>

                        <!-- Experience -->
                        <div>
                            <x-label for="experience" value="{{ __('Helping Experience') }}" />
                            <x-input id="experience" class="block mt-1 w-full rounded-xl text-sm" type="text" name="experience" value="{{ old('experience', $profile->experience) }}" required />
                            <x-input-error for="experience" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-slate-100 pt-6 space-x-3">
                        <a href="{{ route('tutor.profile.show') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                            Cancel
                        </a>
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 transition">
                            {{ __('Update Profile') }}
                        </x-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
