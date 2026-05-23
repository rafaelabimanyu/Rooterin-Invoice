<x-app-layout :title="app()->getLocale() == 'en' ? 'Terms of Service' : 'Syarat & Ketentuan Layanan'">
    <div class="page-fade-in py-12">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card overflow-hidden">
                <div class="px-8 py-10 bg-slate-50/50 border-b border-slate-100">
                    <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Terms of Service</h1>
                    <p class="text-slate-500 font-medium mt-1">Effective Date: May 2026</p>
                </div>
                <div class="p-8 md:p-12 prose prose-slate max-w-none">
                    <p class="text-slate-600 leading-relaxed mb-6">
                        By accessing Rooterin Invoice, you agree to comply with these Terms of Service. Please read them carefully.
                    </p>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 uppercase tracking-wide">1. Service Usage</h3>
                    <p class="text-slate-600 mb-6">
                        Users must provide accurate information and maintain the security of their accounts. Any unauthorized use of the system is strictly prohibited.
                    </p>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 uppercase tracking-wide">2. Intellectual Property</h3>
                    <p class="text-slate-600">
                        All software, designs, and content within the Rooterin Invoice platform are the exclusive property of Rooterin System Operational.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
