<style>
    /* Enable native text selection */
    .select-text {
        user-select: text !important;
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
    }

    /* Pulse effect for the floating chatbot trigger button */
    .chatbot-trigger-btn {
        box-shadow: 0 8px 32px rgba(212, 175, 55, 0.4);
        animation: chatbot-pulse 3.5s infinite;
    }
    @keyframes chatbot-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.65);
        }
        75% {
            box-shadow: 0 0 0 15px rgba(212, 175, 55, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(212, 175, 55, 0);
        }
    }
    
    /* Markdown formatting inside light chat bubbles */
    .chatbot-content-html p { margin-bottom: 0.5rem; }
    .chatbot-content-html p:last-child { margin-bottom: 0; }
    .chatbot-content-html ul { list-style-type: disc; margin-left: 1.25rem; margin-bottom: 0.5rem; }
    .chatbot-content-html ol { list-style-type: decimal; margin-left: 1.25rem; margin-bottom: 0.5rem; }
    .chatbot-content-html li { margin-bottom: 0.25rem; }
    .chatbot-content-html strong { font-weight: 700; color: #0f172a; }
    .chatbot-content-html code { background-color: rgba(15, 23, 42, 0.05); padding: 0.1rem 0.25rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.9em; color: #0f172a; }
    
    /* Smooth custom scrollbar for messages container */
    .chatbot-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .chatbot-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .chatbot-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.2);
        border-radius: 9999px;
    }
    .chatbot-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.4);
    }
</style>

