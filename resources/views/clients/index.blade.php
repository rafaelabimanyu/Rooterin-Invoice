<x-app-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Clients</h1>
            <p class="text-slate-500 dark:text-slate-400">Manage your customers and their details</p>
        </div>
        <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/20">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Add New Client
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6">
        <form action="{{ route('clients.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, company, or code..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl transition-all outline-none text-sm text-slate-900 dark:text-white">
            </div>
            <select name="status" class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-900 rounded-xl transition-all outline-none text-sm text-slate-900 dark:text-white">
                <option value="">All Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-slate-900 dark:bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-800 dark:hover:bg-slate-700 transition-colors">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('clients.index') }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4 font-bold">Client Info</th>
                        <th class="px-6 py-4 font-bold">Company</th>
                        <th class="px-6 py-4 font-bold">Contact</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ substr($client->nama_client, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white">{{ $client->nama_client }}</p>
                                        <p class="text-xs text-slate-500 font-medium">{{ $client->kode_client }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $client->nama_perusahaan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-900 dark:text-white font-medium">{{ $client->email ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $client->no_hp ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge :status="$client->status" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('clients.show', $client) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition-all">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state-table />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clients->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
