@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-900 border-slate-700 text-white placeholder-slate-500 focus:border-red-600 focus:ring-red-600 shadow-sm text-sm disabled:opacity-50 disabled:bg-slate-950']) }}>
