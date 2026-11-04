<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-8 text-slate-100">
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="text-3xl">🤘</span>
                        <div>
                            <h3 class="text-xl font-black uppercase tracking-wider text-white">Lu Masuk. Welcome Back.</h3>
                            <p class="text-xs text-slate-400">Cek tiket lu atau sikat show baru yang lagi available.</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded-lg transition duration-150">
                            Cek Show Aktif &rarr;
                        </a>
                        <a href="{{ route('my-tickets.index') }}" class="inline-flex items-center px-4 py-2.5 border border-slate-700 hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider rounded-lg transition duration-150">
                            Tiket Gue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
