<div wire:poll.2s class="space-y-8">
    @if (session()->has('error'))
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 p-4 rounded-xl font-bold animate-bounce text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="glass rounded-2xl p-8 shadow-2xl text-center">
        @if($isHost)
            <h2 class="text-gray-400 text-sm font-semibold uppercase tracking-widest mb-2">Room Code</h2>
            <div class="text-7xl font-black text-white mb-6 tracking-tighter">{{ $code }}</div>
            
            <div class="flex justify-center mb-8">
                <div class="bg-white p-4 rounded-2xl shadow-inner">
                    <img src="{{ $qrUrl }}" alt="QR Code" class="w-48 h-48">
                </div>
            </div>

            <button wire:click="startQuiz" 
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2 group mb-3">
                <span>Start de Quiz</span>
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>

            <button wire:click="closeRoom" 
                    wire:confirm="Weet je zeker dat je deze room wilt sluiten?"
                    class="w-full bg-slate-800 hover:bg-red-900/50 text-slate-400 hover:text-red-400 font-semibold py-3 px-8 rounded-xl transition-all border border-slate-700 hover:border-red-500/50 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>Sluit Room</span>
            </button>
        @else
            <div class="py-12">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Wachten op de host...</h2>
                <p class="text-gray-400">De quiz begint zodra de host op start drukt.</p>
                <div class="mt-4 text-emerald-400 font-mono text-xl">{{ $code }}</div>
            </div>
        @endif
    </div>

    <div class="glass rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-200">Spelers in Lobby</h3>
            <span class="bg-slate-800 text-slate-300 px-3 py-1 rounded-full text-sm font-mono">{{ $players->count() }}</span>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse($players as $player)
                <div class="bg-slate-800/50 p-3 rounded-lg border border-slate-700 text-center animate-in fade-in zoom-in duration-300">
                    <span class="text-white font-semibold">{{ $player->name }}</span>
                    @if(session('player_id') == $player->id)
                        <span class="block text-[10px] text-emerald-400 uppercase font-bold mt-1">Jij</span>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-gray-500 italic">
                    Nog geen spelers verbonden...
                </div>
            @endforelse
        </div>
    </div>
</div>
