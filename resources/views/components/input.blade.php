@props(['label', 'name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false])

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
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
        {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 placeholder:text-slate-400']) }}
    >
    @error($name)
        <p class="text-xs text-rose-500 font-medium">{{ $message }}</p>
    @enderror
</div>
