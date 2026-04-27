<div class="glass rounded-2xl p-8 shadow-2xl">
    <div class="text-center mb-8">
        <h1 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-blue-500 mb-2">
            Quiz Time!
        </h1>
        <p class="text-gray-400">Voer de room code in om mee te doen</p>
    </div>

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Room Code</label>
            <input wire:model="roomCode" 
                   type="text" 
                   maxlength="5"
                   placeholder="12345" 
                   class="w-full bg-slate-800 border border-slate-600 rounded-xl p-4 text-center text-3xl font-bold tracking-[0.5em] text-white focus:outline-none focus:border-emerald-500 transition-all uppercase placeholder:text-slate-700">
            @error('roomCode') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Jouw Naam</label>
            <input wire:model="name" 
                   type="text" 
                   placeholder="Bijv. Jan de Man" 
                   class="w-full bg-slate-800 border border-slate-600 rounded-xl p-4 text-white focus:outline-none focus:border-emerald-500 transition-all">
            @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button wire:click="join" 
                class="w-full bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-500 hover:to-blue-500 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 group">
            <span>Join de Room</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
        </button>

        <div class="pt-6 border-t border-slate-700/50 text-center">
            <p class="text-gray-500 text-sm mb-2">Wil je zelf een quiz hosten?</p>
            <a href="{{ route('host') }}" class="text-blue-400 hover:text-blue-300 font-semibold transition-colors">Start als Host</a>
        </div>
    </div>
</div>
