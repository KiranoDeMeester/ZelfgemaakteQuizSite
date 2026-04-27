<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Player;
use App\Models\Question;
use App\Models\Answer;
use App\Models\PlayerAnswer;
use App\Services\QuizRunnerService;
use App\Services\RoomService;
use Livewire\Component;

class QuizPlay extends Component
{
    public $code;
    public $isHost = false;
    public $hasAnswered = false;
    public $selectedAnswerId = null;
    public $lastQuestionId = null;

    public function mount($code, QuizRunnerService $service)
    {
        $this->code = $code;
        $room = Room::where('code', $code)->firstOrFail();
        
        if (session('host_room_' . $code)) {
            $this->isHost = true;
            if (!$room->current_question_id) {
                $firstQ = $service->getNextQuestion($room);
                if ($firstQ) {
                    $room->update([
                        'current_question_id' => $firstQ->id,
                        'question_started_at' => now()
                    ]);
                }
            }
        } else {
            $player = Player::find(session('player_id'));
            if (!$player || $player->room_id !== $room->id) {
                return redirect()->route('join', ['room' => $code]);
            }
        }
        
        $this->lastQuestionId = $room->current_question_id;
    }

    public function submitAnswer($answerId, QuizRunnerService $service)
    {
        if ($this->isHost || $this->hasAnswered) return;

        $room = Room::where('code', $this->code)->first();
        
        // Check if time is up
        $question = $room->currentQuestion;
        if ($question && $room->question_started_at) {
            $elapsed = now()->diffInSeconds($room->question_started_at);
            if ($elapsed >= $question->time_limit) {
                return;
            }
        }

        $player = Player::find(session('player_id'));
        $answer = Answer::find($answerId);

        if (!$question || !$answer) return;

        $service->submitAnswer($player, $question, $answer);
        $this->hasAnswered = true;
        $this->selectedAnswerId = $answerId;
    }

    public function nextQuestion(QuizRunnerService $service)
    {
        if (!$this->isHost) return;

        $room = Room::where('code', $this->code)->first();
        $service->moveToNextQuestion($room);
    }

    public function closeRoom(RoomService $roomService)
    {
        if (!session('host_room_' . $this->code)) {
            return;
        }

        $room = Room::where('code', $this->code)->first();
        if ($room) {
            $roomService->finishRoom($room);
        }

        return $this->redirect(route('room.results', ['code' => $this->code]), navigate: true);
    }

    public function render()
    {
        $room = Room::where('code', $this->code)->with(['currentQuestion.answers', 'players.playerAnswers'])->first();
        
        if (!$room) {
            return view('livewire.error-page', ['message' => 'Quiz sessie niet gevonden.'])
                ->layout('layouts.app');
        }

        if ($room->status === 'finished') {
            return $this->redirect(route('room.results', ['code' => $this->code]), navigate: true);
        }

        $question = $room->currentQuestion;
        $timeLeft = 0;
        $isTimeUp = false;

        if ($question && $room->question_started_at) {
            $elapsed = now()->diffInSeconds($room->question_started_at);
            $timeLeft = max(0, $question->time_limit - $elapsed);
            $isTimeUp = $timeLeft <= 0;
        }

        $answeredCount = PlayerAnswer::where('question_id', $room->current_question_id)
            ->whereIn('player_id', $room->players->pluck('id'))
            ->count();
        $totalPlayers = $room->players->count();
        
        $showResults = $isTimeUp || ($answeredCount == $totalPlayers && $totalPlayers > 0);

        // Sync question state for players
        if (!$this->isHost && $this->lastQuestionId !== $room->current_question_id) {
            $this->hasAnswered = false;
            $this->selectedAnswerId = null;
            $this->lastQuestionId = $room->current_question_id;
        }

        // Host specific data
        $playerStatuses = [];
        if ($this->isHost) {
            foreach ($room->players as $player) {
                $answer = $player->playerAnswers()->where('question_id', $room->current_question_id)->first();
                $playerStatuses[] = [
                    'name' => $player->name,
                    'has_answered' => !is_null($answer),
                    'is_correct' => $answer ? $answer->is_correct : false
                ];
            }
        }

        return view('livewire.quiz-play', [
            'room' => $room,
            'question' => $question,
            'timeLeft' => $timeLeft,
            'isTimeUp' => $isTimeUp,
            'showResults' => $showResults,
            'totalPlayers' => $totalPlayers,
            'answeredCount' => $answeredCount,
            'playerStatuses' => $playerStatuses
        ])->layout('layouts.app');
    }
}
