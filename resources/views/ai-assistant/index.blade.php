<x-app-layout title="Rooterin AI Assistant">
    <div class="animate-fade-in-up">
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
        
        /* Table Styles inside AI response bubbles */
        .chat-bubble-content table { width: 100%; border-collapse: collapse; margin-top: 0.75rem; margin-bottom: 0.75rem; font-size: 0.85rem; }
        .chat-bubble-content th { background-color: #f1f5f9; border: 1px solid #e2e8f0; padding: 0.5rem; text-align: left; font-weight: 700; color: #1e293b; }
        .chat-bubble-content td { border: 1px solid #e2e8f0; padding: 0.5rem; color: #334155; }
        .chat-bubble-content tr:nth-child(even) { background-color: #f8fafc; }
        
        .chat-scroll::-webkit-scrollbar { width: 5px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* CSS Audio Wave Keyframes & Animations */
        @keyframes audioWave {
            0%, 100% { transform: scaleY(0.3); }
            50% { transform: scaleY(1); }
        }
        .animate-audio-wave-1 { animation: audioWave 0.8s ease-in-out infinite; transform-origin: bottom; }
        .animate-audio-wave-2 { animation: audioWave 0.5s ease-in-out infinite; transform-origin: bottom; animation-delay: 0.15s; }
        .animate-audio-wave-3 { animation: audioWave 0.7s ease-in-out infinite; transform-origin: bottom; animation-delay: 0.3s; }
        .animate-audio-wave-4 { animation: audioWave 0.6s ease-in-out infinite; transform-origin: bottom; animation-delay: 0.45s; }
    </style>

    <div class="bg-slate-50/50 rounded-3xl lg:rounded-[2.5rem] border border-slate-200/80 shadow-sm overflow-hidden flex flex-col lg:flex-row h-[calc(100vh-9rem)] lg:h-[calc(100vh-12rem)] min-h-[500px] lg:min-h-[600px] font-sans"
        x-data="aiChat()"
        x-init="initChat()"
    >
        <!-- Mobile Chat History Drawer (Sliding Sidebar) -->
        <div x-show="showDrawer" 
            class="relative z-[90] lg:hidden" 
            role="dialog" 
            aria-modal="true"
            style="display: none;"
        >
            <!-- Backdrop -->
            <div x-show="showDrawer"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                @click="showDrawer = false"
            ></div>

            <div class="fixed inset-y-0 left-0 z-50 flex max-w-full">
                <!-- Drawer Content -->
                <div x-show="showDrawer"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="w-screen max-w-xs transform bg-white shadow-2xl transition-all flex flex-col justify-between h-full border-r border-slate-200"
                >
                    <div class="flex flex-col h-full overflow-hidden">
                        <!-- Drawer Header -->
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                            <div>
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider font-jakarta">{{ app()->getLocale() == 'en' ? 'Chat History' : 'Riwayat Obrolan' }}</h3>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Rooterin AI 2.0</p>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button @click="newChat(); showDrawer = false" 
                                    class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition-all active:scale-95" 
                                    title="{{ app()->getLocale() == 'en' ? 'Start New Chat' : 'Mulai Obrolan Baru' }}">
                                    <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i>
                                </button>
                                <button @click="showDrawer = false" 
                                    class="p-2 hover:bg-slate-100 text-slate-400 hover:text-slate-600 rounded-xl transition-all"
                                    title="{{ app()->getLocale() == 'en' ? 'Close Menu' : 'Tutup Menu' }}">
                                    <i data-lucide="x" class="w-4.5 h-4.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Drawer History Items -->
                        <div x-ref="drawerHistoryContainer" class="flex-1 overflow-y-auto p-4 space-y-2.5 chat-scroll">
                            <template x-for="sess in sessions" :key="'drawer-' + sess.session_id">
                                <div class="relative group w-full flex items-center justify-between rounded-2xl border transition-all"
                                    :class="currentSessionId === sess.session_id 
                                        ? 'bg-indigo-50/80 border-indigo-200 text-indigo-950 font-semibold shadow-sm' 
                                        : 'bg-white hover:bg-slate-50 border-slate-100/90 text-slate-700 hover:text-slate-900'"
                                >
                                    <!-- Regular view -->
                                    <template x-if="editingSessionId !== sess.session_id">
                                        <div class="flex items-center w-full justify-between p-3.5 gap-2 pr-1">
                                            <button @click="loadSession(sess.session_id); showDrawer = false" class="flex-1 text-left flex flex-col gap-1.5 min-w-0">
                                                <div class="flex items-center gap-2 w-full">
                                                    <i data-lucide="message-square" 
                                                        class="w-4 h-4 shrink-0"
                                                        :class="currentSessionId === sess.session_id ? 'text-indigo-600' : 'text-slate-400'"
                                                    ></i>
                                                    <span class="text-xs font-bold truncate flex-1 leading-tight" x-text="sess.title"></span>
                                                </div>
                                                <span class="text-[9px] font-medium tracking-wide uppercase self-start"
                                                    :class="currentSessionId === sess.session_id ? 'text-indigo-500' : 'text-slate-400'"
                                                    x-text="sess.date_formatted"
                                                ></span>
                                            </button>
                                            
                                            <!-- Actions on mobile drawer -->
                                            <div class="flex items-center gap-1.5 shrink-0 px-2 bg-gradient-to-l from-white via-white/95 to-transparent absolute right-2 top-1/2 -translate-y-1/2 py-1.5 pl-4 rounded-lg"
                                                 :class="currentSessionId === sess.session_id ? 'from-indigo-50 via-indigo-50/95' : ''">
                                                <!-- Rename -->
                                                <button @click.stop="startRename(sess)" class="p-1 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all active:scale-90" title="Rename">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                                    </svg>
                                                </button>
                                                <!-- Delete -->
                                                <button @click.stop="deleteSession(sess.session_id)" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-all active:scale-90" title="Delete">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Editing view -->
                                    <template x-if="editingSessionId === sess.session_id">
                                        <div class="flex items-center gap-1.5 p-2 w-full">
                                            <input x-model="editingTitle" 
                                                @keydown.enter="saveRename(sess.session_id)"
                                                @keydown.escape="cancelRename()"
                                                type="text" 
                                                class="flex-1 px-2.5 py-1.5 text-xs bg-slate-50 border border-indigo-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 font-semibold text-slate-850"
                                                x-ref="drawerRenameInput"
                                            >
                                            <button @click="saveRename(sess.session_id)" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl transition-all active:scale-95" title="Save">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                </svg>
                                            </button>
                                            <button @click="cancelRename()" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all active:scale-95" title="Cancel">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="sessions.length === 0">
                                <div class="text-center py-12 px-4">
                                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                        <i data-lucide="history" class="w-6 h-6 text-slate-300"></i>
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'No history yet' : 'Belum ada riwayat' }}</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Chat History List (Desktop Only) -->
        <div class="hidden lg:flex lg:w-80 bg-white lg:border-r border-slate-200/80 flex-col justify-between shrink-0 lg:h-full">
            <div class="flex flex-col h-full overflow-hidden">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider font-jakarta">{{ app()->getLocale() == 'en' ? 'Chat History' : 'Riwayat Obrolan' }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Rooterin AI 2.0</p>
                    </div>
                    <button @click="newChat()" 
                        class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition-all active:scale-95 group" 
                        title="{{ app()->getLocale() == 'en' ? 'Start New Chat' : 'Mulai Obrolan Baru' }}">
                        <i data-lucide="plus-circle" class="w-5 h-5 transition-transform group-hover:rotate-45"></i>
                    </button>
                </div>

                <!-- History Items -->
                <div x-ref="historyContainer" class="flex-1 overflow-y-auto p-4 space-y-2.5 chat-scroll">
                    <template x-for="sess in sessions" :key="sess.session_id">
                        <div class="relative group w-full flex items-center justify-between rounded-2xl border transition-all"
                            :class="currentSessionId === sess.session_id 
                                ? 'bg-indigo-50/80 border-indigo-200 text-indigo-950 font-semibold shadow-sm' 
                                : 'bg-white hover:bg-slate-50 border-slate-100/90 text-slate-700 hover:text-slate-900'"
                        >
                            <!-- If not editing, show the regular button with actions on hover -->
                            <template x-if="editingSessionId !== sess.session_id">
                                <div class="flex items-center w-full justify-between p-3.5 gap-2 pr-1">
                                    <button @click="loadSession(sess.session_id)" class="flex-1 text-left flex flex-col gap-1.5 min-w-0">
                                        <div class="flex items-center gap-2 w-full">
                                            <i data-lucide="message-square" 
                                                class="w-4 h-4 shrink-0"
                                                :class="currentSessionId === sess.session_id ? 'text-indigo-600' : 'text-slate-400'"
                                            ></i>
                                            <span class="text-xs font-bold truncate flex-1 leading-tight" x-text="sess.title"></span>
                                        </div>
                                        <span class="text-[9px] font-medium tracking-wide uppercase self-start"
                                            :class="currentSessionId === sess.session_id ? 'text-indigo-500' : 'text-slate-400'"
                                            x-text="sess.date_formatted"
                                        ></span>
                                    </button>
                                    
                                    <!-- Hover actions -->
                                    <div class="hidden group-hover:flex items-center gap-1.5 shrink-0 px-2 bg-gradient-to-l from-slate-50 via-slate-50/95 to-transparent absolute right-2 top-1/2 -translate-y-1/2 py-1.5 pl-4 rounded-lg"
                                         :class="currentSessionId === sess.session_id ? 'from-indigo-50 via-indigo-50/95' : ''">
                                        <!-- Rename Button -->
                                        <button @click.stop="startRename(sess)" class="p-1 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all active:scale-90" title="{{ app()->getLocale() == 'en' ? 'Rename' : 'Ubah Nama' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                            </svg>
                                        </button>
                                        <!-- Delete Button -->
                                        <button @click.stop="deleteSession(sess.session_id)" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-all active:scale-90" title="{{ app()->getLocale() == 'en' ? 'Delete' : 'Hapus' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- If editing this item -->
                            <template x-if="editingSessionId === sess.session_id">
                                <div class="flex items-center gap-1.5 p-2 w-full">
                                    <input x-model="editingTitle" 
                                        @keydown.enter="saveRename(sess.session_id)"
                                        @keydown.escape="cancelRename()"
                                        type="text" 
                                        class="flex-1 px-2.5 py-1.5 text-xs bg-slate-50 border border-indigo-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 font-semibold text-slate-850"
                                        x-ref="renameInput"
                                    >
                                    <button @click="saveRename(sess.session_id)" class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl transition-all active:scale-95" title="{{ app()->getLocale() == 'en' ? 'Save' : 'Simpan' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                        </svg>
                                    </button>
                                    <button @click="cancelRename()" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all active:scale-95" title="{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="sessions.length === 0">
                        <div class="text-center py-12 px-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="history" class="w-6 h-6 text-slate-300"></i>
                            </div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'No history yet' : 'Belum ada riwayat' }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">{{ app()->getLocale() == 'en' ? 'Start sending messages to save your conversation log.' : 'Mulai kirim pesan untuk menyimpan log percakapan Anda.' }}</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Sidebar Footer Security Center Link -->
            <div class="p-6 bg-slate-50/50 border-t border-slate-100 shrink-0 hidden lg:block">
                <div class="flex items-center gap-2.5 text-slate-700 font-bold text-xs">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600 border border-emerald-500/20">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <span>{{ app()->getLocale() == 'en' ? 'System Secure & Encrypted' : 'Sistem Aman & Terenkripsi' }}</span>
                </div>
            </div>
        </div>

        <!-- Chat Area (Right Side) -->
        <div class="flex-1 flex flex-col h-full bg-slate-50/20 min-w-0">
            <!-- Chat Window Header -->
            <div class="px-4 lg:px-8 py-4 lg:py-5 bg-white border-b border-slate-200/80 flex items-center justify-between gap-3 shrink-0 flex-wrap sm:flex-nowrap">
                <div class="flex items-center gap-3 lg:gap-4 min-w-0">
                    <!-- Drawer Toggle Button (Mobile Only) -->
                    <button @click="showDrawer = true; $nextTick(() => { if (typeof lucide !== 'undefined') { lucide.createIcons(); } })" 
                        class="lg:hidden p-2 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 rounded-xl border border-slate-200/80 transition-all active:scale-95 shrink-0"
                        title="{{ app()->getLocale() == 'en' ? 'Open History' : 'Buka Riwayat' }}">
                        <i data-lucide="history" class="w-4.5 h-4.5"></i>
                    </button>
                    
                    <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600 border border-indigo-200/40 shadow-sm relative transition-all duration-300 shrink-0"
                        :class="loading ? 'animate-pulse ring-4 ring-indigo-500/25' : ''">
                        <i data-lucide="bot" class="w-5.5 h-5.5 lg:w-6 lg:h-6 text-indigo-600"></i>
                    </div>
                    
                    <div class="min-w-0">
                        <h4 class="text-xs lg:text-sm font-black text-slate-900 font-jakarta uppercase tracking-wide truncate leading-tight">Rooterin Financial Advisor</h4>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="relative flex h-1.5 w-1.5 lg:h-2 lg:w-2 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 lg:h-2 lg:w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[8px] lg:text-[9px] text-slate-400 font-bold uppercase tracking-wider truncate">{{ app()->getLocale() == 'en' ? 'Senior Financial Consultant' : 'Konsultan Finansial Senior' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('dashboard') }}" class="p-2 lg:p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 rounded-xl transition-colors border border-slate-200" title="{{ app()->getLocale() == 'en' ? 'Back to Dashboard' : 'Kembali ke Dashboard' }}">
                        <i data-lucide="home" class="w-4 h-4 lg:w-4.5 lg:h-4.5"></i>
                    </a>
                </div>
            </div>

            <!-- Messages List & Initial Deck -->
            <div x-ref="chatContainer" class="flex-1 p-4 lg:p-8 overflow-y-auto space-y-4 lg:space-y-6 chat-scroll">
                
                <!-- Welcome & Suggestions Screen -->
                <template x-if="messages.length <= 1 && !loading">
                    <div class="max-w-2xl mx-auto py-4 lg:py-8 space-y-6 lg:space-y-8">
                        <div class="text-center space-y-2 lg:space-y-3">
                            <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-[1.25rem] lg:rounded-3xl flex items-center justify-center text-white mx-auto shadow-lg shadow-indigo-500/20">
                                <i data-lucide="sparkles" class="w-6 h-6 lg:w-8 lg:h-8"></i>
                            </div>
                            <h3 class="text-base lg:text-lg font-black text-slate-900 font-jakarta uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Welcome to Rooterin AI 2.0' : 'Selamat Datang di Rooterin AI 2.0' }}</h3>
                            <p class="text-[11px] lg:text-xs text-slate-500 font-medium leading-relaxed max-w-md mx-auto px-4 lg:px-0">
                                {{ app()->getLocale() == 'en' ? 'Ask questions about cash flow, receivable analysis, client status, or ask the assistant to navigate to a specific page directly.' : 'Ajukan pertanyaan tentang cashflow, analisis penagihan piutang, status klien, atau minta asisten untuk membuka halaman tertentu secara langsung.' }}
                            </p>
                        </div>

                        <!-- Popular questions deck -->
                        <div class="space-y-3 w-full max-w-full overflow-hidden">
                            <p class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center px-4">{{ app()->getLocale() == 'en' ? 'Recommended Popular Questions' : 'Rekomendasi Pertanyaan Populer' }}</p>
                            
                            <!-- Container: Horizontal Scroll on Mobile, Grid on Desktop -->
                            <div class="flex lg:grid lg:grid-cols-2 overflow-x-auto lg:overflow-x-visible pb-3 lg:pb-0 gap-3.5 lg:gap-4 snap-x snap-mandatory scroll-smooth chat-scroll px-4 lg:px-0">
                                
                                <button @click="sendSuggestion('{{ app()->getLocale() == 'en' ? 'This month\'s cash flow analysis' : 'Analisis arus kas bulan ini' }}')" 
                                    class="w-[280px] sm:w-[320px] lg:w-auto shrink-0 lg:shrink snap-start text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors block truncate">{{ app()->getLocale() == 'en' ? 'Cash Flow Analysis' : 'Analisis Arus Kas' }}</span>
                                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ app()->getLocale() == 'en' ? 'Compare paid & overdue this month.' : 'Bandingkan lunas & overdue bulan ini.' }}</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('{{ app()->getLocale() == 'en' ? 'Who are the most overdue clients?' : 'Siapa klien yang paling sering menunggak?' }}')" 
                                    class="w-[280px] sm:w-[320px] lg:w-auto shrink-0 lg:shrink snap-start text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors block truncate">{{ app()->getLocale() == 'en' ? 'Overdue Clients' : 'Klien Terlambat Bayar' }}</span>
                                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ app()->getLocale() == 'en' ? 'Detect clients with accumulated receivables.' : 'Deteksi klien dengan piutang menumpuk.' }}</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('{{ app()->getLocale() == 'en' ? 'Create summary report for owner meeting' : 'Buat laporan ringkas untuk meeting owner' }}')" 
                                    class="w-[280px] sm:w-[320px] lg:w-auto shrink-0 lg:shrink snap-start text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors block truncate">{{ app()->getLocale() == 'en' ? 'Summary Executive Meeting' : 'Ringkasan Executive Meeting' }}</span>
                                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ app()->getLocale() == 'en' ? 'Summary of latest operational KPIs.' : 'Ringkasan KPI operasional terkini.' }}</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('{{ app()->getLocale() == 'en' ? 'Revenue projection for the next 3 months' : 'Prediksi pendapatan 3 bulan ke depan' }}')" 
                                    class="w-[280px] sm:w-[320px] lg:w-auto shrink-0 lg:shrink snap-start text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="line-chart" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors block truncate">{{ app()->getLocale() == 'en' ? 'Revenue Projection' : 'Prediksi Pendapatan' }}</span>
                                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ app()->getLocale() == 'en' ? 'Cash projection based on 3-month trend.' : 'Proyeksi kas berdasarkan tren 3 bulan.' }}</p>
                                    </div>
                                </button>

                                <button @click="sendSuggestion('{{ app()->getLocale() == 'en' ? 'How to improve invoice collectibility?' : 'Bagaimana cara meningkatkan kolektibilitas invoice?' }}')" 
                                    class="w-[280px] sm:w-[320px] lg:w-auto lg:col-span-2 shrink-0 lg:shrink snap-start text-left p-4 bg-white hover:bg-indigo-50/30 border border-slate-200 hover:border-indigo-300 rounded-2xl transition-all flex items-center gap-3.5 group shadow-sm hover:shadow active:scale-98">
                                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-all">
                                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="text-xs font-bold text-slate-800 group-hover:text-indigo-950 transition-colors block truncate">{{ app()->getLocale() == 'en' ? 'Optimize Invoice Collectibility' : 'Optimalkan Kolektibilitas Invoice' }}</span>
                                        <p class="text-[10px] text-slate-400 font-medium truncate">{{ app()->getLocale() == 'en' ? 'Tactical advice to reduce overdue receivables and maintain cash rotation.' : 'Saran taktis untuk mengurangi piutang overdue dan menjaga perputaran kas.' }}</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Actual Messages -->
                <template x-for="(msg, idx) in messages" :key="idx">
                    <div class="flex flex-col transition-all duration-300" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                        <!-- Message Bubble -->
                        <div class="max-w-[85%] lg:max-w-[75%] px-4 lg:px-5 py-3 lg:py-4 rounded-[1.25rem] lg:rounded-[1.75rem] text-xs lg:text-sm leading-relaxed" 
                            :class="msg.sender === 'user' 
                                ? 'bg-indigo-600 text-white rounded-tr-none shadow-md shadow-indigo-600/10 font-medium' 
                                : 'bg-white border border-slate-200 text-slate-800 rounded-tl-none shadow-sm'"
                        >
                            <div class="chat-bubble-content" x-html="renderMarkdown(msg.text)"></div>
                        </div>

                        <!-- Copy to Clipboard & Action Bar (AI responses only) -->
                        <template x-if="msg.sender === 'ai' && idx > 0">
                            <div class="flex items-center gap-3 mt-1.5 ml-3">
                                <button @click="copyToClipboard(msg.text, idx)" 
                                    class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-400 hover:text-indigo-655 transition-colors uppercase tracking-wider group"
                                >
                                    <svg class="w-3.5 h-3.5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"></path>
                                    </svg>
                                    <span x-text="copiedIndex === idx ? '{{ app()->getLocale() == 'en' ? 'Copied!' : 'Tersalin!' }}' : '{{ app()->getLocale() == 'en' ? 'Copy Text' : 'Salin Teks' }}'"></span>
                                </button>
                            </div>
                        </template>

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
                <div x-show="loading" class="flex items-start gap-3.5 transition-all duration-300" style="display: none;">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 relative animate-pulse ring-4 ring-indigo-500/15">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M12 17v4m-6-4v4m12-4v4M9 11h.008v.008H9V11zm6 0h.008v.008H15V11zm-9 4a6 6 0 0112 0H6z"></path>
                        </svg>
                    </div>
                    <div class="bg-white border border-slate-200 text-slate-400 rounded-3xl rounded-tl-none px-5 py-4 shadow-sm flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-2 h-2 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-2 h-2 bg-indigo-700 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Speech Wave Animation Indicator -->
            <div x-show="isListening" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="px-4 lg:px-8 py-2.5 lg:py-3 bg-rose-50 border-t border-rose-100 flex items-center justify-between shrink-0"
                style="display: none;"
            >
                <div class="flex items-center gap-2.5 text-rose-700 text-[10px] font-bold uppercase tracking-wider">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <span>{{ app()->getLocale() == 'en' ? 'Listening to voice...' : 'Mendengarkan suara...' }}</span>
                </div>
                <!-- Audio Wave Bars -->
                <div class="flex items-center gap-0.5 h-4">
                    <span class="w-0.5 bg-rose-500 rounded-full animate-audio-wave-1 h-3"></span>
                    <span class="w-0.5 bg-rose-500 rounded-full animate-audio-wave-2 h-4"></span>
                    <span class="w-0.5 bg-rose-500 rounded-full animate-audio-wave-3 h-2"></span>
                    <span class="w-0.5 bg-rose-500 rounded-full animate-audio-wave-4 h-3.5"></span>
                </div>
            </div>

            <!-- Input Area -->
            <form @submit.prevent="sendMessage()" class="p-3 sm:p-4 lg:p-6 bg-white border-t border-slate-200 flex items-center gap-2 lg:gap-4 shrink-0">
                <input x-model="input" 
                    type="text" 
                    placeholder="{{ app()->getLocale() == 'en' ? 'Ask financial analysis, overdue bills...' : 'Tanyakan analisis keuangan, tagihan overdue...' }}" 
                    class="flex-1 min-w-0 px-3.5 lg:px-5 py-3 lg:py-3.5 bg-slate-50 border border-slate-200 rounded-xl lg:rounded-2xl text-[11px] lg:text-xs outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all text-slate-800 font-medium" 
                    :disabled="loading"
                    autofocus>
                
                <!-- Mic Button -->
                <button type="button" 
                    @click="toggleSpeech()"
                    class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl lg:rounded-2xl flex items-center justify-center shrink-0 transition-all active:scale-95 border"
                    :class="isListening 
                        ? 'bg-rose-500 hover:bg-rose-600 text-white border-rose-600 animate-pulse shadow-md shadow-rose-500/20' 
                        : 'bg-slate-50 hover:bg-slate-100 text-slate-500 border-slate-200 hover:border-slate-300'"
                    :disabled="loading"
                    title="{{ app()->getLocale() == 'en' ? 'Voice input (Speech to Text)' : 'Input suara (Speech to Text)' }}">
                    <i data-lucide="mic" class="w-4.5 h-4.5 lg:w-5 lg:h-5"></i>
                </button>

                <button type="submit" 
                    class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl lg:rounded-2xl flex items-center justify-center shrink-0 transition-all active:scale-95 disabled:opacity-50 shadow-md shadow-indigo-600/10" 
                    :disabled="loading">
                    <i data-lucide="send" class="w-4.5 h-4.5 lg:w-5 lg:h-5"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Move Javascript to proper script tag using Alpine.data -->
    <script>
        const registerAiChat = () => {
            Alpine.data('aiChat', () => ({
                input: '',
                messages: [],
                loading: false,
                currentSessionId: null,
                sessions: @js($sessions),
                editingSessionId: null,
                editingTitle: '',
                copiedIndex: null,
                isListening: false,
                recognition: null,
                showDrawer: false,
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
                    'dashboard': '{{ app()->getLocale() == "en" ? "👉 Open Main Dashboard" : "👉 Buka Dashboard Utama" }}',
                    'invoices.index': '{{ app()->getLocale() == "en" ? "👉 View Invoices List" : "👉 Lihat Daftar Invoice" }}',
                    'invoices.create': '{{ app()->getLocale() == "en" ? "👉 Create New Invoice" : "👉 Buat Invoice Baru" }}',
                    'clients.index': '{{ app()->getLocale() == "en" ? "👉 View Clients List" : "👉 Lihat Daftar Klien" }}',
                    'clients.create': '{{ app()->getLocale() == "en" ? "👉 Add New Client" : "👉 Tambah Klien Baru" }}',
                    'receipts.index': '{{ app()->getLocale() == "en" ? "👉 View Receipts List" : "👉 Lihat Daftar Kuitansi" }}',
                    'receipts.create': '{{ app()->getLocale() == "en" ? "👉 Create New Receipt" : "👉 Buat Kuitansi Baru" }}',
                    'settings.index': '{{ app()->getLocale() == "en" ? "👉 Open Settings" : "👉 Buka Pengaturan" }}',
                    'profile.edit': '{{ app()->getLocale() == "en" ? "👉 Edit My Profile" : "👉 Edit Profil Saya" }}',
                    'reports.index': '{{ app()->getLocale() == "en" ? "👉 Open Financial Reports" : "👉 Buka Laporan Keuangan" }}',
                    'chronos.index': '{{ app()->getLocale() == "en" ? "👉 Open Billing Calendar (Chronos)" : "👉 Buka Kalender Billing (Chronos)" }}'
                },
                initChat() {
                    this.messages = [
                        { sender: 'ai', text: '{{ app()->getLocale() == "en" ? "Hello! I am your Virtual Senior Financial Consultant & Business Analyst. Is there anything I can help you with regarding cash flow analysis, overdue clients, financial forecasts, or system navigation today?" : "Halo! Saya Senior Financial Consultant & Business Analyst Virtual Anda. Ada yang bisa saya bantu terkait analisis arus kas, klien overdue, prediksi keuangan, atau navigasi sistem hari ini?" }}' }
                    ];
                    this.initSpeech();
                    
                    this.$nextTick(() => {
                        this.scrollToBottom();
                        // Focus on chat input
                        const inputEl = this.$el.querySelector('form input[type="text"]');
                        if (inputEl) {
                            inputEl.focus();
                        }
                        
                        // Initialize all lucide icons in the component
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons({
                                icons: lucide.icons,
                                root: this.$el
                            });
                        }
                    });
                },
                initSpeech() {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRecognition) {
                        console.warn('Speech Recognition API not supported in this browser.');
                        return;
                    }
                    this.recognition = new SpeechRecognition();
                    this.recognition.continuous = false;
                    this.recognition.interimResults = false;
                    this.recognition.lang = '{{ app()->getLocale() == "en" ? "en-US" : "id-ID" }}';

                    this.recognition.onstart = () => {
                        this.isListening = true;
                    };

                    this.recognition.onresult = (event) => {
                        const transcript = event.results[0][0].transcript;
                        if (transcript) {
                            if (this.input.trim()) {
                                this.input += ' ' + transcript;
                            } else {
                                this.input = transcript;
                            }
                        }
                    };

                    this.recognition.onerror = (event) => {
                        console.error('Speech recognition error:', event.error);
                        this.isListening = false;
                    };

                    this.recognition.onend = () => {
                        this.isListening = false;
                    };
                },
                toggleSpeech() {
                    if (!this.recognition) {
                        this.initSpeech();
                    }
                    if (!this.recognition) {
                        alert('Speech Recognition is not supported by your browser.');
                        return;
                    }
                    if (this.isListening) {
                        this.recognition.stop();
                    } else {
                        try {
                            this.recognition.start();
                        } catch (err) {
                            console.error(err);
                        }
                    }
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
                        { sender: 'ai', text: '{{ app()->getLocale() == "en" ? "Hello! I am your Virtual Senior Financial Consultant & Business Analyst. Is there anything I can help you with regarding cash flow analysis, overdue clients, financial forecasts, or system navigation today?" : "Halo! Saya Senior Financial Consultant & Business Analyst Virtual Anda. Ada yang bisa saya bantu terkait analisis arus kas, klien overdue, prediksi keuangan, atau navigasi sistem hari ini?" }}' }
                    ];
                    this.currentSessionId = null;
                    this.input = '';
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        if (typeof lucide !== 'undefined' && container) {
                            lucide.createIcons({
                                icons: lucide.icons,
                                root: container
                            });
                        }
                    });
                },
                loadSession(sessionId) {
                    if (this.editingSessionId) return;
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
                                    { sender: 'ai', text: '{{ app()->getLocale() == "en" ? "Hello! I am your Virtual Senior Financial Consultant & Business Analyst. Is there anything I can help you with regarding financial analysis today?" : "Halo! Saya Senior Financial Consultant & Business Analyst Virtual Anda. Ada yang bisa saya bantu terkait analisis keuangan hari ini?" }}' }
                                ];
                                this.$nextTick(() => {
                                    this.scrollToBottom();
                                });
                            }
                        })
                        .catch(err => {
                            this.loading = false;
                            this.messages.push({ sender: 'ai', text: '{{ app()->getLocale() == "en" ? "Sorry, failed to load chat history: " : "Maaf, gagal memuat riwayat obrolan: " }}' + err.message });
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
                            this.messages.push({ sender: 'ai', text: '{{ app()->getLocale() == "en" ? "Sorry, an error occurred while processing your request." : "Maaf, terjadi kesalahan saat memproses permintaan Anda." }}' });
                        }
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    })
                    .catch(err => {
                        this.loading = false;
                        this.messages.push({ sender: 'ai', text: '{{ app()->getLocale() == "en" ? "Sorry, failed to process. " : "Maaf, gagal memproses. " }}' + err.message });
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
                        msg.navigateLabel = this.routeLabels[routeName] || '{{ app()->getLocale() == "en" ? "👉 Open Page" : "👉 Buka Halaman" }}';
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
                                    if (typeof lucide !== 'undefined') {
                                        const hist = this.$refs.historyContainer;
                                        const drawerHist = this.$refs.drawerHistoryContainer;
                                        lucide.createIcons({
                                            icons: lucide.icons,
                                            root: hist || document
                                        });
                                        if (drawerHist) {
                                            lucide.createIcons({
                                                icons: lucide.icons,
                                                root: drawerHist
                                            });
                                        }
                                    }
                                });
                            }
                        })
                        .catch(err => console.error('Gagal me-refresh list session:', err));
                },
                scrollToBottom() {
                    const container = this.$refs.chatContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons({
                                icons: lucide.icons,
                                root: container
                            });
                        }
                    }
                },
                startRename(sess) {
                    this.editingSessionId = sess.session_id;
                    this.editingTitle = sess.title;
                    this.$nextTick(() => {
                        const inputs = document.querySelectorAll('[x-ref=renameInput], [x-ref=drawerRenameInput]');
                        inputs.forEach(el => {
                            if (el.offsetWidth > 0 || el.offsetHeight > 0) {
                                el.focus();
                            }
                        });
                    });
                },
                cancelRename() {
                    this.editingSessionId = null;
                    this.editingTitle = '';
                },
                saveRename(sessionId) {
                    if (!this.editingTitle.trim()) return;
                    const newTitle = this.editingTitle.trim();
                    
                    fetch('/ai-assistant/session/' + sessionId + '/rename', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ title: newTitle })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to rename');
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const index = this.sessions.findIndex(s => s.session_id === sessionId);
                            if (index !== -1) {
                                this.sessions[index].title = newTitle;
                            }
                            this.cancelRename();
                        }
                    })
                    .catch(err => {
                        alert('Error: ' + err.message);
                    });
                },
                deleteSession(sessionId) {
                    if (!confirm('{{ app()->getLocale() == "en" ? "Are you sure you want to delete this chat session?" : "Apakah Anda yakin ingin menghapus sesi obrolan ini?" }}')) {
                        return;
                    }
                    
                    fetch('/ai-assistant/session/' + sessionId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to delete');
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.sessions = this.sessions.filter(s => s.session_id !== sessionId);
                            if (this.currentSessionId === sessionId) {
                                this.newChat();
                            }
                        }
                    })
                    .catch(err => {
                        alert('Error: ' + err.message);
                    });
                },
                copyToClipboard(text, idx) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.copiedIndex = idx;
                        setTimeout(() => {
                            if (this.copiedIndex === idx) {
                                this.copiedIndex = null;
                            }
                        }, 2000);
                    });
                }
            }));
        };

        if (window.Alpine) {
            registerAiChat();
        } else {
            document.addEventListener('alpine:init', registerAiChat);
        }
    </script>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const chatContainer = document.querySelector('[x-ref="chatContainer"]');
            const chatInput = document.querySelector('form input[type="text"]');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
            if (chatInput) {
                chatInput.focus();
            }
            if (typeof lucide !== 'undefined') {
                const container = document.querySelector('[x-data="aiChat()"]');
                if (container) {
                    lucide.createIcons({
                        icons: lucide.icons,
                        root: container
                    });
                }
            }
        });
    </script>
    </div>
</x-app-layout>
