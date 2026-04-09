<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ResQ') }} — Masuk</title>

        <!-- Fonts: Inter (same as landing page) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            *, *::before, *::after { box-sizing: border-box; }
            body { font-family: 'Inter', system-ui, sans-serif; overflow-x: hidden; }

            /* Animations */
            @keyframes float { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-12px); } }
            @keyframes slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes slide-down { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
            @keyframes glow { 0%,100% { box-shadow: 0 0 20px rgba(16,185,129,0.15); } 50% { box-shadow: 0 0 40px rgba(16,185,129,0.3); } }
            @keyframes gradient-x { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-slide-up { animation: slide-up 0.8s ease-out forwards; }
            .animate-slide-down { animation: slide-down 0.6s ease-out forwards; }
            .animate-fade-in { animation: fade-in 1s ease-out forwards; }
            .animate-glow { animation: glow 3s ease-in-out infinite; }
            .animate-gradient { background-size: 200% 200%; animation: gradient-x 4s ease infinite; }

            .delay-100 { animation-delay: 0.1s; }
            .delay-200 { animation-delay: 0.2s; }
            .delay-300 { animation-delay: 0.3s; }
            .delay-400 { animation-delay: 0.4s; }
            .delay-500 { animation-delay: 0.5s; }
            .delay-600 { animation-delay: 0.6s; }

            .opacity-0-start { opacity: 0; }

            /* Glass card (dark) */
            .glass-dark-card {
                background: rgba(15,23,42,0.6);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.06);
            }

            /* Noise texture overlay */
            .noise::after {
                content: ''; position: absolute; inset: 0; z-index: 1;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.02'/%3E%3C/svg%3E");
                pointer-events: none;
            }

            /* Particle dots */
            .particle { position: absolute; width: 4px; height: 4px; border-radius: 50%; background: rgba(16,185,129,0.3); animation: float 8s ease-in-out infinite; }

            /* Custom dark input */
            .dark-input {
                width: 100%;
                padding: 0.75rem 1rem;
                background: rgba(255,255,255,0.04);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 0.75rem;
                color: #e2e8f0;
                font-size: 0.875rem;
                transition: all 0.3s ease;
                outline: none;
            }
            .dark-input::placeholder { color: #64748b; }
            .dark-input:focus {
                border-color: rgba(16,185,129,0.5);
                box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
                background: rgba(255,255,255,0.06);
            }

            /* Dark label */
            .dark-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #94a3b8;
                margin-bottom: 0.5rem;
            }

            /* Emerald gradient button */
            .btn-emerald {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                width: 100%;
                padding: 0.8rem 1.5rem;
                background: linear-gradient(to right, #10b981, #059669);
                color: white;
                font-weight: 700;
                font-size: 0.875rem;
                border-radius: 9999px;
                border: none;
                cursor: pointer;
                box-shadow: 0 10px 25px -5px rgba(16,185,129,0.25);
                transition: all 0.3s ease;
            }
            .btn-emerald:hover {
                box-shadow: 0 20px 40px -5px rgba(16,185,129,0.4);
                transform: scale(1.02);
            }
            .btn-emerald:active { transform: scale(0.98); }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-white">
        <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-8 noise overflow-hidden">

            <!-- Radial glow background -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px]"></div>
            </div>
            <div class="absolute top-10 left-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-10 right-10 w-64 h-64 bg-primary-500/5 rounded-full blur-[120px] pointer-events-none"></div>

            <!-- Floating particles -->
            <div class="particle" style="top:12%;left:8%;animation-delay:0s;"></div>
            <div class="particle" style="top:20%;left:82%;animation-delay:2s;"></div>
            <div class="particle" style="top:72%;left:12%;animation-delay:4s;"></div>
            <div class="particle" style="top:65%;left:88%;animation-delay:1s;"></div>
            <div class="particle" style="top:38%;left:4%;animation-delay:3s;"></div>
            <div class="particle" style="top:85%;left:72%;animation-delay:5s;"></div>

            <!-- Logo -->
            <div class="relative z-10 opacity-0-start animate-slide-down mb-8">
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-primary-500 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">ResQ</span>
                </a>
            </div>

            <!-- Card -->
            <div class="relative z-10 w-full sm:max-w-md opacity-0-start animate-slide-up delay-200">
                <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500/20 to-primary-500/20 rounded-3xl blur-xl pointer-events-none"></div>
                <div class="relative glass-dark-card rounded-2xl p-8 sm:p-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="relative z-10 mt-8 text-sm text-slate-600 opacity-0-start animate-fade-in delay-500">
                &copy; {{ date('Y') }} ResQ — Sistem Mitigasi Bencana Berbasis AI
            </p>
        </div>
    </body>
</html>
