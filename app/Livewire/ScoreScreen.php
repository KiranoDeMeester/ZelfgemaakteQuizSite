<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Player;
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

    public function exportCsv()
    {
        $room = Room::where('code', $this->code)->with('players', 'quiz')->first();
        $players = $room->players->sortByDesc('score');
        $totalQuestions = $room->quiz->questions->count();

        $filename = "results_{$this->code}.csv";
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['Name', 'Score', 'Total Questions']);

        foreach ($players as $player) {
            fputcsv($handle, [$player->name, $player->score, $totalQuestions]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
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
