<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Create Tutor Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100 p-8">
                <div class="mb-8 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-800">Profile Details</h3>
                    <p class="text-sm text-slate-500 mt-1">Complete your profile to start receiving booking requests from learners.</p>
                </div>

                <form method="POST" action="{{ route('tutor.profile.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Bio -->
                    <div>
                        <x-label for="bio" value="{{ __('Biography') }}" />
                        <textarea id="bio" name="bio" rows="6" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full text-sm" placeholder="Write a detailed introduction about yourself, teaching styles, and expertise (min 20 characters)..." required>{{ old('bio') }}</textarea>
                        <x-input-error for="bio" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hourly Rate -->
                        <div>
                            <x-label for="hourly_rate" value="{{ __('Hourly Rate ($)') }}" />
                            <x-input id="hourly_rate" class="block mt-1 w-full rounded-xl text-sm" type="number" step="0.01" min="0" max="999.99" name="hourly_rate" value="{{ old('hourly_rate') }}" placeholder="25.00" required />
                            <x-input-error for="hourly_rate" class="mt-2" />
                        </div>

                        <!-- Profile Photo -->
                        <div>
                            <x-label for="profile_photo" value="{{ __('Profile Photo') }}" />
                            <x-input id="profile_photo" class="block mt-1 w-full rounded-xl border border-gray-300 bg-white p-2 text-sm" type="file" name="profile_photo" accept="image/*" required />
                            <x-input-error for="profile_photo" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Education -->
                        <div>
                            <x-label for="education" value="{{ __('Education / Credentials') }}" />
                            <x-input id="education" class="block mt-1 w-full rounded-xl text-sm" type="text" name="education" value="{{ old('education') }}" placeholder="e.g. B.Sc. in Computer Science from MIT" required />
                            <x-input-error for="education" class="mt-2" />
                        </div>

                        <!-- Experience -->
                        <div>
                            <x-label for="experience" value="{{ __('Teaching Experience') }}" />
                            <x-input id="experience" class="block mt-1 w-full rounded-xl text-sm" type="text" name="experience" value="{{ old('experience') }}" placeholder="e.g. 5+ years teaching high-school chemistry" required />
                            <x-input-error for="experience" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-slate-100 pt-6 space-x-3">
                        <a href="{{ route('tutor.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                            Cancel
                        </a>
                        <x-button class="bg-indigo-600 hover:bg-indigo-700 transition">
                            {{ __('Save Profile') }}
                        </x-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
