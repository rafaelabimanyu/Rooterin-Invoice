<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>J&J GROUP — Next-Gen Enterprise Operations</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-jnj.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --enterprise-navy: #0a0f1d;
            --titanium-white: #f8fafc;
            --accent-gold: #D4AF37;
        }

        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        
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
            background: var(--accent-gold);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            mix-blend-mode: screen;
            filter: blur(12px);
            opacity: 0.5;
            transition: transform 0.15s ease-out, opacity 0.3s ease;
            transform: translate(-50%, -50%);
        }

        @media (max-width: 768px) {
            #cursor-follower { display: none; }
            body { cursor: auto; }
        }

        /* --- Master Sequence Animations --- */
        .initial-hidden { opacity: 0; pointer-events: none; }

        @keyframes maskSlide {
            from { clip-path: inset(0 100% 0 0); transform: translateX(-20px); opacity: 0; }
            to { clip-path: inset(0 0 0 0); transform: translateX(0); opacity: 1; }
        }

        .hero-title-reveal {
            animation: maskSlide 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .fade-down { transform: translateY(-15px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .fade-up { transform: translateY(20px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .visible { opacity: 1 !important; transform: translate(0) !important; pointer-events: auto !important; }

        /* --- Seamless Canvas Integration --- */
        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            mix-blend-mode: lighten;
        }

        .hero-bg-gradient {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.08) 0%, var(--enterprise-navy) 80%);
            z-index: 0;
        }

        /* --- Mobile UI Overhaul & Typography --- */
        .responsive-title {
            font-size: clamp(2.5rem, 12vw, 9rem);
            line-height: 0.9;
            letter-spacing: -0.05em;
        }

        @media (max-width: 768px) {
            .responsive-title { font-size: 3rem; }
            .section-title { font-size: 2rem; }
        }

        .nav-link { position: relative; padding-bottom: 4px; }
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 0; height: 2px; background: var(--accent-gold);
            transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .nav-link:hover::after { width: 100%; }

        .reveal-section { opacity: 0; transform: translateY(30px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-section.active { opacity: 1; transform: translateY(0); }

        .stagger-card { opacity: 0; transform: translateY(15px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .stagger-card.active { opacity: 1; transform: translateY(0); }

        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover { border-color: var(--accent-gold); box-shadow: 0 0 40px rgba(212, 175, 55, 0.1); }

        .pulse-icon { animation: heartbeat 4s infinite ease-in-out; }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 0 transparent); }
            50% { transform: scale(1.1); filter: drop-shadow(0 0 10px var(--accent-gold)); }
        }

        .gradient-text { 
            background: linear-gradient(135deg, var(--accent-gold) 0%, #C5A059 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }

        /* --- Luxury Button Styling --- */
        .btn-primary-luxury {
            background: #ffffff;
            color: #0a0f1d;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-primary-luxury:hover {
            transform: translateY(-4px);
            background: var(--accent-gold);
            color: #0a0f1d;
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.25), 0 0 30px rgba(212, 175, 55, 0.15);
        }

        .btn-secondary-luxury {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-secondary-luxury:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--accent-gold);
            color: var(--accent-gold);
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.1);
        }

        /* --- Exit Loading Overlay Animation --- */
        @keyframes loadingBar {
            0% { left: -100%; width: 50%; }
            50% { width: 70%; }
            100% { left: 100%; width: 50%; }
        }

        @media (max-width: 768px) {
            #particle-canvas { display: none !important; }
        }
    </style>
</head>

<body class="font-jakarta antialiased" x-data="{ mobileMenu: false, exiting: false }" :class="{ 'overflow-hidden': exiting || mobileMenu }">
    <div id="cursor-follower"></div>
    <div class="hero-bg-gradient"></div>
    <canvas id="particle-canvas" class="initial-hidden"></canvas>

    <!-- Transition exiting screen overlay -->
    <div x-show="exiting" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         class="fixed inset-0 bg-[#0a0f1d] z-[9999] flex flex-col items-center justify-center pointer-events-auto"
         x-cloak>
        <div class="flex flex-col items-center gap-6">
            <x-portal-logo class="w-16 h-16 animate-pulse" />
            <div class="h-1 w-32 bg-white/10 rounded-full overflow-hidden relative">
                <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-gold-500 to-amber-500 rounded-full w-1/2 animate-[loadingBar_0.85s_ease-in-out_infinite]"></div>
            </div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] animate-pulse">Initializing Portal Node</span>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenu" 
         x-cloak 
         class="fixed inset-0 bg-[#0F2A44]/95 backdrop-blur-3xl z-[999] p-10 flex flex-col items-center justify-center text-center" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95">
        
        <button @click="mobileMenu = false" class="absolute top-6 right-6 p-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white hover:text-[#D4AF37] rounded-xl transition-all duration-300 focus:outline-none flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>

        <div class="flex flex-col gap-10">
            <a @click="mobileMenu = false" href="#core" class="text-2xl font-black text-white uppercase tracking-wider hover:text-[#D4AF37] transition-all duration-300">Core Systems</a>
            <a @click="mobileMenu = false" href="#capabilities" class="text-2xl font-black text-white uppercase tracking-wider hover:text-[#D4AF37] transition-all duration-300">Capabilities</a>
            <a @click="mobileMenu = false" href="#solutions" class="text-2xl font-black text-white uppercase tracking-wider hover:text-[#D4AF37] transition-all duration-300">Solutions</a>
            <a @click="mobileMenu = false" href="#ai-solutions" class="text-2xl font-black text-white uppercase tracking-wider hover:text-[#D4AF37] transition-all duration-300">AI Solutions</a>
            <div class="h-[2px] w-20 bg-[#D4AF37]/20 mx-auto my-2"></div>
            <a href="{{ route('login') }}" 
               @click.prevent="mobileMenu = false; exiting = true; setTimeout(() => window.location = $el.href, 850)"
               class="px-10 py-5 bg-transparent border border-[#D4AF37]/50 text-white rounded-3xl font-black shadow-[0_0_20px_rgba(212,175,55,0.15)] hover:bg-[#D4AF37] hover:text-[#0F2A44] hover:shadow-[0_0_30px_rgba(212,175,55,0.35)] transition-all duration-300 uppercase tracking-widest text-xs text-center">Portal Login</a>
        </div>
        
        <div class="absolute bottom-12 text-[10px] text-slate-400 font-black uppercase tracking-[0.4em]">jnjgroup.com</div>
    </div>

    <!-- Navbar -->
    <nav id="master-nav" class="fixed top-0 w-full z-[100] bg-[#0a0f1d]/80 backdrop-blur-3xl border-b border-white/5 initial-hidden fade-down">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-portal-logo />
                <span class="text-xl md:text-2xl font-black font-outfit uppercase text-white tracking-tighter">J&J <span class="text-gold-500">GROUP</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-10">
                <a href="#core" class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">Core Systems</a>
                <a href="#capabilities" class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">Capabilities</a>
                <a href="#solutions" class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">Solutions</a>
                <a href="#ai-solutions" class="nav-link text-[11px] font-black text-slate-400 hover:text-white transition-colors uppercase tracking-[0.3em]">AI Solutions</a>
                <div class="h-4 w-px bg-white/10"></div>
                <a href="{{ route('login') }}" 
                   @click.prevent="exiting = true; setTimeout(() => window.location = $el.href, 850)"
                   class="px-8 py-3 bg-white text-slate-900 rounded-2xl font-black text-[11px] shadow-2xl hover:bg-gold-50/50 transition-all uppercase tracking-[0.2em]">Portal Login</a>
            </div>

            <button @click="mobileMenu = true" class="md:hidden p-2.5 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 transition-all duration-300 text-white hover:text-gold-500 focus:outline-none flex items-center justify-center" aria-label="Open menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid">
                    <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                    <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                    <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                    <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center justify-center pt-24 px-8 overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div id="hero-tag" class="inline-flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-full text-gold-400 text-[10px] md:text-[11px] font-black uppercase tracking-[0.5em] mb-12 initial-hidden">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gold-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-gold-500"></span>
                </span>
                Operational Intelligence
            </div>
            <h1 id="hero-title" class="responsive-title font-black mb-12 uppercase font-outfit opacity-0">
                Next-Gen <br> <span class="gradient-text">Operations.</span>
            </h1>
            <p id="hero-subtext" class="text-[15px] sm:text-base md:text-xl text-slate-300 max-w-3xl mx-auto mb-16 font-medium leading-relaxed tracking-wide px-2 md:px-0 initial-hidden fade-up">
                The definitive operating system for high-stakes billing, autonomous tracking, and enterprise-grade financial intelligence at <span class="text-white font-bold">jnjgroup.com</span>.
            </p>
            <div id="hero-buttons" class="flex flex-col md:flex-row items-center justify-center gap-6 initial-hidden fade-up">
                <a href="{{ route('login') }}" 
                   @click.prevent="exiting = true; setTimeout(() => window.location = $el.href, 850)"
                   class="w-full md:w-auto px-16 py-7 btn-primary-luxury text-slate-900 rounded-[40px] font-black uppercase tracking-widest text-[13px] text-center">Initialize Portal</a>
                <a href="#core" class="w-full md:w-auto px-16 py-7 btn-secondary-luxury text-white rounded-[40px] font-black uppercase tracking-widest text-[13px] text-center">
                    Technical Specs
                </a>
            </div>
        </div>
    </section>

    <!-- Sections Flow -->
    <div class="relative z-10 space-y-32 md:space-y-56 pb-40">
        <!-- 1. CORE SYSTEMS -->
        <section id="core" class="scroll-mt-32 px-8 mt-24 md:mt-32">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-24 reveal-section">
                    <h2 class="section-title text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">Core Systems</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[12px]">Infrastructure by jnjgroup.com</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-16 px-2 md:px-0">
                    <div class="stagger-card p-10 md:p-12 group">
                        <div class="w-20 h-20 bg-white/5 rounded-[32px] flex items-center justify-center text-white mb-10 pulse-icon transition-all group-hover:bg-gold-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14.5 2 14.5 7 20 7"/></svg>
                        </div>
                        <h3 class="text-2xl font-black mb-6 uppercase tracking-tight text-white group-hover:text-gold-400">Enterprise Ledger</h3>
                        <p class="text-base text-slate-500 leading-relaxed font-medium">Generate high-conversion, professional documents that elevate your brand's technical authority.</p>
                    </div>
                    <div class="stagger-card p-10 md:p-12 group">
                        <div class="w-20 h-20 bg-white/5 rounded-[32px] flex items-center justify-center text-white mb-10 pulse-icon transition-all group-hover:bg-gold-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <h3 class="text-2xl font-black mb-6 uppercase tracking-tight text-white group-hover:text-gold-400">Node Sync</h3>
                        <p class="text-base text-slate-500 leading-relaxed font-medium">Automated reconciliation and real-time ledger updates for deposit and partial settlements.</p>
                    </div>
                    <div class="stagger-card p-10 md:p-12 group">
                        <div class="w-20 h-20 bg-white/5 rounded-[32px] flex items-center justify-center text-white mb-10 pulse-icon transition-all group-hover:bg-gold-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <h3 class="text-2xl font-black mb-6 uppercase tracking-tight text-white group-hover:text-gold-400">Entity Vault</h3>
                        <p class="text-base text-slate-500 leading-relaxed font-medium">A centralized vault for B2B documentation, historical job logs, and entity billing profiles.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. CAPABILITIES -->
        <section id="capabilities" class="scroll-mt-32 px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-24 reveal-section">
                    <h2 class="section-title text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">Capabilities</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[12px]">Advanced technical specs.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 px-2 md:px-0">
                    <div class="stagger-card glass-card p-10 rounded-[48px] group">
                        <div class="w-14 h-14 bg-gold-500/10 rounded-2xl flex items-center justify-center text-gold-400 mb-8 pulse-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cpu"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white">Autonomous Ledger</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Eliminate manual entry with AI-driven transaction matching and autonomous processing nodes.</p>
                    </div>
                    <div class="stagger-card glass-card p-10 rounded-[48px] group">
                        <div class="w-14 h-14 bg-gold-500/10 rounded-2xl flex items-center justify-center text-gold-400 mb-8 pulse-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-lock"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white">Deep Security Vault</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Enterprise-grade encryption for your most sensitive financial data and documentation vault.</p>
                    </div>
                    <div class="stagger-card glass-card p-10 rounded-[48px] group">
                        <div class="w-14 h-14 bg-gold-500/10 rounded-2xl flex items-center justify-center text-gold-400 mb-8 pulse-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white">Real-time Analytics</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Monitor global operations with sub-second latency data feeds and neural intelligence reports.</p>
                    </div>
                    <div class="stagger-card glass-card p-10 rounded-[48px] group border-gold-500/20 relative overflow-hidden">
                        <div class="absolute -right-16 -top-16 w-32 h-32 bg-gradient-to-br from-gold-500 via-[#C5A059] to-gold-300 opacity-20 rounded-full blur-2xl group-hover:opacity-40 transition-opacity pointer-events-none"></div>
                        <div class="w-14 h-14 bg-gradient-to-br from-gold-500 via-[#C5A059] to-gold-300 rounded-2xl flex items-center justify-center text-white mb-8 pulse-icon shadow-lg shadow-gold-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="m5 3 1 2.5L8.5 6 6 7 5 9.5 4 7 1.5 6 4 5 5 3Z"/><path d="m19 17 1 2.5 2.5.5-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1 1-2.5Z"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white group-hover:text-gold-300 transition-all">Cognitive Intelligence</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">Empower your financial workflow with Google Gemini-powered intelligence. Automated contextual billing drafts, real-time liquidity forecasting, and instant natural language system insights at your fingertips.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. SOLUTIONS -->
        <section id="solutions" class="scroll-mt-32 px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-24 reveal-section">
                    <h2 class="section-title text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">Solutions</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-[0.4em] text-[12px]">Bespoke excellence.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12 px-2 md:px-0">
                    <div class="stagger-card glass-card p-12 md:p-16 rounded-[56px] group">
                        <div class="flex items-center gap-6 mb-10">
                            <div class="w-16 h-16 bg-gold-600 rounded-[20px] flex items-center justify-center text-white shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            </div>
                            <h4 class="text-2xl font-black uppercase text-white">B2B Providers</h4>
                        </div>
                        <p class="text-slate-400 font-medium leading-relaxed">Tailored workflows for maintenance, consulting, and high-fidelity technical service operations.</p>
                    </div>
                    <div class="stagger-card glass-card p-12 md:p-16 rounded-[56px] group">
                        <div class="flex items-center gap-6 mb-10">
                            <div class="w-16 h-16 bg-gold-600 rounded-[20px] flex items-center justify-center text-white shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </div>
                            <h4 class="text-2xl font-black uppercase text-white">Global Nodes</h4>
                        </div>
                        <p class="text-slate-400 font-medium leading-relaxed">Multi-language and currency support for cross-border operations requiring high precision.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. INTELLIGENT SOLUTIONS (AI 2.0 Highlight) -->
        <section id="ai-solutions" class="scroll-mt-32 px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-24 reveal-section">
                    <h2 class="section-title text-4xl md:text-7xl font-black mb-6 uppercase tracking-tighter font-outfit text-white">AI Solutions</h2>
                    <p class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.4em] text-transparent bg-clip-text bg-gradient-to-r from-gold-400 via-amber-400 to-yellow-400">J&J GROUP AI 2.0 Engine</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12 px-2 md:px-0">
                    <div class="stagger-card glass-card p-12 rounded-[48px] relative overflow-hidden group hover:border-gold-500/30">
                        <div class="absolute -right-12 -top-12 w-28 h-28 bg-gold-500/5 rounded-full blur-2xl group-hover:bg-gold-500/10 transition-all pointer-events-none"></div>
                        <div class="w-14 h-14 bg-gold-600/10 border border-gold-500/20 text-gold-400 rounded-2xl flex items-center justify-center mb-8 pulse-icon group-hover:bg-gradient-to-br group-hover:from-gold-500 group-hover:to-gold-600 group-hover:text-white transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="m5 3 1 2.5L8.5 6 6 7 5 9.5 4 7 1.5 6 4 5 5 3Z"/><path d="m19 17 1 2.5 2.5.5-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1 1-2.5Z"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white group-hover:text-gold-400 transition-colors">AI Financial Advisory</h4>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">Real-time analysis of accounts receivable and cash flow patterns with instant strategic recommendations.</p>
                    </div>

                    <div class="stagger-card glass-card p-12 rounded-[48px] relative overflow-hidden group hover:border-gold-500/30">
                        <div class="absolute -right-12 -top-12 w-28 h-28 bg-gold-500/5 rounded-full blur-2xl group-hover:bg-gold-500/10 transition-all pointer-events-none"></div>
                        <div class="w-14 h-14 bg-gold-600/10 border border-gold-500/20 text-gold-400 rounded-2xl flex items-center justify-center mb-8 pulse-icon group-hover:bg-gradient-to-br group-hover:from-gold-500 group-hover:to-gold-600 group-hover:text-white transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wand2"><path d="m21.64 3.64-1.28-1.28a1.21 1.21 0 0 0-1.72 0L2.36 18.64a1.21 1.21 0 0 0 0 1.72l1.28 1.28a1.2 1.2 0 0 0 1.72 0L21.64 5.36a1.2 1.2 0 0 0 0-1.72Z"/><path d="m14 7 3 3"/><path d="M5 6v4"/><path d="M19 14v4"/><path d="M10 2v2"/><path d="M7 8H3"/><path d="M21 16h-4"/><path d="M11 3H9"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white group-hover:text-gold-400 transition-colors">Automated Billing Drafts</h4>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">High-conversion, multi-tone invoice notification drafts generated contextually in seconds.</p>
                    </div>

                    <div class="stagger-card glass-card p-12 rounded-[48px] relative overflow-hidden group hover:border-gold-500/30">
                        <div class="absolute -right-12 -top-12 w-28 h-28 bg-gold-500/5 rounded-full blur-2xl group-hover:bg-gold-500/10 transition-all pointer-events-none"></div>
                        <div class="w-14 h-14 bg-gold-600/10 border border-gold-500/20 text-gold-400 rounded-2xl flex items-center justify-center mb-8 pulse-icon group-hover:bg-gradient-to-br group-hover:from-gold-500 group-hover:to-gold-600 group-hover:text-white transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-messages-square"><path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/></svg>
                        </div>
                        <h4 class="text-xl font-black mb-4 uppercase text-white group-hover:text-gold-400 transition-colors">Omni Chatbot Assistant</h4>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">Advanced natural-language interface with dynamic session history and smart systemic action routing.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="py-32 border-t border-white/5 text-center bg-[#0a0f1d] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex items-center justify-center gap-3 mb-10 reveal-section">
                <x-portal-logo class="w-12 h-12" />
                <span class="text-3xl font-black font-outfit uppercase text-white tracking-tighter">jnjgroup<span class="text-gold-500">.com</span></span>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 mb-12 reveal-section">
                <a href="#core" class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Core Systems</a>
                <a href="#capabilities" class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Capabilities</a>
                <a href="#solutions" class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Solutions</a>
                <a href="#ai-solutions" class="text-[11px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">AI Solutions</a>
            </div>
            <p class="text-[11px] text-slate-600 uppercase tracking-[0.5em] font-black reveal-section">© 2026 jnjgroup.com Enterprise Operating System. All Nodes Operational.</p>
        </div>
    </footer>

    <script>
        // --- Master Sequence ---
        window.addEventListener('load', () => {
            const isMobile = window.innerWidth <= 768;
            const title = document.getElementById('hero-title');
            const nav = document.getElementById('master-nav');
            const tag = document.getElementById('hero-tag');
            const subtext = document.getElementById('hero-subtext');
            const buttons = document.getElementById('hero-buttons');
            const particles = document.getElementById('particle-canvas');

            // Sequence Timing
            setTimeout(() => { title.classList.add('hero-title-reveal'); title.style.opacity = 1; }, 400);
            setTimeout(() => nav.classList.add('visible'), 1000);
            setTimeout(() => { tag.classList.add('visible'); particles.classList.add('visible'); subtext.classList.add('visible'); }, 1400);
            setTimeout(() => buttons.classList.add('visible'), 1800);
        });

        // --- Interaction State ---
        let mouseX = 0, mouseY = 0, cursorX = 0, cursorY = 0;
        let isMobile = window.innerWidth <= 768;

        const updatePosition = e => {
            mouseX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            mouseY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        };
        window.addEventListener('mousemove', updatePosition);
        window.addEventListener('touchmove', updatePosition, {passive: true});

        // --- Custom Cursor ---
        const cursor = document.getElementById('cursor-follower');
        function animateCursor() {
            if(!isMobile) {
                cursorX += (mouseX - cursorX) * 0.15;
                cursorY += (mouseY - cursorY) * 0.15;
                cursor.style.left = cursorX + 'px';
                cursor.style.top = cursorY + 'px';
            }
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        // --- Particle System ---
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particleArray = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            isMobile = window.innerWidth <= 768;
            initParticles();
        }
        window.addEventListener('resize', resize);

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.baseX = this.x; this.baseY = this.y;
                this.size = Math.random() * (isMobile ? 1.5 : 2) + 0.5;
                this.density = (Math.random() * 30) + 1;
                this.trail = [];
            }
            draw() {
                ctx.fillStyle = 'rgba(212, 175, 55, 0.4)';
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill();
                
                if (!isMobile && this.trail.length > 0) {
                    ctx.beginPath();
                    ctx.moveTo(this.trail[0].x, this.trail[0].y);
                    for(let i=1; i<this.trail.length; i++) ctx.lineTo(this.trail[i].x, this.trail[i].y);
                    ctx.strokeStyle = `rgba(212, 175, 55, 0.05)`;
                    ctx.stroke();
                }
            }
            update() {
                let dx = mouseX - this.x; let dy = mouseY - this.y;
                let distance = Math.sqrt(dx * dx + dy * dy);
                let radius = isMobile ? 100 : 150;
                let force = (radius - distance) / radius;
                
                if (distance < radius) {
                    this.x += (dx / distance) * force * this.density;
                    this.y += (dy / distance) * force * this.density;
                } else {
                    this.x += (this.baseX - this.x) * 0.05;
                    this.y += (this.baseY - this.y) * 0.05;
                }

                if(!isMobile) {
                    this.trail.push({x: this.x, y: this.y});
                    if (this.trail.length > 5) this.trail.shift();
                }
            }
        }

        function initParticles() {
            particleArray = [];
            if (isMobile) return; // Completely disable particles on mobile to maximize performance
            let count = 120;
            for (let i = 0; i < count; i++) particleArray.push(new Particle());
        }

        function animate() {
            if (isMobile) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                requestAnimationFrame(animate);
                return;
            }
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particleArray.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }

        resize(); animate();

        // --- Reveal Logic ---
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    if (entry.target.tagName === 'SECTION') {
                        entry.target.querySelectorAll('.stagger-card').forEach((card, i) => {
                            setTimeout(() => card.classList.add('active'), 200 + (i * 120));
                        });
                    }
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal-section, section').forEach(el => observer.observe(el));
    </script>
</body>

</html>