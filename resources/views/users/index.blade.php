<x-app-layout :title="app()->getLocale() == 'en' ? 'Team & Staff Management' : 'Manajemen Tim & Staf Operasional'">
    <div class="animate-fade-in-up">
        <livewire:team-manager />
    </div>
</x-app-layout>
