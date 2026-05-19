<x-app-layout>
    <!-- Add marked.js for markdown parsing -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Markdown Custom Styling for Chat Bubbles -->
    <style>
        .chat-bubble-content p { margin-bottom: 0.75rem; }
        .chat-bubble-content p:last-child { margin-bottom: 0; }
        .chat-bubble-content ul { list-style-type: disc; margin-left: 1.25rem; margin-bottom: 0.75rem; }
        .chat-bubble-content ol { list-style-type: decimal; margin-left: 1.25rem; margin-bottom: 0.75rem; }
        .chat-bubble-content li { margin-bottom: 0.35rem; }
        .chat-bubble-content strong { font-weight: 700; color: inherit; }
        .chat-bubble-content code { background-color: rgba(0, 0, 0, 0.05); padding: 0.125rem 0.25rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.875em; }
        .chat-scroll::-webkit-scrollbar { width: 5px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <div class="bg-slate-50/50 rounded-[2.5rem] border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row h-[calc(100vh-12rem)] min-h-[600px] font-sans"
        x-data="{
            input: '',
            messages: [
                { sender: 'ai', text: 'Halo! Saya Senior Financial Consultant & Business Analyst Virtual Anda. Ada yang bisa saya bantu terkait analisis arus kas, klien overdue, prediksi keuangan, atau navigasi sistem hari ini?' }
            ],
            loading: false,
            currentSessionId: null,
            sessions: {{ json_encode($sessions) }},
            routeMap: {
                'dashboard': '{{ route('dashboard') }}',
                'invoices.index': '{{ route('invoices.index') }}',
                'invoices.create': '{{ route('invoices.create') }}',
                'clients.index': '{{ route('clients.index') }}',
                'clients.create': '{{ route('clients.create') }}',
                'receipts.index': '{{ route('receipts.index') }}',
                'receipts.create': '{{ route('receipts.create') }}',
                'settings.index': '{{ route('settings.index') }}',
                'profile.edit': '{{ route('profile.edit') }}',
                'reports.index': '{{ route('reports.index') }}',
                'chronos.index': '{{ route('chronos.index') }}'
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
                'profile.edit': '👉 Edit Profil Saya',
                'reports.index': '👉 Buka Laporan Keuangan',
                'chronos.index': '👉 Buka Kalender Billing (Chronos)'
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
            newChat() {
                this.messages = [
                    { sender: 'ai', text: 'Halo! Saya Senior Financial Consultant & Business Analyst Virtual Anda. Ada yang bisa saya bantu terkait analisis arus kas, klien overdue, prediksi keuangan, atau navigasi sistem hari ini?' }
                ];
                this.currentSessionId = null;
                this.input = '';
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                });
            },
            loadSession(sessionId) {
                this.loading = true;
                this.currentSessionId = sessionId;
                
                fetch('/ai-assistant/session/' + sessionId)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to load session');
                        return res.json();
                    })
                    .then(data => {
                        this.loading = false;
                        if (data.success) {
                            this.messages = data.messages.length > 0 ? data.messages : [
                                { sender: 'ai', text: 'Halo! Saya Senior Financial Consultant & Business Analyst Virtual Anda. Ada yang bisa saya bantu terkait analisis keuangan hari ini?' }
                            ];
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.messages.push({ sender: 'ai', text: 'Maaf, gagal memuat riwayat obrolan: ' + err.message });
                        this.$nextTick(() => this.scrollToBottom());
                    });
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
                    body: JSON.stringify({ 
                        message: userMsg,
                        session_id: this.currentSessionId
                    })
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
                        
                        const isNewSession = !this.currentSessionId;
                        this.currentSessionId = data.session_id;
                        
                        if (isNewSession) {
                            this.refreshSessionsList();
                        }
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
            refreshSessionsList() {
                fetch('{{ route('ai-assistant.sessions-list') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.sessions = data.sessions;
                            this.$nextTick(() => {
                                if (typeof lucide !== 'undefined') lucide.createIcons();
                            });
                        }
                    })
                    .catch(err => console.error('Gagal me-refresh list session:', err));
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
        <!-- Sidebar: Chat History List -->
        <div class="w-full md:w-80 bg-white border-b md:border-b-0 md:border-r border-slate-200/80 flex flex-col justify-between shrink-0 h-1/3 md:h-full">
            <div class="flex flex-col h-full overflow-hidden">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider font-jakarta">Riwayat Obrolan</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Rooterin AI 2.0</p>
                    </div>
                    <button @click="newChat()" 
                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition-all active:scale-95 group" 
                        title="Mulai Obrolan Baru">
                        <i data-lucide="plus-circle" class="w-5 h-5 transition-transform group-hover:rotate-45"></i>
                    </button>
                </div>

                <!-- History Items -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2 chat-scroll">
                    <template x-for="sess in sessions" :key="sess.session_id">
                        <button @click="loadSession(sess.session_id)"
                            class="w-full text-left p-3.5 rounded-2xl transition-all flex flex-col gap-1.5 border"
                            :class="currentSessionId === sess.session_id 
                                ? 'bg-indigo-50/80 border-indigo-200 text-indigo-950 font-semibold shadow-sm' 
                                : 'bg-white hover:bg-slate-50 border-slate-100 text-slate-700 hover:text-slate-900'"
                        >
                            <div class="flex items-center gap-2 w-full">
                                <i data-lucide="message-square" 
                                    class="w-4 h-4 shrink-0"
                                    :class="currentSessionId === sess.session_id ? 'text-indigo-600' : 'text-slate-400'"
                                ></i>
                                <span class="text-xs font-bold truncate flex-1 leading-tight" x-text="sess.title"></span>
                            </div>
                            <span class="text-[9px] font-medium tracking-wide uppercase self-end"
                                :class="currentSessionId === sess.session_id ? 'text-indigo-500' : 'text-slate-400'"
                                x-text="sess.date_formatted"
                            ></span>
                        </button>
                    </template>
                    <template x-if="sessions.length === 0">
                        <div class="text-center py-12 px-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="history" class="w-6 h-6 text-slate-300"></i>
                            </div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Belum ada riwayat</p>
                            <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">Mulai kirim pesan untuk menyimpan log percakapan Anda.</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Sidebar Footer Security Center Link -->
            <div class="p-6 bg-slate-50/50 border-t border-slate-100 shrink-0 hidden md:block">
                <div class="flex items-center gap-2.5 text-slate-700 font-bold text-xs">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 border border-emerald-500/20">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <span>Sistem Aman & Terenkripsi</span>
                </div>
            </div>
        </div>

        <!-- Chat Area (Right Side) -->
        <div class="flex-1 flex flex-col h-2/3 md:h-full bg-slate-50/20">
            <!-- Chat Window Header -->
            <div class="px-8 py-5 bg-white border-b border-slate-200/80 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600 border border-indigo-200/40 shadow-sm">
                        <i data-lucide="bot" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 font-jakarta uppercase tracking-wide">Rooterin Financial Advisor</h4>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Konsultan Finansial Senior</span>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('dashboard') }}" class="p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 rounded-xl transition-colors border border-slate-200" title="Kembali ke Dashboard">
                    <i data-lucide="home" class="w-4.5 h-4.5"></i>
                </a>
            </div>

            <!-- Messages List & Initial Deck -->
            <div x-ref="chatContainer" class="flex-1 p-8 overflow-y-auto space-y-6 chat-scroll">
                
                <!-- Welcome & Suggestions Screen -->
                <template x-if="messages.length <= 1 && !loading">
                    <div class="max-w-2xl mx-auto py-8 space-y-8">
                        <div class="text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-3xl flex items-center justify-center text-white mx-auto shadow-lg shadow-indigo-500/20">
                                <i data-lucide="sparkles" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 font-jakarta uppercase tracking-tight">Selamat Datang di Rooterin AI 2.0</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-md mx-auto">
                                Ajukan pertanyaan tentang cashflow, analisis penagihan piutang, status klien, atau minta asisten untuk membuka halaman tertentu secara langsung.
                            </p>
                        </div>

                        <!-- Popular questions deck -->
                        <div class="space-y-3">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Rekomendasi Pertanyaan Populer</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button @click="sendSuggestion('Analisis arus kas bulan ini')" 
                                    class="text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors">Analisis Arus Kas</span>
                                        <p class="text-[10px] text-slate-400 font-medium">Bandingkan lunas &amp; overdue bulan ini.</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('Siapa klien yang paling sering menunggak?')" 
                                    class="text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors">Klien Terlambat Bayar</span>
                                        <p class="text-[10px] text-slate-400 font-medium">Deteksi klien dengan piutang menumpuk.</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('Buat laporan ringkas untuk meeting owner')" 
                                    class="text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors">Summary Executive Meeting</span>
                                        <p class="text-[10px] text-slate-400 font-medium">Ringkasan KPI operasional terkini.</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('Prediksi pendapatan 3 bulan ke depan')" 
                                    class="text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="line-chart" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors">Prediksi Pendapatan</span>
                                        <p class="text-[10px] text-slate-400 font-medium">Proyeksi kas berdasarkan tren 3 bulan.</p>
                                    </div>
                                </button>
                            </div>

                            <button @click="sendSuggestion('Bagaimana cara meningkatkan kolektibilitas invoice?')" 
                                class="w-full text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors">Optimalkan Kolektibilitas Invoice</span>
                                    <p class="text-[10px] text-slate-400 font-medium">Saran taktis untuk mengurangi piutang overdue dan menjaga perputaran kas.</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Actual Messages -->
                <template x-for="(msg, idx) in messages" :key="idx">
                    <div class="flex flex-col transition-all duration-300" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                        <!-- Message Bubble -->
                        <div class="max-w-[75%] px-5 py-4 rounded-3xl text-sm leading-relaxed" 
                            :class="msg.sender === 'user' 
                                ? 'bg-indigo-600 text-white rounded-tr-none shadow-md shadow-indigo-600/10 font-medium' 
                                : 'bg-white border border-slate-200 text-slate-800 rounded-tl-none shadow-sm'"
                        >
                            <div class="chat-bubble-content" x-html="renderMarkdown(msg.text)"></div>
                        </div>

                        <!-- Navigation Link Interceptor -->
                        <template x-if="msg.navigateUrl">
                            <div class="mt-2.5 pl-4">
                                <a :href="msg.navigateUrl" 
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-600 rounded-2xl text-xs font-bold transition-all shadow-sm hover:-translate-y-0.5 active:scale-98">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                    <span x-text="msg.navigateLabel"></span>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Loading / Thinking Bubble -->
                <div x-show="loading" class="flex items-start" style="display: none;">
                    <div class="bg-white border border-slate-200 text-slate-400 rounded-3xl rounded-tl-none px-5 py-4 shadow-sm flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-2 h-2 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <form @submit.prevent="sendMessage()" class="p-6 bg-white border-t border-slate-200 flex items-center gap-4 shrink-0">
                <input x-model="input" 
                    type="text" 
                    placeholder="Tanyakan analisis keuangan, tagihan overdue, atau jalankan perintah navigasi..." 
                    class="flex-1 px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all text-slate-800 font-medium" 
                    :disabled="loading"
                    autofocus>
                <button type="submit" 
                    class="w-12 h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl flex items-center justify-center shrink-0 transition-all active:scale-95 disabled:opacity-50 shadow-md shadow-indigo-600/10" 
                    :disabled="loading">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
