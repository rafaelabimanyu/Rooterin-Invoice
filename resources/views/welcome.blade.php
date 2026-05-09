<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rooterin — Professional Billing for Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text { background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-glow { position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 800px; height: 400px; background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, rgba(255,255,255,0) 70%); z-index: -1; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white font-inter text-slate-900 antialiased overflow-x-hidden">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <span class="text-xl font-black font-outfit tracking-tight">Rooterin.</span>
            </div>
            <div class="hidden md:flex items-center gap-10">
                <a href="#features" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">Features</a>
                <a href="#solutions" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">Solutions</a>
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-900">Sign In</a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-xl shadow-indigo-600/20 hover:scale-105 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-44 pb-32">
        <div class="hero-glow"></div>
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full text-indigo-600 text-xs font-bold uppercase tracking-widest mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Next-Gen Billing System
            </div>
            <h1 class="text-6xl md:text-8xl font-black font-outfit leading-[0.95] tracking-tight mb-8">
                Professional Billing for <br> <span class="gradient-text">Enterprise</span> Operations.
            </h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                Empower your technical services business with high-fidelity invoicing, automated payment tracking, and professional B2B quotations.
            </p>
            <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full md:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-2xl shadow-slate-900/20 hover:-translate-y-1 transition-all">Start Your Workspace</a>
                <a href="#features" class="w-full md:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-900 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 bg-slate-50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black font-outfit mb-4">Powerful Features</h2>
                <p class="text-slate-500">Everything you need to manage your business billing cycle.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-white p-10 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14.5 2 14.5 7 20 7"/></svg>
                    </div>
                    <h3 class="text-xl font-bold font-outfit mb-4">SaaS-Level Invoicing</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Generate beautiful, high-conversion invoices that reflect your professional brand identity.</p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3 class="text-xl font-bold font-outfit mb-4">Payment Intelligence</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Track partial payments, deposits, and outstanding balances with real-time ledger updates.</p>
                </div>
                <div class="bg-white p-10 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all">
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="text-xl font-bold font-outfit mb-4">Client Relations</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Centralize customer documentation, job history, and billing profiles in one secure vault.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section id="solutions" class="py-32 bg-white scroll-mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black font-outfit mb-4">Industry Solutions</h2>
                <p class="text-slate-500">Tailored billing experiences for diverse technical service sectors.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-indigo-600 transition-all duration-500">
                    <h4 class="text-lg font-bold font-outfit group-hover:text-white mb-2">Plumbing</h4>
                    <p class="text-xs text-slate-500 group-hover:text-indigo-100 leading-relaxed">Leak fixing, pipe installation, and emergency maintenance billing.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-indigo-600 transition-all duration-500">
                    <h4 class="text-lg font-bold font-outfit group-hover:text-white mb-2">Contractors</h4>
                    <p class="text-xs text-slate-500 group-hover:text-indigo-100 leading-relaxed">Multi-stage project billing with deposit tracking and milestones.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-indigo-600 transition-all duration-500">
                    <h4 class="text-lg font-bold font-outfit group-hover:text-white mb-2">Technicians</h4>
                    <p class="text-xs text-slate-500 group-hover:text-indigo-100 leading-relaxed">Quick service invoicing for electrical, HVAC, and mechanical repairs.</p>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 group hover:bg-indigo-600 transition-all duration-500">
                    <h4 class="text-lg font-bold font-outfit group-hover:text-white mb-2">Renovation</h4>
                    <p class="text-xs text-slate-500 group-hover:text-indigo-100 leading-relaxed">Detailed material and labor breakdown for interior & exterior works.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 border-t border-slate-100 text-center bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <span class="text-lg font-black font-outfit tracking-tight">Rooterin.</span>
            </div>
            <div class="flex items-center justify-center gap-8 mb-10">
                <a href="#features" class="text-xs font-bold text-slate-400 hover:text-indigo-600">Features</a>
                <a href="#solutions" class="text-xs font-bold text-slate-400 hover:text-indigo-600">Solutions</a>
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600">Privacy Policy</a>
            </div>
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">© 2026 Rooterin Enterprise System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
