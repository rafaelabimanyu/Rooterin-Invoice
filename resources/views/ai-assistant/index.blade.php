<x-app-layout>
    <!-- Add marked.js for markdown parsing -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Markdown Custom Styling for Chat Bubbles -->
    <style>
        .chat-bubble-content p { margin-bottom: 0.5rem; }
        .chat-bubble-content p:last-child { margin-bottom: 0; }
        .chat-bubble-content ul { list-style-type: disc; margin-left: 1.25rem; margin-bottom: 0.5rem; }
        .chat-bubble-content ol { list-style-type: decimal; margin-left: 1.25rem; margin-bottom: 0.5rem; }
        .chat-bubble-content li { margin-bottom: 0.25rem; }
        .chat-bubble-content strong { font-weight: 700; }
        .chat-bubble-content code { background-color: rgba(0, 0, 0, 0.05); padding: 0.125rem 0.25rem; rounded: 0.25rem; font-family: monospace; font-size: 0.875em; }
    </style>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col md:flex-row h-[calc(100vh-14rem)] min-h-[600px] font-sans"
        x-data="{
            input: '',
            messages: [
                { sender: 'ai', text: 'Halo! Saya Asisten Virtual Rooterin. Ada yang bisa saya bantu terkait analisis keuangan, tagihan, atau navigasi sistem hari ini?' }
            ],
            loading: false,
            routeMap: {
                'dashboard': '{{ route('dashboard') }}',
                'invoices.index': '{{ route('invoices.index') }}',
                'invoices.create': '{{ route('invoices.create') }}',
                'clients.index': '{{ route('clients.index') }}',
                'clients.create': '{{ route('clients.create') }}',
                'receipts.index': '{{ route('receipts.index') }}',
                'receipts.create': '{{ route('receipts.create') }}',
                'settings.index': '{{ route('settings.index') }}',
                'profile.edit': '{{ route('profile.edit') }}'
            },
            routeLabels: {
                'dashboard': '👉 Buka Dashboard Utama',
                'invoices.index': '👉 Lihat Daftar Invoice',
                'invoices.create': '👉 Buat Invoice Baru',
                'clients.index': '👉 Lihat Daftar Klien',
                'clients.create': '👉 Tambah Klien Baru',
                'receipts.index': '👉 Lihat Daftar Kuitansi',
                'receipts.create': '👉 Buat Kuitansi Baru',
                'settings.index': '👉 Buka Pengaturan',
                'profile.edit': '👉 Edit Profil Saya'
            },
            renderMarkdown(text) {
                if (typeof marked !== 'undefined') {
                    return marked.parse(text);
                }
                return text.replace(/\n/g, '<br>');
            },
            sendSuggestion(suggestionText) {
                this.input = suggestionText;
                this.sendMessage();
            },
            sendMessage() {
                if (!this.input.trim()) return;
                const userMsg = this.input;
                this.messages.push({ sender: 'user', text: userMsg });
                this.input = '';
                this.loading = true;
                
                this.$nextTick(() => {
                    this.scrollToBottom();
                });

                fetch('{{ route('ai-assistant.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: userMsg })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(errData => {
                            throw new Error(errData.error || 'Server error');
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    this.loading = false;
                    if (data.success) {
                        this.processResponse(data.reply);
                    } else {
                        this.messages.push({ sender: 'ai', text: 'Maaf, terjadi kesalahan saat memproses permintaan Anda.' });
                    }
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                })
                .catch(err => {
                    this.loading = false;
                    this.messages.push({ sender: 'ai', text: 'Maaf, gagal memproses. ' + err.message });
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                });
            },
            processResponse(reply) {
                // Check for [NAVIGATE: route_name]
                let routeName = null;
                let text = reply;
                const navRegex = /\[NAVIGATE:\s*([a-zA-Z0-9_\.-]+)\]/;
                const match = reply.match(navRegex);
                if (match) {
                    routeName = match[1].trim();
                    text = reply.replace(navRegex, '').trim();
                }
                
                const msg = { sender: 'ai', text: text };
                if (routeName && this.routeMap[routeName]) {
                    msg.navigateUrl = this.routeMap[routeName];
                    msg.navigateLabel = this.routeLabels[routeName] || '👉 Buka Halaman';
                }
                
                this.messages.push(msg);
            },
            scrollToBottom() {
                const container = this.$refs.chatContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }"
    >
        <!-- Sidebar Quick Guides & Suggestions -->
        <div class="w-full md:w-80 bg-slate-50 border-b md:border-b-0 md:border-r border-slate-200 p-6 flex flex-col justify-between shrink-0">
            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider font-jakarta">Rooterin AI Assistant</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Gunakan asisten kecerdasan buatan untuk mengelola invoicing, melihat insight finansial, dan mengakses navigasi secara instan.
                    </p>
                </div>

                <div class="h-px bg-slate-200"></div>

                <!-- Suggestions list -->
                <div class="space-y-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pertanyaan Populer</p>
                    
                    <button @click="sendSuggestion('Tampilkan ringkasan invoice belum lunas')" 
                        class="w-full text-left p-3 bg-white hover:bg-indigo-50/50 border border-slate-200 hover:border-indigo-200 rounded-2xl text-xs font-semibold text-slate-700 hover:text-indigo-600 transition-all flex items-center gap-2 group shadow-sm">
                        <i data-lucide="help-circle" class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 shrink-0"></i>
                        <span>Ringkasan belum lunas</span>
                    </button>

                    <button @click="sendSuggestion('Buka halaman buat invoice')" 
                        class="w-full text-left p-3 bg-white hover:bg-indigo-50/50 border border-slate-200 hover:border-indigo-200 rounded-2xl text-xs font-semibold text-slate-700 hover:text-indigo-600 transition-all flex items-center gap-2 group shadow-sm">
                        <i data-lucide="help-circle" class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 shrink-0"></i>
                        <span>Bagaimana cara buat invoice?</span>
                    </button>

                    <button @click="sendSuggestion('Tampilkan klien aktif')" 
                        class="w-full text-left p-3 bg-white hover:bg-indigo-50/50 border border-slate-200 hover:border-indigo-200 rounded-2xl text-xs font-semibold text-slate-700 hover:text-indigo-600 transition-all flex items-center gap-2 group shadow-sm">
                        <i data-lucide="help-circle" class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 shrink-0"></i>
                        <span>Berapa jumlah klien aktif?</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 md:mt-0 p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl">
                <div class="flex items-center gap-2 text-indigo-700 font-bold text-xs">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Sistem Aman</span>
                </div>
                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">
                    Semua transaksi dan data klien dilindungi enkripsi standard industri.
                </p>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col h-full bg-slate-50/30">
            <!-- Chat Window Header -->
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md">
                        <i data-lucide="bot" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold font-jakarta">Rooterin AI Chatbot</h4>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Online &amp; Siap Membantu</span>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('dashboard') }}" class="p-2 hover:bg-white/10 rounded-xl text-slate-400 hover:text-white transition-colors" title="Kembali ke Dashboard">
                    <i data-lucide="home" class="w-5 h-5"></i>
                </a>
            </div>

            <!-- Messages List -->
            <div x-ref="chatContainer" class="flex-1 p-6 overflow-y-auto space-y-4">
                <template x-for="(msg, idx) in messages" :key="idx">
                    <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                        <div class="max-w-[80%] px-5 py-3.5 rounded-2xl text-xs leading-relaxed" 
                            :class="msg.sender === 'user' ? 'bg-indigo-600 text-white rounded-tr-none shadow-md shadow-indigo-600/10' : 'bg-white border border-slate-200 text-slate-800 rounded-tl-none shadow-sm'">
                            <!-- Render Markdown Content Safely -->
                            <div class="chat-bubble-content" x-html="renderMarkdown(msg.text)"></div>
                        </div>

                        <!-- Intercepted Navigation Action Button -->
                        <template x-if="msg.navigateUrl">
                            <div class="mt-2 pl-4">
                                <a :href="msg.navigateUrl" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-600 rounded-xl text-xs font-bold transition-all shadow-sm hover:scale-102 active:scale-98">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span x-text="msg.navigateLabel"></span>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Loading Bubble -->
                <div x-show="loading" class="flex items-start" style="display: none;">
                    <div class="bg-white border border-slate-200 text-slate-500 rounded-2xl rounded-tl-none px-5 py-3.5 shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <form @submit.prevent="sendMessage()" class="p-6 bg-white border-t border-slate-200 flex items-center gap-3 shrink-0">
                <input x-model="input" 
                    type="text" 
                    placeholder="Tanyakan analisis keuangan, tagihan overdue, atau minta buka halaman..." 
                    class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all text-slate-800 font-medium" 
                    :disabled="loading"
                    autofocus>
                <button type="submit" 
                    class="w-11 h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center justify-center shrink-0 transition-all active:scale-95 disabled:opacity-50 shadow-md shadow-indigo-600/10" 
                    :disabled="loading">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
