@props(['label', 'name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            {{ $label }} @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600']) }}
    >
    @error($name)
        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
    @enderror
</div>
