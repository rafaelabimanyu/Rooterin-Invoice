<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400">Overview of your business performance</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stats-card title="Total Revenue" value="$12,850.00" change="+12.5%" icon="dollar-sign" color="indigo" />
        <x-stats-card title="Pending Invoices" value="24" change="-2" icon="file-clock" color="amber" />
        <x-stats-card title="Active Clients" value="156" change="+8" icon="users" color="emerald" />
        <x-stats-card title="Overdue" value="$1,200.00" change="+5.2%" icon="alert-circle" color="rose" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Invoices -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 dark:text-white font-outfit">Recent Invoices</h3>
                <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <x-empty-state-table />
            </div>
        </div>

        <!-- Quick Actions / Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-indigo-600 rounded-3xl p-6 text-white overflow-hidden relative group">
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-2">Create Invoice</h3>
                    <p class="text-indigo-100 text-sm mb-6">Send a professional invoice to your client in seconds.</p>
                    <button class="w-full py-3 bg-white text-indigo-600 rounded-xl font-bold hover:bg-indigo-50 transition-colors">
                        New Invoice
                    </button>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="font-bold text-slate-900 dark:text-white font-outfit mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Invoices Sent</span>
                        <span class="font-bold text-slate-900 dark:text-white">1,240</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 75%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Paid Rate</span>
                        <span class="font-bold text-slate-900 dark:text-white">92%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 92%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
