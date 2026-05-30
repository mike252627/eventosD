<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <!-- Bootstrap Icons CDN for sports theme -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Google Fonts: Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
        
        <style>
            body {
                background: radial-gradient(circle at 50% 50%, #1e293b 0%, #090d16 100%) !important;
                background-attachment: fixed !important;
                min-height: 100vh;
            }
            .glass-card {
                background: rgba(15, 23, 42, 0.65);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            }
            .brand-logo-glow {
                box-shadow: 0 0 35px rgba(245, 158, 11, 0.3);
            }
        </style>
    </head>
    <body class="min-h-screen antialiased text-white">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">
                <!-- Brand logo/Header -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 text-decoration-none group" wire:navigate>
                    <div class="bg-gradient-to-tr from-amber-400 to-yellow-300 p-2.5 rounded-2xl brand-logo-glow flex items-center justify-center transition-all duration-300 group-hover:scale-105">
                        <i class="bi bi-trophy-fill text-zinc-950 text-2xl"></i>
                    </div>
                    <span class="font-extrabold text-2xl tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-200" style="font-family: 'Outfit', sans-serif;">
                        INTERTEC
                    </span>
                </a>

                <!-- Slot (Forms) wrapped in premium glass-card -->
                <div class="glass-card p-6 md:p-8 rounded-3xl">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
