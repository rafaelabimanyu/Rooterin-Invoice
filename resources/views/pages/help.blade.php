<x-app-layout :title="app()->getLocale() == 'en' ? 'Help Center' : 'Pusat Bantuan'">
    <div class="page-fade-in py-12">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card overflow-hidden">
                <div class="px-8 py-10 bg-slate-50/50 border-b border-slate-100">
                    <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Help Center</h1>
                    <p class="text-slate-500 font-medium mt-1">Operational Support & Intelligence</p>
                </div>
                <div class="p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                            <h3 class="text-lg font-bold text-slate-800 mb-2 uppercase tracking-tight">Quick Start Guide</h3>
                            <p class="text-sm text-slate-600 mb-4">Learn the basics of creating invoices and managing clients in under 5 minutes.</p>
                            <a href="#" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">Read More &rarr;</a>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                            <h3 class="text-lg font-bold text-slate-800 mb-2 uppercase tracking-tight">Technical Support</h3>
                            <p class="text-sm text-slate-600 mb-4">Having issues with the system? Our technical team is ready to assist you 24/7.</p>
                            <a href="mailto:support@rooterin.com" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">Contact Support &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
