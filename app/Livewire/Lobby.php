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

    public function startQuiz()
    {
        if (!$this->isHost) return;

        Room::where('code', $this->code)->update(['status' => 'active']);
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
        $room = Room::where('code', $this->code)->with('players')->firstOrFail();

        if ($room->status === 'active') {
            return redirect()->route('room.play', ['code' => $this->code]);
        }

        if ($room->status === 'closed') {
            session()->flash('error', 'De host heeft de room gesloten.');
            return redirect()->route('join');
        }

        return view('livewire.lobby', [
            'room' => $room,
            'players' => $room->players
        ])->layout('layouts.app');
    }
}
