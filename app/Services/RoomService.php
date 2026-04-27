<?php
namespace App\Services;

use App\Models\Room;

class RoomService
{
    public function createRoom(int $quizId): Room
    {
        do {
            $code = str_pad((string)rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Room::where('code', $code)->exists());

        return Room::create([
            'quiz_id' => $quizId,
            'code' => $code,
            'status' => 'waiting'
        ]);
    }
}
