<x-app-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
        <div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight mb-2 uppercase">
                Rooterin Chronos
            </h1>
            <p class="text-sm text-slate-500 font-medium tracking-tight">Billing Calendar & Operational Workflows</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-6 px-6 py-3 bg-white/50 backdrop-blur-md rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase text-slate-400">Paid</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase text-slate-400">Draft</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></span>
                    <span class="text-[10px] font-black uppercase text-slate-400">Overdue</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 xl:gap-8 w-full min-w-0">
        <!-- Left Section (75% Width): Main Calendar -->
        <div class="xl:col-span-3 flex flex-col min-w-0 w-full space-y-6">
            @livewire('chronos-calendar')

            <div class="glass-card p-4 md:p-8 shadow-2xl shadow-indigo-500/10 border-slate-100 page-fade-in stagger-1 h-[calc(100vh-200px)] flex flex-col w-full min-w-0">
                <div id="calendar" class="flex-1 w-full h-full"></div>
            </div>
        </div>

        <!-- Right Section (25% Width): Analytics Insights -->
        <div class="xl:col-span-1 flex flex-col gap-6 xl:gap-8 min-w-0 w-full">
            <!-- Metrics Card -->
            <div class="glass-card p-6 border-slate-100 shadow-2xl shadow-rose-500/5 page-fade-in stagger-2">
                <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md mb-6">Metrics Insights</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tunggakan Aktif</p>
                        <h4 class="text-2xl font-black text-rose-500 font-jakarta tracking-tighter">Rp {{ number_format($activeArrears, 0, ',', '.') }}</h4>
                    </div>
                    
                    <div class="pt-6 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jatuh Tempo Minggu Ini</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-2xl font-black text-slate-900 font-jakarta tracking-tighter">{{ $dueThisWeek }} <span class="text-sm text-slate-400 font-medium tracking-normal">Invoices</span></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Feed Card -->
            <div class="glass-card p-6 border-slate-100 shadow-2xl shadow-indigo-500/5 page-fade-in stagger-3 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-md">Live Feed</h3>
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>

                <div class="flex-1 relative">
                    <div class="absolute left-[11px] top-2 bottom-0 w-0.5 bg-slate-100"></div>
                    <div class="space-y-6">
                        @forelse($activities as $activity)
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-indigo-500 flex items-center justify-center z-10"></div>
                                <div class="space-y-1">
                                    <p class="text-[12px] font-bold text-slate-800 leading-snug">{{ $activity->description }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $activity->created_at->diffForHumans() }} - {{ $activity->user->name }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="activity" class="w-6 h-6 text-slate-300"></i>
                                </div>
                                <p class="text-[11px] font-bold text-slate-900 uppercase tracking-widest">No activities yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="invoiceModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all page-fade-in">
            <div id="modalContent" class="p-8">
                <!-- Content injected via JS -->
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                <button onclick="closeModal()" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">Close</button>
                <a id="viewInvoiceBtn" href="#" class="btn-premium">View Full Invoice</a>
            </div>
        </div>
    </div>

    @push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
    <style>
        .fc-theme-standard .fc-scrollgrid { border: none !important; }
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #f1f5f9 !important; }
        .fc .fc-toolbar-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 900; text-transform: uppercase; letter-spacing: -0.025em; color: #0f172a; }
        .fc .fc-button-primary { background-color: #4f46e5 !important; border: none !important; border-radius: 12px !important; font-weight: 700; padding: 10px 20px !important; box-shadow: 0 4px 12px rgba(79,70,229,0.2) !important; }
        .fc .fc-button-primary:hover { background-color: #4338ca !important; transform: translateY(-1px); box-shadow: 0 6px 15px rgba(79,70,229,0.3) !important; }
        .fc .fc-daygrid-day-number { font-weight: 800; font-size: 13px; color: #64748b; padding: 10px !important; }
        .fc .fc-day-today { background-color: #f8fafc !important; }
        .fc-event { border-radius: 8px !important; border: none !important; padding: 4px 8px !important; font-weight: 700 !important; font-size: 11px !important; transition: all 0.2s ease; cursor: pointer !important; }
        .fc-event:hover { transform: scale(1.02); filter: brightness(1.1); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1); }
        .fc-event:hover { transform: scale(1.02); filter: brightness(1.1); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1); }
        .toast-enter { animation: toastSlideIn 0.3s ease-out forwards; }
        @keyframes toastSlideIn { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
    @endpush

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: '100%',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                editable: {{ auth()->user()->hasAnyRole(['owner', 'admin']) ? 'true' : 'false' }},
                eventDisplay: 'block',
                events: {
                    url: '{{ route("chronos.events") }}',
                    extraParams: function() {
                        return {
                            clientId: window.chronosFilters?.clientId || '',
                            status: window.chronosFilters?.status || '',
                            staffId: window.chronosFilters?.staffId || ''
                        };
                    }
                },
                eventClick: function(info) {
                    showModal(info.event);
                },
                eventDrop: function(info) {
                    updateInvoiceDate(info.event);
                }
            });
            calendar.render();

            window.chronosFilters = {
                clientId: '{{ $clientId ?? "" }}',
                status: '{{ $status ?? "" }}',
                staffId: '{{ $staffId ?? "" }}'
            };

            window.addEventListener('filtersUpdated', event => {
                // Livewire 3/4 event detail structure
                const data = event.detail[0] || event.detail;
                window.chronosFilters = data;
                calendar.refetchEvents();
            });

            function showModal(event) {
                const props = event.extendedProps;
                const modal = document.getElementById('invoiceModal');
                const content = document.getElementById('modalContent');
                const btn = document.getElementById('viewInvoiceBtn');

                content.innerHTML = `
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white" style="background-color: ${event.backgroundColor}">
                            <i data-lucide="file-text" class="w-8 h-8"></i>
                        </div>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600">
                            ${props.status}
                        </span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">${event.title}</h3>
                    <p class="text-sm text-slate-500 font-medium mb-8">Due on ${new Date(event.start).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
                    
                    <div class="grid grid-cols-2 gap-8 py-8 border-y border-slate-100">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Amount</p>
                            <p class="text-xl font-black text-slate-900">${props.total}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Client Entity</p>
                            <p class="text-md font-bold text-slate-900">${props.client}</p>
                        </div>
                    </div>
                `;
                
                // Construct URL correctly for view invoice
                btn.href = `/invoices/${event.id}`;
                modal.classList.remove('hidden');
                lucide.createIcons();
            }

            window.closeModal = function() {
                document.getElementById('invoiceModal').classList.add('hidden');
            }

            function updateInvoiceDate(event) {
                fetch(`/chronos/update-date/${event.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        due_date: event.startStr.split('T')[0]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Invoice due date updated successfully!', 'success');
                    } else {
                        showToast('Failed to update due date. Unauthorized.', 'error');
                        event.revert();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Network error occurred.', 'error');
                    event.revert();
                });
            }

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-xl text-white font-bold text-sm shadow-xl z-[200] toast-enter ${type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'}`;
                toast.innerHTML = `<div class="flex items-center gap-3">
                    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
                    ${message}
                </div>`;
                document.body.appendChild(toast);
                lucide.createIcons();
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(100%)';
                    toast.style.transition = 'all 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        });
    </script>
    @endpush
</x-app-layout>
