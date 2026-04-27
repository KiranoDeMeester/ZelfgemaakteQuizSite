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

        $room = Room::where('code', $this->code)->withCount('players')->first();
        
        if ($room->players_count === 0) {
            session()->flash('error', 'Je kunt de quiz niet starten zonder spelers!');
            return;
        }

        $room->update(['status' => 'active']);
    }

    public function closeRoom()
    {
        // Re-verify host status directly from session for maximum reliability
        if (!session('host_room_' . $this->code)) {
            return;
        }

        $room = Room::where('code', $this->code)->first();
        if ($room) {
            $room->update(['status' => 'finished']);
        }
        
        session()->forget('host_room_' . $this->code);
        
        $this->redirect(route('host'), navigate: true);
    }

    public function render()
    {
        $room = Room::where('code', $this->code)->with('players')->first();

        if (!$room) {
            return view('livewire.error-page', ['message' => 'Kamer niet gevonden.'])
                ->layout('layouts.app');
        }

        if ($room->status === 'active') {
            $this->redirect(route('room.play', ['code' => $this->code]), navigate: true);
            return view('livewire.error-page', ['message' => 'Doorsturen naar quiz...'])
                ->layout('layouts.app');
        }

        if ($room->status === 'finished') {
            if ($this->isHost) {
                $this->redirect(route('host'), navigate: true);
            } else {
                session()->flash('error', 'De host heeft de room gesloten.');
                $this->redirect(route('join'), navigate: true);
            }
            return view('livewire.error-page', ['message' => 'Kamer is gesloten.'])
                ->layout('layouts.app');
        }

        return view('livewire.lobby', [
            'room' => $room,
            'players' => $room->players
        ])->layout('layouts.app');
    }
}
