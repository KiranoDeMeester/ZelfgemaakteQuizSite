<?php
namespace App\Services;

use App\Models\Room;
use App\Models\Player;
use App\Models\Question;
use App\Models\Answer;
use App\Models\PlayerAnswer;

class QuizRunnerService
{
    public function submitAnswer(Player $player, Question $question, Answer $answer): void
    {
        // Don't count it if already answered
        if (PlayerAnswer::where('player_id', $player->id)->where('question_id', $question->id)->exists()) {
            return;
        }

        PlayerAnswer::create([
            'player_id' => $player->id,
            'question_id' => $question->id,
            'answer_id' => $answer->id
        ]);

        if ($answer->is_correct) {
            $player->increment('score', 100);
        }
    }

    public function getNextQuestion(Room $room, $currentQuestionId = null): ?Question
    {
        $query = $room->quiz->questions();
        
        if ($currentQuestionId) {
            $query->where('id', '>', $currentQuestionId);
        }

        return $query->orderBy('id', 'asc')->first();
    }
}
