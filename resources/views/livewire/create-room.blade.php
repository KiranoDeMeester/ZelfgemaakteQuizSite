<div class="glass rounded-2xl p-8 shadow-2xl">
    <h1 class="text-4xl font-bold mb-8 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
        Host een Quiz
    </h1>

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Selecteer een Quiz</label>
            <select wire:model="selectedQuizId" 
                    class="w-full bg-slate-800 border border-slate-600 rounded-xl p-4 text-white focus:outline-none focus:border-blue-500 transition-all appearance-none">
                <option value="">-- Kies een quiz --</option>
                @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                @endforeach
            </select>
            @error('selectedQuizId') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <button wire:click="createRoom" 
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2 group">
            <span>Maak Room Aan</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
        
        <div class="pt-4 flex flex-col gap-3 text-center">
            <a href="{{ route('quizzes.index') }}" class="text-blue-400 hover:text-blue-300 text-sm font-bold transition-colors flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                Beheer Quizzen
            </a>
            <a href="{{ route('join') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Terug naar join</a>
        </div>
    </div>
</div>
