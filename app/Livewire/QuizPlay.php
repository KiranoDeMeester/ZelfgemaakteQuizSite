<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Player;
use App\Models\Question;
use App\Models\Answer;
use App\Models\PlayerAnswer;
use App\Services\QuizRunnerService;
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
                $room->update(['current_question_id' => $firstQ?->id]);
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
        $player = Player::find(session('player_id'));
        $question = $room->currentQuestion;
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
        $nextQ = $service->getNextQuestion($room, $room->current_question_id);

        if ($nextQ) {
            $room->update(['current_question_id' => $nextQ->id]);
        } else {
            $room->update(['status' => 'finished']);
        }
    }

    public function closeRoom()
    {
        if (!$this->isHost) return;

        $room = Room::where('code', $this->code)->first();
        if ($room) {
            $room->update(['status' => 'closed']);
        }

        session()->forget('host_room_' . $this->code);
        
        return redirect()->route('host');
    }

    public function render()
    {
        $room = Room::where('code', $this->code)->with(['currentQuestion.answers', 'players'])->firstOrFail();
        
        if ($room->status === 'finished') {
            return redirect()->route('room.results', ['code' => $this->code]);
        }

        if ($room->status === 'closed') {
            session()->flash('error', 'De host heeft de quiz beëindigd.');
            return redirect()->route('join');
        }

        // Sync question state for players
        if (!$this->isHost && $this->lastQuestionId !== $room->current_question_id) {
            $this->hasAnswered = false;
            $this->selectedAnswerId = null;
            $this->lastQuestionId = $room->current_question_id;
        }

        return view('livewire.quiz-play', [
            'room' => $room,
            'question' => $room->currentQuestion,
            'totalPlayers' => $room->players->count(),
            'answeredCount' => PlayerAnswer::where('question_id', $room->current_question_id)
                ->whereIn('player_id', $room->players->pluck('id'))
                ->count()
        ])->layout('layouts.app');
    }
}
