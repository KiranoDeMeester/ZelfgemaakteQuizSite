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

    public function moveToNextQuestion(Room $room): bool
    {
        $nextQ = $this->getNextQuestion($room, $room->current_question_id);

        if ($nextQ) {
            $room->update([
                'current_question_id' => $nextQ->id,
                'question_started_at' => now()
            ]);
            return true;
        } else {
            $room->update(['status' => 'finished']);
            return false;
        }
    }

    public function generateCsvExport(Room $room)
    {
        $players = $room->players->sortByDesc('score');
        $totalQuestions = $room->quiz->questions->count();

        $callback = function () use ($players, $totalQuestions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Naam', 'Score', 'Totaal Vragen']);

            foreach ($players as $player) {
                fputcsv($handle, [$player->name, $player->score, $totalQuestions]);
            }
            fclose($handle);
        };

        return $callback;
    }
}
