<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Global SweetAlert Interceptor -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.body.addEventListener('submit', function(e) {
                    const form = e.target;
                    const confirmMessage = form.getAttribute('data-confirm');
                    
                    if (confirmMessage) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Are you sure?',
                            text: confirmMessage,
                            icon: 'warning',
                            iconColor: '#7c3aed', // Purple system color
                            showCancelButton: true,
                            confirmButtonColor: '#059669', // Emerald 600
                            cancelButtonColor: '#f43f5e', // Rose 500
                            confirmButtonText: 'Yes, proceed!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Remove attribute to prevent infinite loop and submit natively
                                form.removeAttribute('data-confirm');
                                form.submit();
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>
