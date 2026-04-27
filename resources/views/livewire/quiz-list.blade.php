<div class="space-y-8 animate-in fade-in duration-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-black text-white italic">Quiz Beheer</h1>
            <p class="text-gray-400">Maak nieuwe quizzen aan of bewerk bestaande.</p>
        </div>
        <a href="{{ route('host') }}" class="text-slate-400 hover:text-white font-semibold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Terug naar Host
        </a>
    </div>

    <div class="glass p-6 rounded-2xl border border-slate-700/50">
        <form wire:submit="createQuiz" class="flex gap-4">
            <input type="text" wire:model="newQuizTitle" 
                   placeholder="Naam van de nieuwe quiz..." 
                   class="flex-grow bg-slate-800/50 border border-slate-700 rounded-xl px-6 py-4 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg shadow-blue-500/20">
                Quiz Aanmaken
            </button>
        </form>
        @error('newQuizTitle') <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($quizzes as $quiz)
            <div class="glass p-6 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition-all group">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-white group-hover:text-blue-400 transition-colors">{{ $quiz->title }}</h3>
                        <p class="text-slate-500 font-mono text-sm uppercase tracking-widest mt-1">{{ $quiz->questions_count }} vragen</p>
                    </div>
                    <button wire:click="deleteQuiz({{ $quiz->id }})" 
                            wire:confirm="Weet je zeker dat je deze quiz wilt verwijderen? Alle vragen en antwoorden gaan verloren!"
                            class="text-slate-600 hover:text-red-400 p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('quizzes.edit', ['id' => $quiz->id]) }}" 
                       class="flex-grow bg-slate-800 hover:bg-slate-700 text-white text-center font-bold py-3 px-6 rounded-xl transition-all border border-slate-700 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Bewerken
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
