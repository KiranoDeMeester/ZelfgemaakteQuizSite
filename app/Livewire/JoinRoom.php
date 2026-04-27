<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;

class JoinRoom extends Component
{
    public $roomCode;
    public $name;

    public function mount()
    {
        $this->roomCode = request()->query('room');
    }

    public function join()
    {
        $this->validate([
            'roomCode' => 'required|size:5|exists:rooms,code',
            'name' => 'required|min:2|max:20'
        ], [
            'roomCode.exists' => 'Deze room bestaat niet.',
            'roomCode.size' => 'De code moet exact 5 cijfers zijn.'
        ]);

        $room = Room::where('code', $this->roomCode)->first();

        if ($room->status === 'finished') {
            $this->addError('roomCode', 'Deze quiz is al afgelopen.');
            return;
        }

        // Check if player already joined this room
        $existingPlayerId = session('player_id');
        if ($existingPlayerId) {
            $existingPlayer = \App\Models\Player::find($existingPlayerId);
            if ($existingPlayer && $existingPlayer->room_id === $room->id) {
                return redirect()->route('room.lobby', ['code' => $room->code]);
            }
        }

        // Create player
        $player = $room->players()->create([
            'name' => $this->name,
            'score' => 0
        ]);

        session(['player_id' => $player->id]);

        return redirect()->route('room.lobby', ['code' => $room->code]);
    }

    public function render()
    {
        return view('livewire.join-room')->layout('layouts.app');
    }
}
