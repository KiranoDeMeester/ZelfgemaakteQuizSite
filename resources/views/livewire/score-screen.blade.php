<div class="space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-700">
    <div class="text-center">
        <h1 class="text-6xl font-black text-white mb-2 italic">FINISH!</h1>
        <p class="text-gray-400 text-xl">De eindstand is bekend.</p>
    </div>

    <div class="glass rounded-3xl p-8 shadow-2xl">
        <div class="space-y-4">
            @foreach($players as $index => $player)
                <div class="flex items-center gap-6 p-4 rounded-2xl 
                    {{ $index === 0 ? 'bg-yellow-500/20 border-2 border-yellow-500/50 scale-105' : 
                       ($index === 1 ? 'bg-slate-300/10 border-2 border-slate-300/30' : 
                       ($index === 2 ? 'bg-amber-700/10 border-2 border-amber-700/30' : 'bg-slate-800/50 border border-slate-700')) }}">
                    
                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center text-2xl font-black 
                        {{ $index === 0 ? 'text-yellow-400' : 'text-slate-400' }}">
                        #{{ $index + 1 }}
                    </div>

                    <div class="flex-grow">
                        <div class="text-xl font-bold text-white">{{ $player->name }}</div>
                        <div class="text-sm text-gray-500">{{ $player->score }} punten</div>
                    </div>

                    <div class="text-right">
                        <div class="text-xs uppercase font-bold text-slate-500 mb-1">Eindscore</div>
                        <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white to-slate-400">
                            {{ $player->score }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if($isHost)
        <div class="flex flex-col items-center gap-4">
            <button wire:click="exportCsv" 
                    class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-4 px-12 rounded-2xl transition-all shadow-xl shadow-emerald-500/20 flex items-center gap-3 group">
                <svg class="w-6 h-6 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Exporteer Resultaten (CSV)</span>
            </button>
            
            <a href="{{ route('host') }}" class="text-slate-400 hover:text-white font-semibold transition-colors">Start een nieuwe quiz</a>
        </div>
    @endif
</div>
