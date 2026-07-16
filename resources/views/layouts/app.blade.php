<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) && $title ? $title . ' | J&J GROUP - Sistem Operasional' : 'J&J GROUP - Sistem Operasional' }}</title>
        <link rel="icon" type="image/png" href="{{ asset('img/logo-jnj.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
    </head>
    <body class="h-full bg-[#f8f9fa] font-sans antialiased overflow-x-hidden">
        <div 
            class="flex min-h-screen" 
            x-data="{ 
                collapsed: $persist(false).as('sidebar-collapsed'), 
                mobileOpen: false,
                slideOverOpen: false,
                slideOverTitle: '',
                slideOverContent: '',
                slideOverLoading: false
            }"
            @open-slide-over.window="slideOverOpen = true; slideOverTitle = $event.detail.title; slideOverContent = $event.detail.content; slideOverLoading = false"
            @slide-over-loading-start.window="slideOverOpen = true; slideOverTitle = '{{ __('ui.loading_details') }}'; slideOverContent = ''; slideOverLoading = true"
        >
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Mobile Sidebar Backdrop -->
            <div 
                x-show="mobileOpen" 
                @click="mobileOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] lg:hidden"
                x-cloak
            ></div>

            <!-- Main Shell -->
            <div 
                class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out"
                x-bind:class="collapsed ? 'lg:ml-[72px]' : 'lg:ml-72'"
            >
                <!-- Header / Navbar -->
                <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm relative w-full">
                    <div class="max-w-[1600px] w-full mx-auto h-16 flex items-center justify-between px-4 sm:px-6 md:px-10 lg:px-14">
                    <div class="flex items-center gap-3 md:gap-8">
                        <!-- Toggle Button -->
                        <button @click="collapsed = !collapsed" class="hidden lg:flex p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all duration-300 group">
                            <i data-lucide="menu" class="w-5 h-5 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        <!-- Mobile Toggle -->
                        <button @click="mobileOpen = true; $dispatch('close-chat'); $dispatch('close-chatbot')" class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        
                        <div class="h-5 w-px bg-slate-200 hidden md:block"></div>
                        
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] leading-tight">System Status</span>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] animate-pulse"></span>
                                    <span class="text-[11px] font-bold text-slate-700">{{ __('ui.system_live') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 md:gap-6 shrink-0">
                        <!-- Language Switcher -->
                        <div class="flex items-center bg-slate-50 rounded-lg p-1 border border-slate-200">
                            <a href="{{ route('lang.switch', 'id') }}" @click="$dispatch('close-chat'); $dispatch('close-chatbot')" class="px-2.5 py-1 text-[10px] font-black rounded-md transition-all {{ App::getLocale() == 'id' ? 'bg-white text-gold-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">ID</a>
                            <a href="{{ route('lang.switch', 'en') }}" @click="$dispatch('close-chat'); $dispatch('close-chatbot')" class="px-2.5 py-1 text-[10px] font-black rounded-md transition-all {{ App::getLocale() == 'en' ? 'bg-white text-gold-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">EN</a>
                        </div>

                        @if(Auth::user()->role !== 'staff')
                        <!-- Notifications -->
                        <livewire:navbar-notification />

                        <div class="h-6 w-px bg-slate-200"></div>
                        @endif

                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open; if (open) { $dispatch('close-chat'); $dispatch('close-chatbot'); }" class="flex items-center gap-3.5 group focus:outline-none">
                                <div class="flex flex-col text-right hidden md:flex">
                                    <span class="text-[11px] font-black text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ Auth::user()->role }}</span>
                                </div>
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-xl object-cover shadow-lg shadow-slate-900/10 ring-2 ring-transparent group-hover:ring-gold-500 transition-all duration-300">
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                class="absolute right-0 mt-3 w-64 glass-card p-2 z-[100] border-slate-200/50 shadow-2xl"
                                x-cloak
                            >
                                <div class="px-4 py-4 border-b border-slate-100 mb-2">
                                    <p class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-[12px] font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors group">
                                        <i data-lucide="user" class="w-4 h-4 group-hover:text-gold-500"></i> {{ __('Profile') }}
                                    </a>
                                    @if(Auth::user()->role !== 'staff')
                                    <a href="{{ route('security.center') }}" class="flex items-center gap-3 px-4 py-2.5 text-[12px] font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors group">
                                        <i data-lucide="shield" class="w-4 h-4 group-hover:text-gold-500"></i> {{ __('ui.security_center') }}
                                    </a>
                                    @endif
                                </div>
                                <div class="h-px bg-slate-100 my-2 mx-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-[12px] font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-colors group">
                                        <i data-lucide="log-out" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i> {{ __('ui.logout') ?? 'End Session' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                </header>

                <!-- Content Area -->
                <main class="flex-1 overflow-x-hidden bg-[#f8f9fa]">
                    <div class="max-w-[1600px] w-full mx-auto px-4 sm:px-6 md:px-10 lg:px-14 py-8 md:py-12">
                        {{ $slot }}
                    </div>
                </main>

                <!-- Footer -->
                <x-footer />
            </div>            <!-- Global Slide-over Panel -->
            <template x-teleport="body">
                <div 
                    x-show="slideOverOpen" 
                    class="fixed inset-0 z-[100] overflow-hidden" 
                    x-cloak
                >
                    <div class="absolute inset-0 overflow-hidden">
                        <!-- Backdrop -->
                        <div 
                            x-show="slideOverOpen"
                            x-transition:enter="ease-in-out duration-500"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in-out duration-500"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                            @click="slideOverOpen = false"
                        ></div>

                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                            <div 
                                x-show="slideOverOpen"
                                x-transition:enter="transform transition ease-in-out duration-500 cubic-bezier-spring sm:duration-700"
                                x-transition:enter-start="translate-x-full"
                                x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in-out duration-500 cubic-bezier-spring sm:duration-700"
                                x-transition:leave-start="translate-x-0"
                                x-transition:leave-end="translate-x-full"
                                class="pointer-events-auto w-full max-w-md ml-auto"
                            >
                                <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl border-l border-slate-200">
                                    <div class="px-6 py-8 sm:px-10 bg-slate-50/50 border-b border-slate-100">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h2 class="text-xl sm:text-2xl font-black text-slate-900 font-jakarta tracking-tight uppercase" x-text="slideOverTitle"></h2>
                                                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Detailed Intelligence Report</p>
                                            </div>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button @click="slideOverOpen = false" class="rounded-xl p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all">
                                                    <i data-lucide="x" class="h-5 w-5 sm:h-6 sm:w-6"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative flex-1 px-6 py-8 sm:px-10">
                                        <!-- Loading state skeleton -->
                                        <div x-show="slideOverLoading" class="space-y-6 animate-pulse" x-cloak>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <div class="h-3 w-20 bg-slate-200 rounded"></div>
                                                    <div class="h-5 w-28 bg-slate-200 rounded"></div>
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="h-3 w-16 bg-slate-200 rounded"></div>
                                                    <div class="h-5 w-24 bg-slate-200 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="h-3 w-24 bg-slate-200 rounded"></div>
                                                <div class="h-5 w-full bg-slate-200 rounded"></div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <div class="h-3 w-20 bg-slate-200 rounded"></div>
                                                    <div class="h-5 w-32 bg-slate-200 rounded"></div>
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="h-3 w-24 bg-slate-200 rounded"></div>
                                                    <div class="h-5 w-28 bg-slate-200 rounded"></div>
                                                </div>
                                            </div>
                                            <div class="pt-6 border-t border-slate-100 space-y-4">
                                                <div class="h-4 w-32 bg-slate-200 rounded"></div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="h-16 bg-slate-200 rounded-2xl"></div>
                                                    <div class="h-16 bg-slate-200 rounded-2xl"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content placeholder -->
                                        <div x-show="!slideOverLoading" x-html="slideOverContent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- PDF Download Loading Overlay -->
        <div 
            x-data="pdfDownloader"
            x-show="show"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @download-pdf.window="download($event.detail.url, $event.detail.filename)"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-gradient-to-br from-[#05111E]/95 via-[#0F2A44]/90 to-[#173F66]/95 backdrop-blur-md border border-amber-500/10"
            x-cloak
        >
            <div class="bg-[#0B1E33]/85 border border-amber-500/30 shadow-[0_0_50px_rgba(212,175,55,0.15)] rounded-2xl p-8 max-w-sm w-full mx-4 text-center">
                <!-- Icon Loader with Pulse and Gold Accent -->
                <div class="relative flex justify-center mb-6">
                    <div class="absolute -inset-3 bg-[#D4AF37]/15 rounded-full blur-xl animate-pulse"></div>
                    <div class="relative w-16 h-16 rounded-2xl bg-[#0F2A44]/80 border border-amber-500/30 flex items-center justify-center text-[#D4AF37] shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                        <svg class="w-8 h-8 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Status Message -->
                <h3 class="text-transparent bg-clip-text bg-gradient-to-r from-[#FDB931] to-[#D4AF37] font-outfit font-black text-lg uppercase tracking-wider mb-2" x-text="statusText"></h3>
                <p class="text-slate-300 text-xs font-medium mb-6" x-text="subText"></p>

                <!-- Elegant Progress Bar Container -->
                <div class="w-full h-2.5 bg-slate-950/60 rounded-full overflow-hidden border border-amber-500/10 relative shadow-inner">
                    <div 
                        class="h-full rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(212,175,55,0.7)]"
                        :style="`width: ${progress}%; background: linear-gradient(90deg, #D4AF37, #FDB931);`"
                    ></div>
                </div>
                
                <div class="flex justify-between items-center mt-2.5 text-[10px] font-bold text-[#D4AF37] uppercase tracking-widest">
                    <span x-text="`${Math.round(progress)}%`"></span>
                    <span x-text="phaseText"></span>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            window.addEventListener('alpine:initialized', () => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            
            function registerPdfDownloader() {
                if (window.pdfDownloaderRegistered) return;
                window.pdfDownloaderRegistered = true;

                Alpine.data('pdfDownloader', () => ({
                    show: false,
                    progress: 0,
                    statusText: '',
                    subText: '',
                    phaseText: '',
                    intervalId: null,
                    
                    async download(url, filename) {
                        if (this.show) return;
                        
                        this.show = true;
                        this.progress = 0;
                        
                        // Phase 1: Memuat Data (0% - 30%)
                        this.statusText = 'Memuat Data...';
                        this.subText = 'Menghubungkan ke server dan menyiapkan basis data.';
                        this.phaseText = 'Loading';
                        
                        let targetProgress = 30;
                        let step = 1.5;
                        this.progress = 5;
                        
                        this.intervalId = setInterval(() => {
                            if (this.progress < targetProgress) {
                                this.progress += step;
                            } else if (this.progress >= 30 && this.progress < 80) {
                                // Phase 2: Merender Dokumen PDF (30% - 80%)
                                this.statusText = 'Merender Dokumen PDF...';
                                this.subText = 'Menyusun template HTML & memproses visual PDF.';
                                this.phaseText = 'Processing';
                                targetProgress = 80;
                                step = 0.5;
                                this.progress += step;
                            } else if (this.progress >= 80 && this.progress < 95) {
                                this.progress += 0.1;
                            }
                        }, 80);
                        
                        try {
                            const response = await fetch(url);
                            if (!response.ok) {
                                throw new Error('Render PDF gagal.');
                            }
                            
                            const blob = await response.blob();
                            
                            // Phase 3: Unduhan Dimulai! (80% - 100%)
                            clearInterval(this.intervalId);
                            this.progress = 100;
                            this.statusText = 'Unduhan Dimulai!';
                            this.subText = 'File PDF Anda sedang diunduh.';
                            this.phaseText = 'Download Triggered';
                            
                            let finalFilename = filename;
                            const disposition = response.headers.get('content-disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                const matches = filenameRegex.exec(disposition);
                                if (matches != null && matches[1]) { 
                                    finalFilename = matches[1].replace(/['"]/g, '');
                                }
                            }

                            const downloadUrl = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = downloadUrl;
                            a.download = finalFilename;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(downloadUrl);
                            
                            setTimeout(() => {
                                this.show = false;
                            }, 1000);
                            
                        } catch (error) {
                            clearInterval(this.intervalId);
                            this.show = false;
                            
                            this.$dispatch('notify', {
                                type: 'danger',
                                message: error.message || 'Gagal mengunduh dokumen PDF.'
                            });
                        }
                    }
                }));
            }

            if (window.Alpine) {
                registerPdfDownloader();
            } else {
                document.addEventListener('alpine:init', registerPdfDownloader);
            }
        </script>
        @livewireScripts
        @stack('scripts')
        <script>
            window.addEventListener('notify', event => {
                const data = event.detail[0] || event.detail;
                const type = data.type || 'success';
                const message = data.message || 'Transmission received.';
                
                // Professional Floating Toast Implementation
                const toast = document.createElement('div');
                toast.className = `fixed top-10 right-10 z-[1000] flex items-center gap-4 px-6 py-4 rounded-[24px] shadow-2xl border-2 transition-all duration-500 translate-x-full opacity-0 transform scale-95`;
                
                const themes = {
                    success: 'bg-white border-emerald-500/20 text-emerald-600 shadow-emerald-500/10',
                    warning: 'bg-white border-amber-500/20 text-amber-600 shadow-amber-500/10',
                    danger: 'bg-white border-rose-500/20 text-rose-600 shadow-rose-500/10',
                    info: 'bg-white border-gold-500/20 text-gold-600 shadow-gold-500/10'
                };
                const theme = themes[type] || themes.success;

                const icons = {
                    success: 'check-circle-2',
                    warning: 'alert-triangle',
                    danger: 'alert-octagon',
                    info: 'info'
                };
                const iconName = icons[type] || 'bell';

                toast.className += ` ${theme}`;
                toast.innerHTML = `
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: currentColor; color: white;">
                        <i data-lucide="${iconName}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-0.5">Intelligence Event</p>
                        <p class="text-sm font-bold text-slate-900">${message}</p>
                    </div>
                    <button class="ml-4 p-1 hover:bg-slate-50 rounded-lg transition-colors text-slate-300 hover:text-slate-900">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                `;

                document.body.appendChild(toast);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Animate In
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0', 'scale-95');
                }, 100);

                const closeToast = () => {
                    toast.classList.add('translate-x-full', 'opacity-0', 'scale-95');
                    setTimeout(() => toast.remove(), 500);
                };

                toast.querySelector('button').onclick = closeToast;
                setTimeout(closeToast, 5000);
            });
        </script>
        
        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((reg) => console.log('[Service Worker] Registered successfully:', reg.scope))
                        .catch((err) => console.error('[Service Worker] Registration failed:', err));
                });
            }
        </script>
    </body>
</html>
