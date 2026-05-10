<x-app-layout>
    <div class="flex flex-col lg:flex-row gap-10 items-start">
        <!-- Sidebar Navigation (Sticky) -->
        <aside class="w-full lg:w-64 sticky top-24 lg:h-[calc(100vh-120px)] overflow-y-auto hidden lg:block">
            <nav class="space-y-1">
                <p class="px-3 mb-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Documentation</p>
                <a href="#getting-started" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Getting Started
                </a>
                <a href="#dashboard" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Dashboard Overview
                </a>
                <a href="#clients" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Client Management
                </a>
                <a href="#invoices" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Invoicing System
                </a>
                <a href="#quotations" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Quotations
                </a>
                <a href="#payments" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Payments & Collections
                </a>
                <a href="#reports" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    Financial Reports
                </a>
                <a href="#settings" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                    System Settings
                </a>
                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="#faq" class="group flex items-center px-4 py-2 text-sm font-bold text-slate-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-100">
                        Frequently Asked Questions
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 max-w-4xl space-y-20 pb-20">
            <!-- Header -->
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-full text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                    <i data-lucide="book-open" class="w-3 h-3"></i> Knowledge Base
                </div>
                <h1 class="text-5xl font-black text-slate-900 font-outfit tracking-tight">Rooterin Guide.</h1>
                <p class="text-lg text-slate-500 leading-relaxed">Comprehensive manual for mastering the Rooterin Enterprise Billing ecosystem.</p>
            </div>

            <!-- Quick Start Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="glass-card p-6 text-center border-t-4 border-t-indigo-500">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Step 1</div>
                    <p class="text-sm font-bold text-slate-900">Add Client</p>
                </div>
                <div class="glass-card p-6 text-center border-t-4 border-t-indigo-500">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Step 2</div>
                    <p class="text-sm font-bold text-slate-900">Create Invoice</p>
                </div>
                <div class="glass-card p-6 text-center border-t-4 border-t-indigo-500">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Step 3</div>
                    <p class="text-sm font-bold text-slate-900">Send PDF</p>
                </div>
                <div class="glass-card p-6 text-center border-t-4 border-t-indigo-500">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Step 4</div>
                    <p class="text-sm font-bold text-slate-900">Get Paid</p>
                </div>
            </div>

            <!-- Getting Started -->
            <section id="getting-started" class="scroll-mt-32 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Getting Started</h2>
                </div>
                <div class="prose prose-slate max-w-none space-y-6">
                    <p class="text-slate-500 leading-relaxed">Rooterin-Invoice is designed to streamline your business financial cycle. To get the best results, follow this initial setup workflow:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                        <div class="glass-card p-8 space-y-4">
                            <h4 class="text-lg font-bold text-slate-900">1. Login & Profile</h4>
                            <p class="text-xs text-slate-500">Access your workspace using administrator credentials and ensure your profile information is correct.</p>
                        </div>
                        <div class="glass-card p-8 space-y-4">
                            <h4 class="text-lg font-bold text-slate-900">2. Register Clients</h4>
                            <p class="text-xs text-slate-500">Navigate to the Clients module to add your first customer. This is essential for issuing invoices.</p>
                        </div>
                    </div>
                    <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100 flex gap-4">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-amber-600 shrink-0"></i>
                        <p class="text-sm text-amber-900"><strong>Pro Tip:</strong> Use the "Inline Client Creation" feature inside the Create Invoice page to add clients on-the-fly!</p>
                    </div>
                </div>
            </section>

            <!-- Dashboard Overview -->
            <section id="dashboard" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="layout-grid" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Dashboard Overview</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">The dashboard provides real-time intelligence on your business performance. Key metrics include:</p>
                    <ul class="space-y-4 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-slate-900 font-bold text-[10px]">01</span>
                            <div><strong>Total Billing:</strong> The cumulative value of all invoices issued, representing your gross potential revenue.</div>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-slate-900 font-bold text-[10px]">02</span>
                            <div><strong>Amount Due:</strong> Total balance from invoices that are either pending or partially paid.</div>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Clients -->
            <section id="clients" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Client Management</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Maintain a professional ledger of your customers. Each client profile tracks:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <h5 class="font-bold text-slate-900 mb-2">Detailed History</h5>
                            <p class="text-xs text-slate-500">View every invoice and payment associated with a specific client.</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <h5 class="font-bold text-slate-900 mb-2">Professional Profiles</h5>
                            <p class="text-xs text-slate-500">Store NPWP, company names, and primary contact details for accurate billing.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Invoices -->
            <section id="invoices" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Invoicing System</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Issuing invoices is the core of Rooterin. The system handles complex calculations automatically.</p>
                    <div class="glass-card overflow-hidden">
                        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Feature Highlight: Auto-Calc</span>
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                        </div>
                        <div class="p-8 space-y-4">
                            <p class="text-sm text-slate-600">Simply add items, quantities, and rates. Rooterin calculates subtotal, tax (PPN), and final totals in real-time as you type.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quotations -->
            <section id="quotations" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Quotations</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Propose technical services with professional quotations. Approve and convert them to invoices with a single click.</p>
                </div>
            </section>

            <!-- Payments -->
            <section id="payments" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Payments & Collections</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Record financial settlements. Rooterin supports partial payments, allowing you to track deposits (DP) and final balances.</p>
                </div>
            </section>

            <!-- Reports -->
            <section id="reports" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                        <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">Financial Reports</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Gain insights into your revenue growth. Filter by date range to see how your business is performing over time.</p>
                </div>
            </section>

            <!-- Settings -->
            <section id="settings" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">
                        <i data-lucide="settings" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">System Settings</h2>
                </div>
                <div class="space-y-6">
                    <p class="text-slate-500 leading-relaxed">Customize the workspace to your brand. Update company headers, tax rates, and numbering formats in the Settings module.</p>
                </div>
            </section>

            <!-- FAQ -->
            <section id="faq" class="scroll-mt-32 space-y-8 pt-10 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="help-circle" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 font-outfit">FAQ</h2>
                </div>
                <div class="space-y-4" x-data="{ active: null }">
                    <div class="glass-card p-6 cursor-pointer hover:border-indigo-500/30 transition-all" @click="active = active === 1 ? null : 1">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-900">How to create a professional invoice?</h5>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="active === 1 ? 'rotate-180' : ''"></i>
                        </div>
                        <div x-show="active === 1" class="mt-4 text-xs text-slate-500 leading-relaxed" x-collapse>
                            Go to Invoices > New Invoice. Select a client, add job items, and hit Save. You can then download the PDF for your client.
                        </div>
                    </div>
                    <div class="glass-card p-6 cursor-pointer hover:border-indigo-500/30 transition-all" @click="active = active === 2 ? null : 2">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-900">Can I record partial payments?</h5>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="active === 2 ? 'rotate-180' : ''"></i>
                        </div>
                        <div x-show="active === 2" class="mt-4 text-xs text-slate-500 leading-relaxed" x-collapse>
                            Yes. In the Invoice Detail page, use the "Record Payment" button and enter the specific amount received. The system will update the remaining balance.
                        </div>
                    </div>
                    <div class="glass-card p-6 cursor-pointer hover:border-indigo-500/30 transition-all" @click="active = active === 3 ? null : 3">
                        <div class="flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-900">How to download PDF?</h5>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="active === 3 ? 'rotate-180' : ''"></i>
                        </div>
                        <div x-show="active === 3" class="mt-4 text-xs text-slate-500 leading-relaxed" x-collapse>
                            Open any invoice from the ledger and click the "Download PDF" button in the top action bar.
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
