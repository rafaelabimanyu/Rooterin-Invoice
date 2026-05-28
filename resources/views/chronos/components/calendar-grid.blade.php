<!-- Main Calendar Card -->
<div class="glass-card p-6 md:p-8 shadow-2xl shadow-indigo-500/10 border-slate-100 bg-white/80 backdrop-blur-md rounded-3xl flex flex-col w-full min-w-0"
     x-data="chronosCalendar()"
     @reminder-saved.window="refetch()"
     @refresh-calendar.window="refetch()"
>
    <!-- Calendar Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full">
            <div class="flex items-center justify-between sm:justify-start gap-3 w-full sm:w-auto">
                <button @click="goToPrevMonth()" class="w-12 h-12 shrink-0 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition-all border border-slate-200/60 active:scale-95" title="Previous Month">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>
                    </svg>
                </button>
                <h2 id="calendar-title" class="text-xs sm:text-md font-black text-slate-850 font-jakarta uppercase tracking-wider flex-1 sm:flex-none text-center select-none truncate px-2">
                    ...
                </h2>
                <button @click="goToNextMonth()" class="w-12 h-12 shrink-0 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition-all border border-slate-200/60 active:scale-95" title="Next Month">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto sm:ml-auto">
                <button @click="goToToday()" 
                    class="w-full sm:w-auto flex items-center justify-center px-5 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-extrabold text-xs rounded-2xl transition-all duration-200 active:scale-95"
                >
                    {{ __('Today') }}
                </button>
                <button @click="openCreateModalForToday()" 
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 font-extrabold text-xs border border-slate-200/80 rounded-2xl shadow-sm hover:shadow transition-all duration-200 active:scale-95"
                >
                    <svg class="w-4 h-4 text-indigo-650" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    <span>{{ __('+ Add Event / Reminder') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- FullCalendar Render Target with Micro Loading Indicator -->
    <div class="relative w-full min-w-0">
        <!-- Syncing Micro-Loader overlay -->
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-white/65 backdrop-blur-[2px] z-20 flex items-center justify-center rounded-3xl transition-all"
             style="display: none;"
        >
            <div class="flex items-center gap-3 px-5 py-3.5 bg-white shadow-xl rounded-2xl border border-slate-100/80">
                <div class="w-5 h-5 border-3 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    {{ __('Syncing Calendar...') }}
                </span>
            </div>
        </div>
        
        <div wire:ignore class="w-full min-w-0" id="fullcalendar-target"></div>
    </div>
</div>

<!-- Self-Contained Assets (FullCalendar Library & Stylesheet) safe for wire:navigate SPA -->
<div wire:ignore>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
    <style>
        .fc {
            font-family: 'Outfit', 'Inter', sans-serif;
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #f1f5f9 !important;
        }
        .fc-theme-standard .fc-scrollgrid {
            border-color: #e2e8f0 !important;
            border-radius: 20px;
            overflow: hidden;
        }
        .fc .fc-col-header-cell {
            background-color: #f8fafc;
            padding: 12px 0 !important;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b !important;
        }
        .fc .fc-daygrid-day-top {
            flex-direction: row;
            justify-content: space-between;
            padding: 8px 10px 0 10px;
        }
        .fc-daygrid-day-number {
            font-size: 12px;
            font-weight: 800;
            color: #334155 !important;
            padding: 4px 8px !important;
            border-radius: 8px;
        }
        .fc-daygrid-day:hover {
            background-color: rgba(248, 250, 252, 0.5) !important;
        }
        .fc-daygrid-day {
            position: relative;
        }
        
        /* Premium Input Field Styling with rounder shapes & focus rings */
        .premium-input {
            background-color: rgba(255, 255, 255, 0.85) !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 16px !important;
            padding: 11px 15px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #334155 !important;
            outline: none !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .premium-input:focus {
            background-color: #ffffff !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15), 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
            transform: translateY(-1px);
        }
        
        /* Responsive Grid Heights for Desktop */
        @media (min-width: 768px) {
            .fc .fc-daygrid-day-frame {
                min-height: 120px !important;
            }
        }
        
        /* Desktop interactive add button overlay on hover */
        .fc-daygrid-day::after {
            content: '+';
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            line-height: 22px;
            text-align: center;
            background-color: #4f46e5;
            color: white;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 800;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        @media (min-width: 768px) {
            .fc-daygrid-day:hover::after {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Premium Popover for "+ more" events */
        .fc-popover {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid #f1f5f9 !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            overflow: hidden;
            z-index: 80 !important;
        }
        .fc-popover-header {
            background-color: #f8fafc !important;
            padding: 10px 14px !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .fc-popover-body {
            padding: 10px !important;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        /* Premium FullCalendar List View styling for Mobile */
        .fc-list {
            border: none !important;
            background: transparent !important;
        }
        .fc-list-day {
            background-color: #f8fafc !important;
        }
        .fc-list-day-cushion {
            padding: 12px 16px !important;
            background-color: #f8fafc !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b !important;
        }
        .fc-list-event {
            background-color: white !important;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .fc-list-event:hover {
            background-color: #faf5ff !important;
            transform: translateX(4px);
        }
        .fc-list-event td {
            padding: 12px 16px !important;
            border: none !important;
        }
        .fc-list-event-dot {
            border-width: 4px !important;
            width: 8px !important;
            height: 8px !important;
            border-radius: 9999px !important;
        }
        .fc-list-event-title a {
            font-size: 12px !important;
            font-weight: 850 !important;
            color: #1e293b !important;
            text-decoration: none !important;
        }
        .fc-list-empty {
            background-color: white !important;
            padding: 40px 20px !important;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            border-radius: 20px;
        }
        
        .fc-day-today {
            background-color: rgba(79, 70, 229, 0.03) !important;
            border: 2px solid rgba(79, 70, 229, 0.4) !important;
            box-shadow: inset 0 0 12px rgba(79, 70, 229, 0.03), 4px 4px 20px rgba(79, 70, 229, 0.06) !important;
            position: relative;
        }
        .fc-day-today .fc-daygrid-day-number {
            color: #4f46e5 !important;
            background-color: #f5f3ff;
            border: 1px solid #ddd6fe;
        }
        .fc-event {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
        #calendar-tooltip {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            transition: opacity 0.15s ease-out, transform 0.15s ease-out;
        }
        .fc-header-toolbar {
            display: none !important;
        }
        .fc-daygrid-event-harness {
            margin: 2px 4px !important;
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js" data-navigate-once></script>
    <script>
        function chronosCalendar() {
            return {
                calendar: null,
                loading: false,
                init() {
                    // Safe delayed initialization to guarantee target is ready in DOM
                    setTimeout(() => {
                        this.initCalendar();
                    }, 50);
                    
                    // Screen resize trigger to switch between DayGrid (Desktop) and List (Mobile) view dynamically
                    window.addEventListener('resize', () => {
                        if (!this.calendar) return;
                        const isMobile = window.innerWidth < 768;
                        const targetView = isMobile ? 'listMonth' : 'dayGridMonth';
                        if (this.calendar.view.type !== targetView) {
                            this.calendar.changeView(targetView);
                        }
                    });
                    
                    this.$watch(() => this.$wire.clientId, () => this.refetch());
                    this.$watch(() => this.$wire.status, () => this.refetch());
                    this.$watch(() => this.$wire.staffId, () => this.refetch());
                },
                initCalendar() {
                    const calendarEl = document.getElementById('fullcalendar-target');
                    if (!calendarEl) return;
                    
                    // Fallback resilience mechanism in case network load for FullCalendar CDN takes longer
                    if (typeof FullCalendar === 'undefined') {
                        setTimeout(() => this.initCalendar(), 100);
                        return;
                    }
                    
                    const isMobile = window.innerWidth < 768;
                    const initialView = isMobile ? 'listMonth' : 'dayGridMonth';
                    
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: initialView,
                        locale: '{{ app()->getLocale() }}',
                        firstDay: 1,
                        editable: true,
                        droppable: true,
                        selectable: false,
                        dayMaxEvents: 3,
                        events: (info, successCallback, failureCallback) => {
                            this.loading = true;
                            let url = '{{ route("chronos.events") }}';
                            let params = new URLSearchParams({
                                start: info.startStr,
                                end: info.endStr,
                                client_id: this.$wire.clientId || '',
                                status: this.$wire.status || '',
                                staff_id: this.$wire.staffId || ''
                            });
                            
                            axios.get(url + '?' + params.toString())
                                .then(response => successCallback(response.data))
                                .catch(error => {
                                    console.error(error);
                                    if (typeof window.showToast === 'function') {
                                        window.showToast('Failed to fetch events', 'danger');
                                    }
                                    failureCallback(error);
                                })
                                .finally(() => {
                                    this.loading = false;
                                });
                        },
                        datesSet: (info) => {
                            const titleEl = document.getElementById('calendar-title');
                            if (titleEl) titleEl.innerText = info.view.title;
                        },
                        dateClick: (info) => {
                            this.$wire.openAddModal(info.dateStr);
                        },
                        eventClick: (info) => {
                            let type = info.event.extendedProps.type;
                            let dbId = info.event.extendedProps.dbId;
                            if (type === 'invoice') {
                                this.$wire.viewInvoiceDetails(dbId);
                            } else if (type === 'reminder') {
                                this.$wire.openEditModal(dbId);
                            }
                        },
                        eventDrop: (info) => {
                            this.updateEventDate(info.event, info.oldEvent, info.revert);
                        },
                        eventResize: (info) => {
                            this.updateEventDate(info.event, info.oldEvent, info.revert);
                        },
                        eventMouseEnter: (info) => {
                            this.showTooltip(info);
                        },
                        eventMouseLeave: (info) => {
                            this.hideTooltip();
                        },
                        eventContent: (arg) => {
                            let type = arg.event.extendedProps.type;
                            let status = arg.event.extendedProps.status || arg.event.extendedProps.status_type;
                            let title = arg.event.title;
                            
                            let colorClass = 'bg-slate-50 text-slate-700 border-slate-200/80';
                            let bulletColor = 'bg-slate-400';
                            
                            if (type === 'invoice') {
                                if (status === 'paid') {
                                    colorClass = 'bg-emerald-50 text-emerald-700 border-emerald-100/80';
                                    bulletColor = 'bg-emerald-500';
                                } else if (status === 'overdue') {
                                    colorClass = 'bg-rose-50 text-rose-700 border-rose-100/80';
                                    bulletColor = 'bg-rose-500';
                                } else if (status === 'draft') {
                                    colorClass = 'bg-amber-50 text-amber-700 border-amber-100/80';
                                    bulletColor = 'bg-amber-500';
                                } else if (status === 'sent') {
                                    colorClass = 'bg-blue-50 text-blue-700 border-blue-100/80';
                                    bulletColor = 'bg-blue-500';
                                }
                            } else {
                                if (status === 'internal') {
                                    colorClass = 'bg-indigo-50 text-indigo-700 border-indigo-100/80';
                                    bulletColor = 'bg-indigo-500';
                                } else if (status === 'meeting') {
                                    colorClass = 'bg-emerald-50 text-emerald-700 border-emerald-100/80';
                                    bulletColor = 'bg-emerald-500';
                                } else if (status === 'draft') {
                                    colorClass = 'bg-amber-50 text-amber-700 border-amber-100/80';
                                    bulletColor = 'bg-amber-500';
                                } else if (status === 'overdue') {
                                    colorClass = 'bg-rose-50 text-rose-700 border-rose-100/80';
                                    bulletColor = 'bg-rose-500';
                                }
                            }

                            return {
                                html: `
                                    <div class="w-full text-left truncate text-[10px] font-extrabold px-2 py-1 rounded-lg border flex items-center gap-1.5 transition-all hover:scale-[1.02] active:scale-98 ${colorClass}">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0 ${bulletColor}"></span>
                                        <span class="truncate flex-1 tracking-tight">${title}</span>
                                    </div>
                                `
                            };
                        }
                    });
                    this.calendar.render();
                },
                refetch() {
                    if (this.calendar) this.calendar.refetchEvents();
                },
                goToPrevMonth() {
                    if (this.calendar) this.calendar.prev();
                },
                goToNextMonth() {
                    if (this.calendar) this.calendar.next();
                },
                goToToday() {
                    if (this.calendar) this.calendar.today();
                },
                openCreateModalForToday() {
                    let todayStr = new Date().toISOString().split('T')[0];
                    this.$wire.openAddModal(todayStr);
                },
                updateEventDate(event, oldEvent, revertFunc) {
                    let id = event.id;
                    let start = event.startStr;
                    let end = event.endStr;
                    
                    axios.post('{{ route("chronos.update-event") }}', {
                        id: id,
                        start: start,
                        end: end
                    })
                    .then(response => {
                        if (response.data.success) {
                            if (typeof window.showToast === 'function') {
                                window.showToast(response.data.message || 'Date updated successfully!', 'success');
                            }
                        } else {
                            if (typeof window.showToast === 'function') {
                                window.showToast(response.data.error || 'Failed to update date.', 'danger');
                            }
                            revertFunc();
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        let errorMsg = 'Failed to update date.';
                        if (error.response && error.response.data && error.response.data.error) {
                            errorMsg = error.response.data.error;
                        }
                        if (typeof window.showToast === 'function') {
                            window.showToast(errorMsg, 'danger');
                        }
                        revertFunc();
                    });
                },
                showTooltip(info) {
                    const tooltip = document.getElementById('calendar-tooltip');
                    if (!tooltip) return;
                    const props = info.event.extendedProps;
                    const type = props.type;
                    
                    const titleEl = document.getElementById('tooltip-title');
                    const badgeEl = document.getElementById('tooltip-badge');
                    const descEl = document.getElementById('tooltip-desc');
                    const clientEl = document.getElementById('tooltip-client');
                    const staffEl = document.getElementById('tooltip-staff');
                    const dateEl = document.getElementById('tooltip-date');
                    
                    if (titleEl) titleEl.innerText = info.event.title;
                    
                    if (type === 'invoice') {
                        if (badgeEl) {
                            badgeEl.innerText = 'Invoice: ' + props.status;
                            badgeEl.className = `px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider ` + 
                                (props.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100/30' : 
                                 (props.status === 'overdue' ? 'bg-rose-50 text-rose-700 border border-rose-100/30' : 'bg-amber-50 text-amber-700 border border-amber-100/30'));
                        }
                        if (descEl) descEl.innerText = 'Invoice: ' + props.invoice_number + ' | Amount: ' + props.total;
                    } else {
                        if (badgeEl) {
                            badgeEl.innerText = 'Reminder: ' + props.status_type;
                            badgeEl.className = `px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100/30`;
                        }
                        if (descEl) descEl.innerText = props.description || 'No description provided';
                    }
                    
                    if (clientEl) clientEl.innerText = props.client || 'N/A';
                    if (staffEl) staffEl.innerText = props.responsible_staff || 'N/A';
                    
                    let dateStr = info.event.start.toLocaleDateString('{{ app()->getLocale() }}', { day: 'numeric', month: 'short' });
                    if (info.event.end) {
                        let dispEnd = new Date(info.event.end);
                        dispEnd.setDate(dispEnd.getDate() - 1);
                        if (dispEnd > info.event.start) {
                            dateStr += ' - ' + dispEnd.toLocaleDateString('{{ app()->getLocale() }}', { day: 'numeric', month: 'short' });
                        }
                    }
                    if (dateEl) dateEl.innerText = dateStr;
                    
                    const rect = info.el.getBoundingClientRect();
                    const tooltipWidth = tooltip.offsetWidth || 288;
                    const tooltipHeight = tooltip.offsetHeight || 150;
                    
                    let top = window.scrollY + rect.top - tooltipHeight - 10;
                    let left = window.scrollX + rect.left + (rect.width / 2) - (tooltipWidth / 2);
                    
                    if (top < window.scrollY) {
                        top = window.scrollY + rect.bottom + 10;
                    }
                    if (left < 10) {
                        left = 10;
                    } else if (left + tooltipWidth > window.innerWidth - 10) {
                        left = window.innerWidth - tooltipWidth - 10;
                    }
                    
                    tooltip.style.top = top + 'px';
                    tooltip.style.left = left + 'px';
                    tooltip.classList.remove('hidden');
                },
                hideTooltip() {
                    const tooltip = document.getElementById('calendar-tooltip');
                    if (tooltip) tooltip.classList.add('hidden');
                }
            };
        }
    </script>
</div>
