<div class="space-y-8 animate-in fade-in duration-500">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-black text-white italic">{{ $quiz->title }}</h1>
            <p class="text-gray-400">Beheer de vragen van deze quiz.</p>
        </div>
        <a href="{{ route('quizzes.index') }}" class="text-slate-400 hover:text-white font-semibold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Terug naar Overzicht
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add/Edit Question Form -->
        <div class="lg:col-span-1">
            <div class="glass p-8 rounded-3xl sticky top-8 border border-slate-700/50">
                <h2 class="text-2xl font-bold text-white mb-6">{{ $editingQuestionId ? 'Bewerk Vraag' : 'Nieuwe Vraag' }}</h2>
                
                <form wire:submit="saveQuestion" class="space-y-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Vraagstelling</label>
                        <textarea wire:model="questionText" rows="3" 
                                  class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all placeholder-slate-600"
                                  placeholder="Typ hier de vraag..."></textarea>
                        @error('questionText') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Afbeelding URL (optioneel)</label>
                        <input type="url" wire:model="imageUrl" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all placeholder-slate-600"
                               placeholder="https://example.com/image.jpg">
                        @error('imageUrl') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Tijdslimiet (sec)</label>
                        <input type="number" wire:model="timeLimit" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Antwoordopties</label>
                        @foreach($answers as $index => $answer)
                            <div class="relative">
                                <input type="text" wire:model="answers.{{ $index }}.text" 
                                       placeholder="Optie {{ chr(65 + $index) }}"
                                       class="w-full bg-slate-800/50 border {{ $answers[$index]['is_correct'] ? 'border-emerald-500/50 ring-1 ring-emerald-500/50' : 'border-slate-700' }} rounded-xl px-4 py-3 text-white focus:outline-none transition-all pr-12">
                                
                                <button type="button" wire:click="setCorrect({{ $index }})" 
                                        class="absolute right-2 top-2 w-8 h-8 rounded-lg flex items-center justify-center transition-all 
                                        {{ $answers[$index]['is_correct'] ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-500 hover:bg-slate-600' }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ $editingQuestionId ? 'Sla Wijzigingen Op' : 'Voeg Vraag Toe' }}
                        </button>

                        @if($editingQuestionId)
                            <button type="button" wire:click="cancelEdit" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-400 font-bold py-3 rounded-xl transition-all border border-slate-700">
                                Annuleren
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Questions List -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-2xl font-bold text-white mb-6">Huidige Vragen ({{ $questions->count() }})</h2>

            @forelse($questions as $index => $q)
                <div class="glass p-6 rounded-2xl border border-slate-700/50 animate-in fade-in slide-in-from-right-8 duration-300 {{ $editingQuestionId == $q->id ? 'ring-2 ring-blue-500' : '' }}" style="animation-delay: {{ $index * 50 }}ms">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-grow">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-blue-600 text-[10px] font-black uppercase px-2 py-1 rounded">Vraag {{ $index + 1 }}</span>
                                <span class="bg-slate-800 text-[10px] font-black uppercase px-2 py-1 rounded text-slate-400">{{ $q->time_limit }}s</span>
                            </div>
                            <h4 class="text-xl font-bold text-white mb-4">{{ $q->question }}</h4>
                            
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($q->answers as $ans)
                                    <div class="flex items-center gap-2 text-sm p-3 rounded-lg {{ $ans->is_correct ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-slate-800/30 text-slate-500' }}">
                                        <div class="w-2 h-2 rounded-full {{ $ans->is_correct ? 'bg-emerald-500' : 'bg-slate-700' }}"></div>
                                        {{ $ans->text }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <button wire:click="editQuestion({{ $q->id }})" 
                                    class="text-slate-600 hover:text-blue-400 p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="deleteQuestion({{ $q->id }})" 
                                    wire:confirm="Weet je zeker dat je deze vraag wilt verwijderen?"
                                    class="text-slate-600 hover:text-red-400 p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass p-12 rounded-3xl text-center border border-slate-700/50 dashed">
                    <p class="text-slate-500 italic">Nog geen vragen toegevoegd aan deze quiz...</p>
                </div>
            @endif
        </div>
    </div>
</div>
