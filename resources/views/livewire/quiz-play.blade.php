<div @if(!$showEndConfirmation) wire:poll.2s @endif class="space-y-6">
    <div class="flex justify-between items-center mb-4">
        <div class="bg-slate-800 px-4 py-2 rounded-full border border-slate-700 text-sm font-bold text-blue-400 uppercase tracking-wider">
            Room: {{ $code }}
        </div>
        <div class="flex items-center gap-4">
            <div class="bg-slate-800 px-4 py-2 rounded-full border border-slate-700 text-sm font-bold {{ $isTimeUp ? 'text-red-500 animate-pulse' : 'text-yellow-400' }}">
                <span class="font-mono text-lg">{{ $timeLeft }}s</span>
            </div>
            <div class="bg-slate-800 px-4 py-2 rounded-full border border-slate-700 text-sm font-bold text-emerald-400">
                {{ $answeredCount }} / {{ $totalPlayers }} Beantwoord
            </div>
        </div>
    </div>

    @if($question)
        <div class="glass rounded-3xl p-8 shadow-2xl relative overflow-hidden">
            @if($showResults)
                <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500 animate-in slide-in-from-left duration-1000"></div>
            @endif

            @if($question->image)
                <div class="mb-8 flex justify-center">
                    <img src="{{ $question->image }}" alt="Question Image" class="max-h-64 rounded-xl shadow-lg border border-slate-700">
                </div>
            @endif

            <h2 class="text-3xl font-bold text-center text-white mb-10 leading-tight">
                {{ $question->question }}
            </h2>

            @if($isHost || $showResults || $hasAnswered)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($playerStatuses as $status)
                        <div class="flex items-center justify-between p-4 rounded-xl border {{ $status['has_answered'] ? ($status['is_correct'] ? 'bg-emerald-500/10 border-emerald-500/50' : 'bg-red-500/10 border-red-500/50') : 'bg-slate-800/50 border-slate-700' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $status['has_answered'] ? ($status['is_correct'] ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white') : 'bg-slate-700 text-slate-400' }}">
                                    @if($status['has_answered'])
                                        {!! $status['is_correct'] ? '✓' : '✗' !!}
                                    @else
                                        ?
                                    @endif
                                </div>
                                <span class="font-semibold {{ $status['has_answered'] ? 'text-white' : 'text-slate-400' }}">{{ $status['name'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($isHost || $showResults)
                    <div class="mt-10 pt-8 border-t border-slate-700/50">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Correct Antwoord</h3>
                        <div class="bg-emerald-500/20 border border-emerald-500/50 p-4 rounded-xl text-emerald-400 font-bold text-xl flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $question->answers->where('is_correct', true)->first()?->text }}
                        </div>
                    </div>
                @endif
            @endif

            @if(!$isHost)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 {{ ($isHost || $hasAnswered || $showResults) ? 'mt-8 opacity-50' : '' }}">
                    @foreach($question->answers as $index => $answer)
                        <button 
                            @if(!$isHost && !$hasAnswered && !$showResults) wire:click="submitAnswer({{ $answer->id }})" @endif
                            @disabled($isHost || $hasAnswered || $showResults)
                            class="relative group p-6 rounded-2xl text-left transition-all duration-300 border-2 
                            {{ $hasAnswered && $selectedAnswerId == $answer->id ? 'border-blue-500 bg-blue-500/20 ring-2 ring-blue-500/50' : 'border-slate-700 bg-slate-800/50 hover:border-slate-500' }}
                            {{ $showResults && $answer->is_correct ? 'border-emerald-500 bg-emerald-500/10' : '' }}
                            {{ ($isHost || $hasAnswered || $showResults) ? 'cursor-default' : 'hover:scale-[1.02] active:scale-95' }}
                            {{ ($hasAnswered || $showResults) && $selectedAnswerId != $answer->id && !$answer->is_correct ? 'opacity-30' : '' }}">
                            
                            <div class="flex items-center gap-4">
                                <span class="flex-shrink-0 w-10 h-10 rounded-full {{ $showResults && $answer->is_correct ? 'bg-emerald-500' : 'bg-slate-700' }} flex items-center justify-center font-bold text-white group-hover:bg-slate-600 transition-colors">
                                    {{ chr(65 + $index) }}
                                </span>
                                <span class="text-xl font-semibold text-white">{{ $answer->text }}</span>
                            </div>

                            @if($hasAnswered && $selectedAnswerId == $answer->id)
                                <div class="absolute top-2 right-2">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-ping"></div>
                                </div>
                            @endif

                            @if($showResults && $answer->is_correct)
                                <div class="absolute -top-2 -right-2 bg-emerald-500 text-white rounded-full p-1 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if($isHost)
            <div class="flex flex-col items-center gap-4 pt-6">
                <button wire:click="nextQuestion" 
                        class="w-full sm:w-auto bg-white text-slate-900 font-black py-4 px-12 rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl flex items-center justify-center gap-3 group">
                    <span>Volgende Vraag</span>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>

                <button wire:click="confirmEnd" 
                        class="text-slate-500 hover:text-red-400 text-sm font-semibold transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Quiz Beëindigen</span>
                </button>
            </div>
        @elseif($hasAnswered)
            <div class="text-center py-6 animate-bounce">
                <p class="text-emerald-400 font-bold text-xl">Antwoord verzonden! Wachten op de volgende vraag...</p>
            </div>
        @endif
    @else
        <div class="glass rounded-3xl p-12 text-center">
            <h2 class="text-2xl font-bold text-gray-500">Geen vragen gevonden...</h2>
        </div>
    @endif

    <!-- Custom Modal -->
    @if($showEndConfirmation)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-in fade-in duration-200">
            <div class="glass max-w-md w-full p-8 rounded-3xl shadow-2xl border border-slate-700 animate-in zoom-in-95 duration-200">
                <div class="w-16 h-16 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-white text-center mb-2">Quiz beëindigen?</h2>
                <p class="text-slate-400 text-center mb-8">Weet je zeker dat je de quiz nu wilt stoppen? Je gaat direct naar het eindscherm.</p>

                <div class="flex flex-col gap-3">
                    <button wire:click="closeRoom" class="w-full bg-red-600 hover:bg-red-500 text-white font-black py-4 rounded-xl transition-all">
                        Ja, Stop Quiz
                    </button>
                    <button wire:click="cancelEnd" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-3 rounded-xl transition-all border border-slate-700">
                        Annuleren
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
