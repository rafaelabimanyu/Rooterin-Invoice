<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rooterin') }} — Enterprise Billing System</title>

        <!-- Fonts -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rooterin') }} — Enterprise Billing System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        @livewireStyles
    </head>
    <body class="h-full bg-[#f8f9fa] font-sans antialiased overflow-x-hidden">
        <div 
            class="flex min-h-screen" 
            x-data="{ 
                collapsed: $persist(false).as('sidebar-collapsed'), 
                mobileOpen: false,
                slideOverOpen: false,
                slideOverTitle: '',
                slideOverContent: ''
            }"
            @open-slide-over.window="slideOverOpen = true; slideOverTitle = $event.detail.title; slideOverContent = $event.detail.content"
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
                        <button @click="mobileOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
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
                            <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 text-[10px] font-black rounded-md transition-all {{ App::getLocale() == 'id' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">ID</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 text-[10px] font-black rounded-md transition-all {{ App::getLocale() == 'en' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">EN</a>
                        </div>

                        <!-- AI Voice Command Icon (CFO Suara) -->
                        <div x-data="{
                            listening: false,
                            resultText: '',
                            errorText: '',
                            recognition: null,
                            supported: true,
                            
                            init() {
                                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                                if (!SpeechRecognition) {
                                    this.supported = false;
                                    return;
                                }
                                this.recognition = new SpeechRecognition();
                                this.recognition.lang = 'id-ID';
                                this.recognition.continuous = false;
                                this.recognition.interimResults = false;
                                
                                this.recognition.onstart = () => {
                                    this.listening = true;
                                    this.resultText = '';
                                    this.errorText = '';
                                };
                                
                                this.recognition.onresult = (event) => {
                                    const transcript = event.results[0][0].transcript;
                                    this.resultText = transcript;
                                    this.processVoiceCommand(transcript);
                                };
                                
                                this.recognition.onerror = (event) => {
                                    console.error('Voice Command Error', event.error);
                                    if (event.error === 'not-allowed') {
                                        this.errorText = 'Akses mikrofon ditolak.';
                                    } else {
                                        this.errorText = 'Gagal mendengar suara.';
                                    }
                                    this.listening = false;
                                };
                                
                                this.recognition.onend = () => {
                                    this.listening = false;
                                };
                            },
                            
                            toggleListening() {
                                if (!this.supported) {
                                    alert('Voice command is not supported on this browser. Please use Chrome, Safari or Edge.');
                                    return;
                                }
                                if (this.listening) {
                                    this.recognition.stop();
                                } else {
                                    this.recognition.start();
                                }
                            },
                            
                            processVoiceCommand(text) {
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: {
                                        type: 'info',
                                        message: 'Memproses suara: &quot;' + text + '&quot;...'
                                    }
                                }));
                                
                                fetch('{{ route('ai-assistant.voice-command') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ command: text })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        if (data.redirect) {
                                            window.dispatchEvent(new CustomEvent('notify', {
                                                detail: {
                                                    type: 'success',
                                                    message: data.message || 'Mengalihkan halaman...'
                                                }
                                            }));
                                            setTimeout(() => {
                                                window.location.href = data.redirect;
                                            }, 1200);
                                        } else {
                                            this.showAdvisoryResult(data.title, data.message);
                                        }
                                    } else {
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                type: 'danger',
                                                message: data.message || 'Perintah tidak dikenali.'
                                            }
                                        }));
                                    }
                                })
                                .catch(err => {
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: {
                                            type: 'danger',
                                            message: 'Koneksi gagal atau error.'
                                        }
                                    }));
                                });
                            },
                            
                            showAdvisoryResult(title, message) {
                                this.$dispatch('open-slide-over', {
                                    title: title,
                                    content: `<div class='space-y-6'>
                                        <div class='w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-4 border border-indigo-100 shadow-sm'>
                                            <svg class='w-8 h-8' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                                                <path stroke-linecap='round' stroke-linejoin='round' d='M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z'></path>
                                            </svg>
                                        </div>
                                        <div class='bg-slate-50 border border-slate-200/60 p-6 rounded-2xl'>
                                            <p class='text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2'>AI Voice CFO Response</p>
                                            <h3 class='text-lg font-black text-slate-900 leading-snug mb-3'>\${title}</h3>
                                            <p class='text-sm text-slate-600 leading-relaxed'>\${message}</p>
                                        </div>
                                    </div>`
                                });
                            }
                        }" class="relative">
                            
                            <!-- Mic Button -->
                            <button @click="toggleListening()" 
                                    type="button" 
                                    class="p-2.5 rounded-xl border transition-all duration-300 relative focus:outline-none"
                                    :class="listening ? 'bg-rose-500 border-rose-500 text-white animate-pulse' : 'bg-slate-50 hover:bg-slate-100 border-slate-200/60 text-slate-500 hover:text-slate-900 active:scale-95'"
                                    :title="listening ? 'Rooterin sedang mendengarkan...' : 'Aktifkan CFO Suara'"
                            >
                                <i data-lucide="mic" class="w-4 h-4" :class="listening ? 'animate-bounce' : ''"></i>
                                
                                <!-- Active listener visual halo -->
                                <template x-if="listening">
                                    <span class="absolute -inset-1 rounded-xl border border-rose-500 opacity-75 animate-ping"></span>
                                </template>
                            </button>
                            
                            <!-- Listening Indicator Overlay Panel -->
                            <div x-show="listening" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute right-0 mt-3 w-72 md:w-80 bg-slate-900/95 backdrop-blur-xl border border-slate-800 text-white rounded-[24px] p-5 shadow-2xl z-[150] space-y-4"
                                 style="display: none;"
                                 x-cloak
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-rose-500 flex items-center justify-center text-white relative">
                                        <span class="absolute inset-0 rounded-lg bg-rose-500 animate-ping opacity-60"></span>
                                        <i data-lucide="mic" class="w-4 h-4 animate-bounce"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest leading-none">CFO Suara</p>
                                        <p class="text-xs font-bold text-slate-200 mt-1">Ayo bicara, Rooterin sedang mendengarkan...</p>
                                    </div>
                                </div>
                                <div class="bg-white/5 border border-white/10 rounded-xl p-3 min-h-[50px] flex items-center justify-center">
                                    <p class="text-xs font-semibold text-slate-300 italic text-center" x-text="resultText || 'Mulai berbicara sekarang...'"></p>
                                </div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest text-center">
                                    Contoh: "Buka halaman kalender"
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <livewire:navbar-notification />

                        <div class="h-6 w-px bg-slate-200"></div>

                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3.5 group focus:outline-none">
                                <div class="flex flex-col text-right hidden md:flex">
                                    <span class="text-[11px] font-black text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ Auth::user()->role }}</span>
                                </div>
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-xl object-cover shadow-lg shadow-slate-900/10 ring-2 ring-transparent group-hover:ring-indigo-500 transition-all duration-300">
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
                                        <i data-lucide="user" class="w-4 h-4 group-hover:text-indigo-500"></i> {{ __('Profile') }}
                                    </a>
                                    <a href="{{ route('security.center') }}" class="flex items-center gap-3 px-4 py-2.5 text-[12px] font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors group">
                                        <i data-lucide="shield" class="w-4 h-4 group-hover:text-indigo-500"></i> {{ __('ui.security_center') }}
                                    </a>
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
            </div>

            <!-- Global Slide-over Panel -->
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
                                class="pointer-events-auto w-screen max-w-2xl"
                            >
                                <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl border-l border-slate-200">
                                    <div class="px-8 py-10 sm:px-10 bg-slate-50/50 border-b border-slate-100">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h2 class="text-2xl font-black text-slate-900 font-jakarta tracking-tight uppercase" x-text="slideOverTitle"></h2>
                                                <p class="text-sm text-slate-500 font-medium mt-1">Detailed Intelligence Report</p>
                                            </div>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button @click="slideOverOpen = false" class="rounded-xl p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-all">
                                                    <i data-lucide="x" class="h-6 w-6"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative flex-1 px-8 py-10 sm:px-10">
                                        <!-- Content placeholder -->
                                        <div x-html="slideOverContent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <script>
            lucide.createIcons();
            window.addEventListener('alpine:initialized', () => {
                lucide.createIcons();
            });
        </script>
        @livewireScripts
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
                    info: 'bg-white border-indigo-500/20 text-indigo-600 shadow-indigo-500/10'
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
                lucide.createIcons();

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
    </body>
</html>