<div x-data="{ open: false, isFullSize: false, notificationOpen: false }" 
     @notification-toggle.window="notificationOpen = $event.detail.open" 
     @close-chatbot.window="open = false; isFullSize = false"
     @close-chat.window="open = false; isFullSize = false">
    
    <!-- Floating Trigger Button -->
    <button 
        x-show="!notificationOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click="open = !open; if(open) { $nextTick(() => { const container = $refs.chatContainer; if(container) container.scrollTop = container.scrollHeight; }) }" 
        class="chatbot-trigger-btn fixed bottom-4 right-4 md:bottom-6 md:right-6 z-[45] w-14 h-14 bg-gradient-to-tr from-gold-600 via-gold-700 to-gold-800 text-white rounded-full items-center justify-center border-2 border-white shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 group focus:outline-none"
        :class="open ? 'hidden sm:flex' : 'flex'"
    >
        <span class="relative flex items-center justify-center">
            <!-- Bot icon when closed -->
            <i x-show="!open" data-lucide="bot" class="w-6 h-6 transition-transform group-hover:rotate-6"></i>
            <!-- Close icon when open -->
            <i x-show="open" data-lucide="x" class="w-6 h-6 transition-transform group-hover:scale-90" style="display: none;"></i>
        </span>
    </button>

    <!-- Chat Window (Clean White Theme & Maximize Layout) -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 scale-90 translate-y-4" 
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
        x-transition:leave-end="opacity-0 scale-90 translate-y-4" 
        class="fixed z-40 bg-white border border-slate-200 shadow-[0_32px_64px_rgba(15,23,42,0.15)] overflow-hidden flex flex-col transition-all duration-300 ease-in-out text-slate-800"
        :class="isFullSize ? 'top-16 right-0 left-0 bottom-0 h-[calc(100vh-4rem)] w-full rounded-none md:inset-auto md:right-4 md:top-16 md:bottom-0 md:h-[calc(100vh-4rem)] md:w-[600px] md:max-w-[40%] md:rounded-t-[2rem]' : 'top-16 right-0 left-0 bottom-0 h-[calc(100vh-4rem)] w-full rounded-none sm:inset-auto sm:bottom-24 sm:right-6 sm:h-[580px] sm:w-[380px] sm:md:w-[400px] sm:rounded-[2rem]'"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-6 py-5 bg-slate-50 text-slate-800 flex items-center justify-between border-b border-slate-200 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-slate-800 shadow-sm border border-slate-200 overflow-hidden p-1 shrink-0">
                    <img src="{{ asset('img/logo-jnj.png') }}" alt="J&J GROUP Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h4 class="text-xs md:text-sm font-black font-jakarta leading-none uppercase tracking-wide text-slate-800">J&J GROUP AI Assistant</h4>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Online</span>
                    </div>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center gap-1.5">
                <!-- Maximize/Minimize size toggle -->
                <button 
                    @click="isFullSize = !isFullSize; $nextTick(() => { const container = $refs.chatContainer; if(container) container.scrollTop = container.scrollHeight; })" 
                    class="hidden sm:inline-flex p-1.5 hover:bg-slate-200/60 rounded-lg text-slate-500 hover:text-slate-800 transition-all active:scale-95" 
                    :title="isFullSize ? '{{ app()->getLocale() == 'en' ? 'Normal size' : 'Ukuran normal' }}' : '{{ app()->getLocale() == 'en' ? 'Maximize size' : 'Perbesar ukuran' }}'"
                >
                    <i x-show="!isFullSize" data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                    <i x-show="isFullSize" data-lucide="minimize-2" class="w-3.5 h-3.5" style="display: none;"></i>
                </button>
                
                <!-- Link to full AI Assistant page -->
                <a 
                    href="{{ route('ai-assistant.index') }}" 
                    class="p-1.5 hover:bg-slate-200/60 rounded-lg text-slate-500 hover:text-slate-800 transition-all active:scale-95" 
                    title="{{ app()->getLocale() == 'en' ? 'Open full-page chat' : 'Buka halaman chat penuh' }}"
                >
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>

                <!-- Close / Minimize window -->
                <button 
                    @click="open = false" 
                    class="p-1.5 hover:bg-slate-200/60 rounded-lg text-slate-500 hover:text-slate-800 transition-all active:scale-95"
                    title="{{ app()->getLocale() == 'en' ? 'Close' : 'Tutup' }}"
                >
                    <i data-lucide="minus" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div 
            x-ref="chatContainer" 
            class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/50 chatbot-scrollbar text-slate-800"
        >
            @foreach($messages as $idx => $msg)
                <div class="flex items-start gap-2.5 w-full {{ $msg['sender'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <!-- AI Avatar -->
                    @if($msg['sender'] === 'ai')
                        <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm mt-0.5 overflow-hidden p-0.5">
                            <img src="{{ asset('img/logo-jnj.png') }}" alt="J&J GROUP Logo" class="w-full h-full object-contain">
                        </div>
                    @endif

                    <div class="flex flex-col {{ $msg['sender'] === 'user' ? 'items-end' : 'items-start' }} max-w-[85%] w-fit select-text">
                        <!-- Sender Label -->
                        <span class="text-[9px] font-bold text-slate-400 mb-1 uppercase tracking-wider select-text">
                            {{ $msg['sender'] === 'user' ? (app()->getLocale() == 'en' ? 'You' : 'Anda') : 'Financial Advisor' }}
                        </span>
                        
                        <!-- Message Bubble -->
                        <div 
                            class="px-4 py-3 rounded-2xl text-xs leading-relaxed select-text {{ $msg['sender'] === 'user' ? 'bg-gold-500 text-slate-950 rounded-tr-none shadow-md shadow-gold-500/20 font-bold' : 'bg-white text-slate-800 border border-slate-200 rounded-tl-none shadow-sm font-medium' }}"
                        >
                            <div class="chatbot-content-html select-text">
                                @if($msg['sender'] === 'user')
                                    {{ $msg['text'] }}
                                @else
                                    {!! nl2br(preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($msg['text']))) !!}
                                @endif
                            </div>
                        </div>

                        <!-- Action button for page redirect tag -->
                        @if(isset($msg['navigateUrl']) && $msg['navigateUrl'])
                            <div class="mt-2 pl-1">
                                <a 
                                    href="{{ $msg['navigateUrl'] }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-gold-50 hover:bg-gold-100 border border-gold-200/80 text-gold-700 rounded-xl text-[10px] font-black transition-all shadow-sm hover:-translate-y-0.5 active:scale-95"
                                >
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>{{ $msg['navigateLabel'] }}</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- User Avatar -->
                    @if($msg['sender'] === 'user')
                        <div class="w-7 h-7 rounded-lg bg-gold-500 flex items-center justify-center text-slate-950 shrink-0 shadow-sm mt-0.5 border border-gold-500/20">
                            <span class="text-[10px] font-black uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Loading indicator for Livewire -->
            <div wire:loading wire:target="sendMessage" class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm mt-0.5 animate-pulse overflow-hidden p-0.5">
                    <img src="{{ asset('img/logo-jnj.png') }}" alt="J&J GROUP Logo" class="w-full h-full object-contain">
                </div>
                <div class="bg-white text-slate-400 rounded-2xl rounded-tl-none px-4 py-3 border border-slate-200 shadow-sm flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-gold-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-1.5 h-1.5 bg-gold-600 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        <!-- Input Form (Sticky Clean White Bar) -->
        <form 
            wire:submit.prevent="sendMessage" 
            class="p-4 bg-white border-t border-slate-100 flex items-center gap-3 shrink-0 sticky bottom-0 z-10"
            x-data="{
                handleSubmit() {
                    $nextTick(() => {
                        const container = $refs.chatContainer;
                        if (container) {
                            setTimeout(() => {
                                container.scrollTop = container.scrollHeight;
                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons({ root: container });
                                }
                            }, 250);
                        }
                    });
                }
            }"
            @submit.prevent="handleSubmit(); if ($refs.inputChatbox) $refs.inputChatbox.style.height = 'auto';"
        >
            <div class="relative flex-grow flex items-center">
                <textarea 
                    id="chatbot-message-input"
                    name="chatbot-message-input"
                    wire:model="input" 
                    x-ref="inputChatbox"
                    rows="1"
                    x-data="{
                        resize() {
                            $el.style.height = 'auto';
                            $el.style.height = ($el.scrollHeight) + 'px';
                        }
                    }"
                    x-init="resize()"
                    @input="resize()"
                    @keydown.enter.prevent="if(!$event.shiftKey) { $el.form.requestSubmit(); }"
                    placeholder="{{ app()->getLocale() == 'en' ? 'Ask about unpaid invoices or client list...' : 'Tanyakan invoice belum dibayar atau data klien...' }}" 
                    class="w-full pl-5 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-gold-500/40 focus:border-gold-500/80 focus:bg-white transition-all font-medium resize-none max-h-32 chatbot-scrollbar" 
                    wire:loading.attr="disabled"
                    wire:target="sendMessage"
                    required
                ></textarea>
                <button 
                    type="submit" 
                    class="absolute right-1.5 w-9 h-9 bg-gold-500 hover:bg-gold-600 text-slate-950 font-bold rounded-full flex items-center justify-center transition-all active:scale-95 disabled:opacity-50 shadow-md shadow-gold-500/15 focus:outline-none"
                    wire:loading.attr="disabled"
                    wire:target="sendMessage"
                >
                    <i data-lucide="send-horizontal" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Scroll chatbot to bottom when Livewire renders updates
    document.addEventListener('livewire:initialized', () => {
        const container = document.querySelector('[x-ref="chatContainer"]');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        // Listen for message events or component updates
        Livewire.hook('commit', ({ component, succeed }) => {
            succeed(() => {
                const el = document.querySelector('[x-ref="chatContainer"]');
                if (el) {
                    setTimeout(() => {
                        el.scrollTop = el.scrollHeight;
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons({ root: el });
                        }
                    }, 50);
                }
            });
        });
    });
</script>
