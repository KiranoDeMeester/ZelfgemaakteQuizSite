<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Player;
use App\Services\QrService;
use Livewire\Component;

class Lobby extends Component
{
    public $code;
    public $qrUrl;
    public $isHost = false;

    public function mount($code, QrService $qrService)
    {
        $this->code = $code;
        $room = Room::where('code', $code)->firstOrFail();
        $this->qrUrl = $qrService->getQrUrl($code);
        
        if (session('host_room_' . $code)) {
            $this->isHost = true;
        }
    }

    public function startQuiz(RoomService $roomService)
    {
        if (!$this->isHost) return;

        $room = Room::where('code', $this->code)->withCount('players')->first();
        
        if ($room->players_count === 0) {
            session()->flash('error', 'Je kunt de quiz niet starten zonder spelers!');
            return;
        }

        $roomService->startRoom($room);
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
        
        session()->forget('host_room_' . $this->code);
        
        return $this->redirect(route('host'), navigate: true);
    }

    public function render()
    {
        $room = Room::where('code', $this->code)->with('players')->first();

        if (!$room) {
            return view('livewire.error-page', ['message' => 'Kamer niet gevonden.'])
                ->layout('layouts.app');
        }

        if ($room->status === 'active') {
            return $this->redirect(route('room.play', ['code' => $this->code]), navigate: true);
        }

        if ($room->status === 'finished') {
            if ($this->isHost) {
                return $this->redirect(route('host'), navigate: true);
            } else {
                session()->flash('error', 'De host heeft de room gesloten.');
                return $this->redirect(route('join'), navigate: true);
            }
        }

        return view('livewire.lobby', [
            'room' => $room,
            'players' => $room->players
        ])->layout('layouts.app');
    }
}
