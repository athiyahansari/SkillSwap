<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">
    <!-- Top Gradient Accent line -->
    <div class="h-[3px] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 w-full absolute top-0 left-0 right-0"></div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-[3px]">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-200 group-hover:scale-105 transition duration-300">
                            <!-- Graduate Hat Icon -->
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7l10 5 10-5-10-5z" fill="currentColor"/>
                                <path d="M6 10v6c0 2 2.7 3.5 6 3.5s6-1.5 6-3v-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21.5 8.5v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="20.5" cy="14.5" r="1" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="font-outfit font-extrabold text-xl text-slate-800 tracking-tight group-hover:text-indigo-600 transition">SkillSwap</span>
                    </a>
                </div>

                <!-- Navigation Links (Role Differentiated) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @guest
                        <x-nav-link href="{{ route('tutors.index') }}" :active="request()->routeIs('tutors.index')">
                            {{ __('Find Tutors') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                            {{ __('Become a Tutor') }}
                        </x-nav-link>
                    @endguest

                    @auth
                        @if (auth()->user()->role === 'learner')
                            <x-nav-link href="{{ route('learner.dashboard') }}" :active="request()->routeIs('learner.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('tutors.index') }}" :active="request()->routeIs('tutors.index')">
                                {{ __('Find Tutors') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('learner.bookings.index') }}" :active="request()->routeIs('learner.bookings.index')">
                                {{ __('My Bookings') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('inbox.index') }}" :active="request()->routeIs('inbox.*')">
                                {{ __('Messages') }}
                                @if(auth()->user()->unreadMessagesCount() > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ auth()->user()->unreadMessagesCount() }}</span>
                                @endif
                            </x-nav-link>
                        @elseif (auth()->user()->role === 'tutor')
                            <x-nav-link href="{{ route('tutor.dashboard') }}" :active="request()->routeIs('tutor.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('tutor.bookings.index') }}" :active="request()->routeIs('tutor.bookings.index')">
                                {{ __('Booking Requests') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('tutor.profile.show') }}" :active="request()->routeIs('tutor.profile.show')">
                                {{ __('My Tutor Profile') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('tutor.subjects.edit') }}" :active="request()->routeIs('tutor.subjects.edit')">
                                {{ __('Subjects') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('tutor.earnings.index') }}" :active="request()->routeIs('tutor.earnings.index')">
                                {{ __('My Earnings') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('inbox.index') }}" :active="request()->routeIs('inbox.*')">
                                {{ __('Messages') }}
                                @if(auth()->user()->unreadMessagesCount() > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ auth()->user()->unreadMessagesCount() }}</span>
                                @endif
                            </x-nav-link>
                        @elseif (auth()->user()->role === 'admin')
                            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('admin.finances.index') }}" :active="request()->routeIs('admin.finances.index')">
                                {{ __('Platform Finances') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right section: Auth settings or Guest actions -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @guest
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">Log In</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition duration-150">
                            Sign Up
                        </a>
                    </div>
                @endguest

                @auth
                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-slate-200 hover:border-indigo-500 rounded-full focus:outline-none focus:border-indigo-500 transition shadow-sm">
                                        <img class="size-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3.5 py-2 border border-slate-100 text-sm leading-4 font-semibold rounded-xl text-slate-700 bg-white hover:text-indigo-600 hover:bg-slate-50 focus:outline-none focus:bg-slate-50 active:bg-slate-50 transition shadow-sm">
                                            {{ Auth::user()->name }}
                                            <svg class="ms-2 -me-0.5 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Info (Polished Header) -->
                                <div class="px-4 py-3 bg-slate-50/70 border-b border-slate-100 rounded-t-xl">
                                    <div class="font-bold text-sm text-slate-800 leading-tight">{{ Auth::user()->name }}</div>
                                    <div class="font-medium text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</div>
                                    <div class="mt-2">
                                        @if (Auth::user()->role === 'admin')
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold text-purple-700 bg-purple-50 rounded-full border border-purple-100 uppercase tracking-wider">Admin</span>
                                        @elseif (Auth::user()->role === 'tutor')
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-100 uppercase tracking-wider">Tutor</span>
                                        @elseif (Auth::user()->role === 'learner')
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold text-blue-700 bg-blue-50 rounded-full border border-blue-100 uppercase tracking-wider">Learner</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="py-1">
                                    <!-- Settings Links -->
                                    <x-dropdown-link href="{{ route('profile.show') }}">
                                        {{ __('Account Settings') }}
                                    </x-dropdown-link>

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                            {{ __('API Tokens') }}
                                        </x-dropdown-link>
                                    @endif

                                    @if (Auth::user()->role === 'tutor')
                                        <x-dropdown-link href="{{ route('tutor.profile.edit') }}">
                                            {{ __('Edit Tutor Profile') }}
                                        </x-dropdown-link>
                                    @endif

                                    <div class="border-t border-slate-100 my-1"></div>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}"
                                                 @click.prevent="$root.submit();" class="text-rose-600 font-medium">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile Toggle) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-50/98 border-t border-slate-100 backdrop-blur-md transition-all duration-200">
        <div class="pt-2 pb-3 space-y-1">
            @guest
                <x-responsive-nav-link href="{{ route('tutors.index') }}" :active="request()->routeIs('tutors.index')">
                    {{ __('Find Tutors') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                    {{ __('Become a Tutor') }}
                </x-responsive-nav-link>
                <div class="border-t border-slate-100 my-2 pt-2 px-4 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl">Log In</a>
                    <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl">Sign Up</a>
                </div>
            @endguest

            @auth
                @if (auth()->user()->role === 'learner')
                    <x-responsive-nav-link href="{{ route('learner.dashboard') }}" :active="request()->routeIs('learner.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('tutors.index') }}" :active="request()->routeIs('tutors.index')">
                        {{ __('Find Tutors') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('learner.bookings.index') }}" :active="request()->routeIs('learner.bookings.index')">
                        {{ __('My Bookings') }}
                    </x-responsive-nav-link>
                @elseif (auth()->user()->role === 'tutor')
                    <x-responsive-nav-link href="{{ route('tutor.dashboard') }}" :active="request()->routeIs('tutor.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('tutor.bookings.index') }}" :active="request()->routeIs('tutor.bookings.index')">
                        {{ __('Booking Requests') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('tutor.profile.show') }}" :active="request()->routeIs('tutor.profile.show')">
                        {{ __('My Tutor Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('tutor.subjects.edit') }}" :active="request()->routeIs('tutor.subjects.edit')">
                        {{ __('Subjects') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('tutor.earnings.index') }}" :active="request()->routeIs('tutor.earnings.index')">
                        {{ __('My Earnings') }}
                    </x-responsive-nav-link>
                @elseif (auth()->user()->role === 'admin')
                    <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.finances.index') }}" :active="request()->routeIs('admin.finances.index')">
                        {{ __('Platform Finances') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-4 border-t border-slate-200 bg-slate-100/50">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="size-10 rounded-full object-cover border border-slate-200" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-bold text-base text-slate-800 leading-none flex items-center gap-2">
                            <span>{{ Auth::user()->name }}</span>
                            @if (Auth::user()->role === 'admin')
                                <span class="px-2 py-0.5 text-[9px] font-extrabold text-purple-700 bg-purple-50 rounded-full border border-purple-100 uppercase tracking-wider">Admin</span>
                            @elseif (Auth::user()->role === 'tutor')
                                <span class="px-2 py-0.5 text-[9px] font-extrabold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-100 uppercase tracking-wider">Tutor</span>
                            @elseif (Auth::user()->role === 'learner')
                                <span class="px-2 py-0.5 text-[9px] font-extrabold text-blue-700 bg-blue-50 rounded-full border border-blue-100 uppercase tracking-wider">Learner</span>
                            @endif
                        </div>
                        <div class="font-medium text-xs text-slate-400 mt-1 leading-none">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Settings -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Account Settings') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif

                    @if (Auth::user()->role === 'tutor')
                        <x-responsive-nav-link href="{{ route('tutor.profile.edit') }}" :active="request()->routeIs('tutor.profile.edit')">
                            {{ __('Edit Tutor Profile') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link href="{{ route('logout') }}"
                                       @click.prevent="$root.submit();" class="text-rose-600 font-medium">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
