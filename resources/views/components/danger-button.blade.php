<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-red-950 border border-red-800 rounded-lg font-bold text-xs uppercase tracking-wider text-red-300 hover:bg-red-900 hover:border-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-slate-950 transition duration-150 disabled:opacity-50 cursor-pointer shadow-sm']) }}>
    {{ $slot }}
</button>
