<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="glass p-12 rounded-3xl shadow-2xl max-w-lg w-full border border-slate-700/50">
        <div class="mb-8 flex justify-center">
            <div class="w-24 h-24 bg-red-500/10 rounded-full flex items-center justify-center text-red-500 animate-pulse">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-4xl font-black text-white mb-4 tracking-tight">Oeps! Er ging iets mis.</h1>
        <p class="text-gray-400 text-lg mb-10">
            {{ $message ?? 'De gezochte kamer of quiz is niet meer beschikbaar.' }}
        </p>

        <a href="{{ route('host') }}" 
           class="inline-flex items-center justify-center gap-2 w-full bg-white text-slate-900 font-bold py-4 px-8 rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Terug naar Dashboard</span>
        </a>
    </div>
</div>
