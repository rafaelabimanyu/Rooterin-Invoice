<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rooterin — High-Fidelity Enterprise Operations</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-text { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-glow { position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 800px; height: 400px; background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, rgba(255,255,255,0) 70%); z-index: -1; }
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        
        /* Particle Background Effect */
        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
    </style>
</head>
<body class="bg-white font-jakarta text-slate-900 antialiased overflow-x-hidden" x-data="{ mobileMenu: false }">
    <!-- Particle System Canvas -->
    <canvas id="particle-canvas"></canvas>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-[100] bg-white/70 backdrop-blur-2xl border-b border-slate-100/50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-xl shadow-slate-900/10 transition-transform hover:rotate-12 duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap fill-current text-indigo-500"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase">Rooterin<span class="text-indigo-600">.</span></span>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-10">
                <a href="#capabilities" class="text-[11px] font-black text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-[0.2em]">Capabilities</a>
                <a href="#solutions" class="text-[11px] font-black text-slate-500 hover:text-indigo-600 transition-colors uppercase tracking-[0.2em]">Solutions</a>
                <div class="h-4 w-px bg-slate-200"></div>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black text-[11px] shadow-2xl shadow-slate-900/20 hover:scale-105 transition-all uppercase tracking-[0.2em]">Portal Login</a>
            </div>

            <!-- Mobile Toggle -->
            <button @click="mobileMenu = true" class="md:hidden p-2 text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenu" x-cloak class="fixed inset-0 bg-white z-[110] p-8 flex flex-col" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between mb-16">
                <span class="text-2xl font-black tracking-tighter uppercase">Rooterin<span class="text-indigo-600">.</span></span>
                <button @click="mobileMenu = false" class="p-3 bg-slate-100 rounded-2xl text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="flex flex-col gap-10">
                <a @click="mobileMenu = false" href="#capabilities" class="text-4xl font-black text-slate-900 uppercase tracking-tighter">Capabilities</a>
                <a @click="mobileMenu = false" href="#solutions" class="text-4xl font-black text-slate-900 uppercase tracking-tighter">Solutions</a>
                <div class="h-px bg-slate-100"></div>
                <a href="{{ route('login') }}" class="w-full py-5 bg-slate-900 text-white rounded-3xl font-black text-center shadow-2xl shadow-slate-900/20 uppercase tracking-widest">Portal Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 md:pt-56 pb-20 md:pb-32 overflow-hidden">
        <div class="hero-glow animate-pulse"></div>
        <div class="max-w-7xl mx-auto px-6 text-center page-fade-in">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-slate-900 rounded-full text-white text-[10px] md:text-[11px] font-black uppercase tracking-[0.4em] mb-12 shadow-2xl shadow-slate-900/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Neural Enterprise OS
            </div>
            <h1 class="text-6xl md:text-[130px] font-black leading-[0.8] tracking-tighter mb-12 uppercase">
                High <br class="hidden md:block"> <span class="gradient-text">Fidelity</span>.
            </h1>
            <p class="text-base md:text-xl text-slate-500 max-w-2xl mx-auto mb-16 font-medium leading-relaxed tracking-tight">
                The absolute standard for enterprise billing operations. Autonomous processing, high-conversion ledgers, and deep technical oversight.
            </p>
            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="{{ route('login') }}" class="w-full md:w-auto px-14 py-6 bg-slate-900 text-white rounded-[32px] font-black shadow-[0_20px_50px_rgba(0,0,0,0.2)] hover:-translate-y-2 transition-all duration-500 uppercase tracking-widest text-[12px]">Initialize Portal</a>
                <a href="#capabilities" class="w-full md:w-auto px-14 py-6 bg-white border border-slate-200 text-slate-900 rounded-[32px] font-black hover:bg-slate-50 transition-all duration-500 uppercase tracking-widest text-[12px]">
                    Capabilities
                </a>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section id="solutions" class="py-24 md:py-40 bg-slate-50/50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tighter">Unified Solutions</h2>
                <p class="text-slate-500 font-bold uppercase tracking-[0.3em] text-[12px]">Engineered for high-volume technical services.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="glass-card p-12 rounded-[48px] transition-all duration-500 group relative overflow-hidden hover:scale-[1.02]">
                    <div class="w-16 h-16 bg-slate-900 rounded-3xl flex items-center justify-center text-white mb-10 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14.5 2 14.5 7 20 7"/></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-6 uppercase tracking-tight">Enterprise Ledger</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">Generate high-conversion, professional documents that elevate your brand's technical authority.</p>
                </div>
                <div class="glass-card p-12 rounded-[48px] transition-all duration-500 group relative overflow-hidden hover:scale-[1.02]">
                    <div class="w-16 h-16 bg-slate-900 rounded-3xl flex items-center justify-center text-white mb-10 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-6 uppercase tracking-tight">Node Sync</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">Automated reconciliation and real-time ledger updates for deposit and partial settlements.</p>
                </div>
                <div class="glass-card p-12 rounded-[48px] transition-all duration-500 group relative overflow-hidden hover:scale-[1.02]">
                    <div class="w-16 h-16 bg-slate-900 rounded-3xl flex items-center justify-center text-white mb-10 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-6 uppercase tracking-tight">Entity Vault</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">A centralized vault for B2B documentation, historical job logs, and entity billing profiles.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Capabilities Section -->
    <section id="capabilities" class="py-24 md:py-40 bg-white scroll-mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tighter">Capabilities</h2>
                <p class="text-slate-500 font-bold uppercase tracking-[0.3em] text-[12px]">Technical excellence at every layer.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="p-10 border border-slate-100 rounded-3xl hover:border-indigo-100 transition-colors">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cpu"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-tight">Autonomous Processing</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">Neural-based job categorization and automated invoice generation for recurring services.</p>
                </div>
                <div class="p-10 border border-slate-100 rounded-3xl hover:border-emerald-100 transition-colors">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-lock"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-tight">Secure Data Vault</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">Military-grade encryption for all financial records and sensitive client documentation.</p>
                </div>
                <div class="p-10 border border-slate-100 rounded-3xl hover:border-rose-100 transition-colors">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-tight">Real-time Analytics</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">Deep-dive financial oversight with sub-second latency for all performance metrics.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 md:py-32 border-t border-slate-100 text-center bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex items-center justify-center gap-3 mb-10">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap fill-current text-indigo-500"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase">Rooterin<span class="text-indigo-600">.</span></span>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 mb-12">
                <a href="#capabilities" class="text-[11px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">Capabilities</a>
                <a href="#solutions" class="text-[11px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">Solutions</a>
                <a href="#" class="text-[11px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">Compliance</a>
            </div>
            <p class="text-[10px] text-slate-300 uppercase tracking-[0.4em] font-black">© 2026 Rooterin Enterprise Operating System. All nodes operational.</p>
        </div>
    </footer>

    <script>
        // Interactive Particle System
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        let mouse = { x: null, y: null };

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        window.addEventListener('resize', resize);
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.x;
            mouse.y = e.y;
        });

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = Math.random() * 0.5 - 0.25;
                this.speedY = Math.random() * 0.5 - 0.25;
                this.color = 'rgba(79, 70, 229, 0.15)';
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;

                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;

                // Mouse interaction
                if (mouse.x && mouse.y) {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < 150) {
                        this.x -= dx / 50;
                        this.y -= dy / 50;
                    }
                }
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            particles = [];
            for (let i = 0; i < 150; i++) {
                particles.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        }

        resize();
        init();
        animate();
    </script>
</body>
</html>
