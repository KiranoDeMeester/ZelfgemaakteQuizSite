<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Player;
use App\Services\QuizRunnerService;
use Livewire\Component;

class ScoreScreen extends Component
{
    public $code;
    public $isHost = false;

    public function mount($code)
    {
        $this->code = $code;
        if (session('host_room_' . $code)) {
            $this->isHost = true;
        }
    }

    public function exportCsv(QuizRunnerService $service)
    {
        $room = Room::where('code', $this->code)->with('players', 'quiz')->first();
        $callback = $service->generateCsvExport($room);
        
        return response()->streamDownload($callback, "results_{$this->code}.csv");
    }

    public function render()
    {
        $room = Room::where('code', $this->code)->with(['players', 'quiz.questions'])->firstOrFail();
        $players = $room->players->sortByDesc('score');

        return view('livewire.score-screen', [
            'room' => $room,
            'players' => $players,
            'totalQuestions' => $room->quiz->questions->count()
        ])->layout('layouts.app');
    }
}
