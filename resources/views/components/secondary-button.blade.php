<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-transparent border border-slate-700 rounded-lg font-bold text-xs uppercase tracking-wider text-white hover:bg-slate-800 hover:border-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-950 disabled:opacity-50 transition duration-150 cursor-pointer shadow-sm']) }}>
    {{ $slot }}
</button>
