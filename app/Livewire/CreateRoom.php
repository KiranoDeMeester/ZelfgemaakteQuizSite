<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Services\RoomService;
use Livewire\Component;

class CreateRoom extends Component
{
    public $quizzes;
    public $selectedQuizId;

    public function mount()
    {
        $this->quizzes = Quiz::all();
    }

    public function createRoom(RoomService $service)
    {
        $this->validate([
            'selectedQuizId' => 'required|exists:quizzes,id'
        ]);

        $room = $service->createRoom($this->selectedQuizId);
        
        session(['host_room_' . $room->code => true]);
        
        return redirect()->route('room.lobby', ['code' => $room->code]);
    }

    public function render()
    {
        return view('livewire.create-room')
            ->layout('layouts.app');
    }
}
