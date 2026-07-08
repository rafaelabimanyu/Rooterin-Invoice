<x-app-layout :title="__('ui.add_business_unit')">
    <div class="mb-8">
        <a href="{{ route('business-units.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-gold-600 transition-colors mb-4 font-jakarta font-bold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            {{ app()->getLocale() == 'en' ? 'Back to Business Units' : 'Kembali ke Unit Bisnis' }}
        </a>
        <h1 class="text-3xl font-bold text-slate-900 font-outfit">{{ __('ui.add_business_unit') }}</h1>
        <p class="text-slate-500 font-medium text-sm">{{ app()->getLocale() == 'en' ? 'Create a new business unit division in the system' : 'Buat divisi unit bisnis baru di dalam sistem' }}</p>
    </div>

    <!-- Error Validation Feedback -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl space-y-1 font-medium text-sm animate-fade-in">
            <div class="flex items-center gap-2 text-rose-900 font-bold mb-1">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <span>{{ app()->getLocale() == 'en' ? 'Whoops! Please correct the errors below.' : 'Oops! Mohon perbaiki kesalahan berikut.' }}</span>
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('business-units.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2 font-outfit">
                        <i data-lucide="layers" class="w-4 h-4 text-gold-600"></i>
                        {{ __('ui.business_unit_info') }}
                    </h3>

                    <!-- Unit Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('ui.business_unit_name') }} <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}" 
                            required 
                            placeholder="e.g. Jaya-Design" 
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all outline-none text-slate-900 font-bold text-sm"
                        >
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('ui.business_unit_desc') }}</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4" 
                            placeholder="{{ app()->getLocale() == 'en' ? 'Describe the scope of this division...' : 'Jelaskan cakupan dari divisi ini...' }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all outline-none text-slate-900 font-medium text-sm"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Settings Sidebar -->
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2 font-outfit">
                        <i data-lucide="settings" class="w-4 h-4 text-gold-600"></i>
                        {{ app()->getLocale() == 'en' ? 'Status Configuration' : 'Konfigurasi Status' }}
                    </h3>

                    <!-- Status Toggle -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">{{ __('ui.active_status') }}</span>
                            <span class="text-[9px] text-slate-400 font-bold mt-0.5 leading-tight">{{ app()->getLocale() == 'en' ? 'Visible in dropdown selectors' : 'Terlihat pada dropdown pilihan' }}</span>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Block -->
                <div class="p-6 bg-gold-50 rounded-3xl border border-gold-100">
                    <p class="text-[11px] text-gold-700 font-black mb-4 font-jakarta uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Save business unit details to activate it.' : 'Simpan detail unit bisnis untuk mengaktifkannya.' }}</p>
                    <button type="submit" class="w-full py-4 bg-gold-500 hover:bg-gold-600 text-slate-950 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-gold-500/25">
                        {{ app()->getLocale() == 'en' ? 'Save Business Unit' : 'Simpan Unit Bisnis' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
