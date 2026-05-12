<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>rooterin.com — Master Sequence Operations</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;900&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --enterprise-navy: #0a0f1d;
            --titanium-white: #f8fafc;
            --electric-blue: #3b82f6;
        }

        html {
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: var(--enterprise-navy);
            color: var(--titanium-white);
            cursor: none;
            overflow-x: hidden;
        }

        #cursor-follower {
            position: fixed;
            width: 30px;
            height: 30px;
            background: var(--electric-blue);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            mix-blend-mode: screen;
            filter: blur(12px);
            opacity: 0.5;
            transition: transform 0.1s ease-out, opacity 0.3s ease;
            transform: translate(-50%, -50%);
        }

        /* --- Master Sequence Animations --- */
        .initial-hidden {
            opacity: 0;
            pointer-events: none;
        }

        @keyframes maskSlide {
            from {
                clip-path: inset(0 100% 0 0);
                transform: translateX(-50px);
                opacity: 0;
            }

            to {
                clip-path: inset(0 0 0 0);
                transform: translateX(0);
                opacity: 1;
            }
        }

        .hero-title-reveal {
            animation: maskSlide 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .fade-down {
            transform: translateY(-20px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fade-up {
            transform: translateY(30px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .visible {
            opacity: 1 !important;
            transform: translate(0) !important;
            pointer-events: auto !important;
        }

        /* --- Global Navbar Underline --- */
        .nav-link {
            position: relative;
            padding-bottom: 4px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--electric-blue);
            transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* --- Scroll Reveal System --- */
        .reveal-section {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-section.active {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-card {
            opacity: 0;
            transform: translateX(-30px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .stagger-card.active {
            opacity: 1;
            transform: translateX(0);
        }

        /* --- Cards & Visuals --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card:hover {
            border-color: var(--electric-blue);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.1);
        }

        .pulse-icon {
            animation: heartbeat 4s infinite ease-in-out;
        }

        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 0 transparent);
            }

            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 10px var(--electric-blue));
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--electric-blue) 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="font-jakarta antialiased" x-data="{ mobileMenu: false }">
    <div id="cursor-follower"></div>
    <canvas id="particle-canvas" class="initial-hidden">

    </canvas>

    <!-- Navbar -->
    <nav id="master-nav"
        class="fixed top-0 w-full z-[100] bg-[#0a0f1d]/80 backdrop-blur-3xl border-b border-white/5 initial-hidden fade-down">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-slate-900 shadow-xl transition-transform hover:rotate-12 duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-zap fill-current text-blue-600">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                    </svg>
                </div>
                <span class="text-2xl font-black font-outfit uppercase text-white tracking-tighter">rooterin<span
                        class="text-blue-500">.com</span></span>
            </div>

            <div class="hidden md:flex items-center gap-10">
                <a href="#core"
                    class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">Core
                    Systems</a>
                <a href="#capabilities"
                    class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">Capabilities</a>
                <a href="#solutions"
                    class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">Solutions</a>
                <div class="h-4 w-px bg-white/10"></div>
                <a href="{{ route('login') }}"
                    class="px-8 py-3 bg-white text-slate-900 rounded-2xl font-black text-[11px] shadow-2xl hover:bg-blue-50 transition-all uppercase tracking-[0.2em]">Portal
                    Login</a>
            </div>

            <button @click="mobileMenu = true" class="md:hidden p-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-menu">
                    <line x1="4" x2="20" y1="12" y2="12" />
                    <line x1="4" x2="20" y1="6" y2="6" />
                    <line x1="4" x2="20" y1="18" y2="18" />
                </svg>
            </button>
        </div>
    </nav>



    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="parallax-layer absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[150px]"
                data-depth="0.1"></div>
            <div class="parallax-layer absolute bottom-1/4 right-1/4 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[150px]"
                data-depth="0.2"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <div id="hero-tag"
                class="inline-flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-full text-blue-400 text-[10px] md:text-[11px] font-black uppercase tracking-[0.5em] mb-12 initial-hidden">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Operational Intelligence Hub
            </div>
            <h1 id="hero-title"
                class="text-6xl md:text-[160px] font-black leading-[0.75] tracking-tighter mb-12 uppercase font-outfit opacity-0">
                Next-Gen <br class="hidden md:block"> <span class="gradient-text">Operations.</span>
            </h1>
            <p id="hero-subtext"
                class="text-base md:text-xl text-slate-400 max-w-3xl mx-auto mb-16 font-medium leading-relaxed tracking-tight initial-hidden fade-up">
                The definitive operating system for high-stakes billing, autonomous tracking, and enterprise-grade
                financial intelligence at <span class="text-white font-bold">rooterin.com</span>.
            </p>
            <div id="hero-buttons"
                class="flex flex-col md:flex-row items-center justify-center gap-6 initial-hidden fade-up">
                <a href="{{ route('login') }}"
                    class="w-full md:w-auto px-16 py-7 bg-white text-slate-900 rounded-[40px] font-black shadow-[0_20px_50px_rgba(255,255,255,0.1)] hover:-translate-y-2 transition-all duration-500 uppercase tracking-widest text-[13px]">Initialize
                    Portal</a>
                <a href="#core"
                    class="w-full md:w-auto px-16 py-7 bg-white/5 border border-white/10 text-white rounded-[40px] font-black hover:bg-white/10 transition-all duration-500 uppercase tracking-widest text-[13px]">
                    Technical Specs
                </a>
            </div>
        </div>
    </section>

    <!-- 1. CORE SYSTEMS Section -->
    <section id="core" class="py-24 md:py-48 bg-[#0a0f1d] scroll-mt-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-32 reveal-section">
                <h2 class="text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">Core
                    Systems</h2>
                <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[12px]">Unified infrastructure by
                    rooterin.com</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div class="stagger-card p-12 group">
                    <div
                        class="w-20 h-20 bg-white/5 rounded-[32px] flex items-center justify-center text-white mb-10 pulse-icon transition-all group-hover:bg-blue-600 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-file-text">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14.5 2 14.5 7 20 7" />
                        </svg>
                    </div>
                    <h3
                        class="text-2xl font-black mb-6 uppercase tracking-tight text-white group-hover:text-blue-400 transition-colors">
                        Enterprise Ledger</h3>
                    <p class="text-base text-slate-500 leading-relaxed font-medium">Generate high-conversion,
                        professional documents that elevate your brand's technical authority.</p>
                </div>
                <div class="stagger-card p-12 group">
                    <div
                        class="w-20 h-20 bg-white/5 rounded-[32px] flex items-center justify-center text-white mb-10 pulse-icon transition-all group-hover:bg-blue-600 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-activity">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                    </div>
                    <h3
                        class="text-2xl font-black mb-6 uppercase tracking-tight text-white group-hover:text-blue-400 transition-colors">
                        Node Sync</h3>
                    <p class="text-base text-slate-500 leading-relaxed font-medium">Automated reconciliation and
                        real-time ledger updates for deposit and partial settlements.</p>
                </div>
                <div class="stagger-card p-12 group">
                    <div
                        class="w-20 h-20 bg-white/5 rounded-[32px] flex items-center justify-center text-white mb-10 pulse-icon transition-all group-hover:bg-blue-600 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-users">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3
                        class="text-2xl font-black mb-6 uppercase tracking-tight text-white group-hover:text-blue-400 transition-colors">
                        Entity Vault</h3>
                    <p class="text-base text-slate-500 leading-relaxed font-medium">A centralized vault for B2B
                        documentation, historical job logs, and entity billing profiles.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. CAPABILITIES Section -->
    <section id="capabilities" class="py-24 md:py-48 bg-[#0a0f1d] scroll-mt-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-32 reveal-section">
                <h2 class="text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">
                    Capabilities</h2>
                <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[12px]">Advanced technical
                    specifications.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="stagger-card glass-card p-12 rounded-[48px] group">
                    <div
                        class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-400 mb-8 pulse-icon group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-cpu">
                            <rect width="16" height="16" x="4" y="4" rx="2" />
                            <rect width="6" height="6" x="9" y="9" rx="1" />
                            <path d="M15 2v2" />
                            <path d="M15 20v2" />
                            <path d="M2 15h2" />
                            <path d="M2 9h2" />
                            <path d="M20 15h2" />
                            <path d="M20 9h2" />
                            <path d="M9 2v2" />
                            <path d="M9 20v2" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-tight text-white">Autonomous Ledger</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Eliminate manual entry with AI-driven transaction
                        matching and autonomous processing nodes.</p>
                </div>
                <div class="stagger-card glass-card p-12 rounded-[48px] group">
                    <div
                        class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-400 mb-8 pulse-icon group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-shield-lock">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                            <path d="M12 8v4" />
                            <path d="M12 16h.01" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-tight text-white">Deep Security Vault</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Enterprise-grade encryption for your most
                        sensitive financial data and B2B documentation vault.</p>
                </div>
                <div class="stagger-card glass-card p-12 rounded-[48px] group">
                    <div
                        class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-400 mb-8 pulse-icon group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-bar-chart-3">
                            <path d="M3 3v18h18" />
                            <path d="M18 17V9" />
                            <path d="M13 17V5" />
                            <path d="M8 17v-3" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-tight text-white">Real-time Analytics</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Monitor global operations with sub-second latency
                        data feeds and neural intelligence reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. SOLUTIONS Section -->
    <section id="solutions" class="py-24 md:py-48 bg-[#0a0f1d] scroll-mt-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-32 reveal-section">
                <h2 class="text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">
                    Solutions</h2>
                <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[12px]">Bespoke operational
                    excellence.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="stagger-card glass-card p-16 rounded-[56px] group hover:scale-[1.02]">
                    <div class="flex items-center gap-6 mb-10">
                        <div
                            class="w-16 h-16 bg-blue-600 rounded-[20px] flex items-center justify-center text-white shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-briefcase">
                                <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            </svg>
                        </div>
                        <h4 class="text-2xl font-black uppercase tracking-tight text-white">B2B Service Providers</h4>
                    </div>
                    <p class="text-slate-400 font-medium leading-relaxed">Tailored workflows for maintenance,
                        consulting, and high-fidelity technical service operations.</p>
                </div>
                <div class="stagger-card glass-card p-16 rounded-[56px] group hover:scale-[1.02]">
                    <div class="flex items-center gap-6 mb-10">
                        <div
                            class="w-16 h-16 bg-purple-600 rounded-[20px] flex items-center justify-center text-white shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-globe">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" x2="22" y1="12" y2="12" />
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                        </div>
                        <h4 class="text-2xl font-black uppercase tracking-tight text-white">Global Billing Nodes</h4>
                    </div>
                    <p class="text-slate-400 font-medium leading-relaxed">Multi-language and currency support for
                        cross-border operations requiring high precision.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-32 border-t border-white/5 text-center bg-[#0a0f1d] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex items-center justify-center gap-3 mb-10 reveal-section">
                <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-slate-900 shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-zap fill-current text-blue-600">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                    </svg>
                </div>
                <span class="text-3xl font-black font-outfit uppercase text-white tracking-tighter">rooterin<span
                        class="text-blue-500">.com</span></span>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 mb-12 reveal-section">
                <a href="#core"
                    class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Core
                    Systems</a>
                <a href="#capabilities"
                    class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Capabilities</a>
                <a href="#solutions"
                    class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Solutions</a>
            </div>
            <p class="text-[11px] text-slate-600 uppercase tracking-[0.5em] font-black reveal-section">© 2026
                rooterin.com Enterprise Operating System. All Nodes Operational.</p>
        </div>
    </footer>

    <script>
        // --- Master Sequence Logic ---
        window.addEventListener('load', () => {
            const title = document.getElementById('hero-title');
            const nav = document.getElementById('master-nav');
            const tag = document.getElementById('hero-tag');
            const subtext = document.getElementById('hero-subtext');
            const buttons = document.getElementById('hero-buttons');
            const particles = document.getElementById('particle-canvas');

            // 1. Lead Animation: Title reveal
            setTimeout(() => {
                title.classList.add('hero-title-reveal');
                title.style.opacity = 1;
            }, 500);

            // 2. Chain Reaction: Staggered reveal
            setTimeout(() => nav.classList.add('visible'), 1200);
            setTimeout(() => {
                tag.classList.add('visible');
                particles.classList.add('visible');
                subtext.classList.add('visible');
            }, 1600);
            setTimeout(() => buttons.classList.add('visible'), 2000);
        });

        // --- Custom Cursor ---
        const cursor = document.getElementById('cursor-follower');
        let mouseX = 0, mouseY = 0, cursorX = 0, cursorY = 0;
        window.addEventListener('mousemove', e => { mouseX = e.clientX; mouseY = e.clientY; });
        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.15;
            cursorY += (mouseY - cursorY) * 0.15;
            cursor.style.left = cursorX + 'px';
            cursor.style.top = cursorY + 'px';
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        // --- Interaction Effects ---
        const hoverables = document.querySelectorAll('a, button, .glass-card, .stagger-card');
        hoverables.forEach(el => {
            el.addEventListener('mouseenter', () => { cursor.style.transform = 'translate(-50%, -50%) scale(2.5)'; cursor.style.opacity = 0.8; });
            el.addEventListener('mouseleave', () => { cursor.style.transform = 'translate(-50%, -50%) scale(1)'; cursor.style.opacity = 0.5; });
        });

        // --- Scroll Reveal & Staggered Cards ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Stagger child cards if it's a section
                    if (entry.target.tagName === 'SECTION') {
                        const cards = entry.target.querySelectorAll('.stagger-card');
                        cards.forEach((card, i) => {
                            setTimeout(() => card.classList.add('active'), 200 + (i * 150));
                        });
                    }
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal-section, section').forEach(el => observer.observe(el));

        // --- Magnetic Particles ---
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
        window.addEventListener('resize', resize);

        class Particle {
            constructor() { this.init(); }
            init() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.baseX = this.x; this.baseY = this.y;
                this.size = Math.random() * 2 + 0.5;
                this.density = (Math.random() * 30) + 1;
                this.color = 'rgba(59, 130, 246, 0.4)';
            }
            update() {
                let dx = mouseX - this.x; let dy = mouseY - this.y;
                let distance = Math.sqrt(dx * dx + dy * dy);
                if (distance < 150) {
                    this.x += (dx / distance) * (150 - distance) / 10;
                    this.y += (dy / distance) * (150 - distance) / 10;
                } else {
                    this.x += (this.baseX - this.x) * 0.05;
                    this.y += (this.baseY - this.y) * 0.05;
                }
            }
            draw() {
                ctx.fillStyle = this.color; ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill();
            }
        }
        function initParticles() {
            particles = []; for (let i = 0; i < 150; i++) particles.push(new Particle());
        }
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }
        resize(); initParticles(); animate();

        // --- Parallax ---
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            document.querySelectorAll('.parallax-layer').forEach(layer => {
                layer.style.transform = `translateY(${scrolled * layer.getAttribute('data-depth')}px)`;
            });
        });
    </script>
</body>

</html>