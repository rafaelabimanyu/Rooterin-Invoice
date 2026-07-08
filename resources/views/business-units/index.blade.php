<x-app-layout :title="__('ui.business_units_management')">
    <div class="animate-fade-in-up">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-jakarta">
                    <span>{{ app()->getLocale() == 'en' ? 'Administration' : 'Administrasi' }}</span>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <span class="text-gold-600">{{ __('ui.business_units') }}</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 font-outfit">{{ __('ui.business_units_management') }}</h1>
                <p class="text-sm text-slate-500 font-medium">{{ __('ui.business_units_desc') }}</p>
            </div>
            <div>
                <a href="{{ route('business-units.create') }}" class="btn-premium py-3.5 px-6 rounded-2xl font-bold text-xs uppercase tracking-wider inline-flex items-center gap-2 shadow-lg shadow-gold-500/20">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    {{ __('ui.add_business_unit') }}
                </a>
            </div>
        </div>

        <!-- Alert Notification -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 font-medium text-sm animate-fade-in">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('danger'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 font-medium text-sm animate-fade-in">
                <i data-lucide="alert-octagon" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <div>{{ session('danger') }}</div>
            </div>
        @endif

        <!-- Filter & Search Card -->
        <div class="glass-card border-slate-200/60 p-6 mb-8">
            <form action="{{ route('business-units.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search bar -->
                <div class="space-y-2 md:col-span-10">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-jakarta">{{ app()->getLocale() == 'en' ? 'Search Unit Name / Description' : 'Cari Nama Unit / Deskripsi' }}</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="{{ __('ui.search_business_units') }}"
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-all font-medium text-slate-900"
                        >
                        <div class="absolute left-3.5 top-3.5 text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex gap-2 md:col-span-2">
                    <button type="submit" class="flex-1 btn-premium py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-center">
                        Filter
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('business-units.index') }}" class="btn-secondary py-3 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Desktop Floating Table -->
        <div class="hidden md:block space-y-4">
            <!-- Headers -->
            <div class="grid grid-cols-12 gap-8 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2 font-jakarta">
                <div class="col-span-3">{{ __('ui.business_unit_name') }}</div>
                <div class="col-span-4">{{ __('ui.business_unit_desc') }}</div>
                <div class="col-span-2 text-center">{{ __('ui.invoice_count') }}</div>
                <div class="col-span-2 text-center">{{ __('ui.active_status') }}</div>
                <div class="col-span-1 text-right">{{ __('ui.actions') }}</div>
            </div>

            <!-- Rows -->
            @forelse($businessUnits as $bu)
                <div class="row-floating grid grid-cols-12 gap-8 items-center px-10 py-6 group">
                    <!-- NAME -->
                    <div class="col-span-3">
                        <span class="text-[15px] font-bold text-slate-900 font-outfit tracking-tight block">
                            {{ $bu->name }}
                        </span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5 block">
                            {{ $bu->slug }}
                        </span>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="col-span-4">
                        <p class="text-sm text-slate-600 font-medium truncate max-w-xs" title="{{ $bu->description }}">
                            {{ $bu->description ?: '-' }}
                        </p>
                    </div>

                    <!-- INVOICES COUNT -->
                    <div class="col-span-2 text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-black text-xs border border-slate-200/50 min-w-8">
                            {{ $bu->invoices_count }}
                        </span>
                    </div>

                    <!-- ACTIVE STATUS -->
                    <div class="col-span-2 flex justify-center">
                        @if($bu->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                {{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}
                            </span>
                        @endif
                    </div>

                    <!-- ACTIONS -->
                    <div class="col-span-1">
                        <div class="flex items-center justify-end gap-3 opacity-40 group-hover:opacity-100 transition-all duration-300">
                            <a href="{{ route('business-units.edit', $bu) }}" class="p-1.5 text-slate-400 hover:text-gold-600 transition-colors" title="{{ __('ui.edit') }}">
                                <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                            </a>

                            <form action="{{ route('business-units.destroy', $bu) }}" method="POST" onsubmit="return confirm('{{ __('ui.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors" title="{{ __('ui.delete') }}">
                                    <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-600 mx-auto mb-6">
                        <i data-lucide="layers" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 font-outfit mb-1">{{ __('ui.no_business_units_title') }}</h3>
                    <p class="text-slate-500 text-sm font-medium mb-6 max-w-sm mx-auto">{{ __('ui.no_business_units_desc') }}</p>
                    <a href="{{ route('business-units.create') }}" class="btn-premium py-3 px-6 rounded-xl font-bold text-xs uppercase tracking-wider inline-flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        {{ __('ui.add_business_unit') }}
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Mobile List View -->
        <div class="md:hidden space-y-4">
            @forelse($businessUnits as $bu)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-4 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-base font-bold text-slate-900 font-outfit block">{{ $bu->name }}</span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">{{ $bu->slug }}</span>
                        </div>
                        <div>
                            @if($bu->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    {{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                    {{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($bu->description)
                        <p class="text-xs text-slate-500 font-medium">{{ $bu->description }}</p>
                    @endif

                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            {{ __('ui.invoice_count') }}: <span class="text-slate-800 font-black ml-1">{{ $bu->invoices_count }}</span>
                        </span>
                        
                        <div class="flex items-center gap-3">
                            <a href="{{ route('business-units.edit', $bu) }}" class="p-2 bg-slate-50 hover:bg-gold-50 text-slate-400 hover:text-gold-600 rounded-xl transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('business-units.destroy', $bu) }}" method="POST" onsubmit="return confirm('{{ __('ui.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-500 rounded-xl transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 text-center shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-600 mx-auto mb-4">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 font-outfit mb-1">{{ __('ui.no_business_units_title') }}</h3>
                    <p class="text-slate-500 text-xs font-medium mb-4">{{ __('ui.no_business_units_desc') }}</p>
                    <a href="{{ route('business-units.create') }}" class="btn-premium py-2.5 px-5 rounded-xl font-bold text-xs uppercase tracking-wider inline-flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        {{ __('ui.add_business_unit') }}
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $businessUnits->links() }}
        </div>
    </div>
</x-app-layout>
