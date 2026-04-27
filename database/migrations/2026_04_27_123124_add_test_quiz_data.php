<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $quiz = \App\Models\Quiz::create(['title' => 'Cloud Test Quiz 🚀']);

        $q1 = $quiz->questions()->create([
            'question' => 'Wat is de hoofdstad van België?',
            'time_limit' => 20
        ]);
        $q1->answers()->createMany([
            ['text' => 'Antwerpen', 'is_correct' => false],
            ['text' => 'Brussel', 'is_correct' => true],
            ['text' => 'Gent', 'is_correct' => false],
            ['text' => 'Brugge', 'is_correct' => false],
        ]);

        $q2 = $quiz->questions()->create([
            'question' => 'Hoeveel graden is een rechte hoek?',
            'time_limit' => 15
        ]);
        $q2->answers()->createMany([
            ['text' => '45', 'is_correct' => false],
            ['text' => '90', 'is_correct' => true],
            ['text' => '180', 'is_correct' => false],
            ['text' => '360', 'is_correct' => false],
        ]);

        $q3 = $quiz->questions()->create([
            'question' => 'Welke planeet staat het dichtst bij de zon?',
            'time_limit' => 20
        ]);
        $q3->answers()->createMany([
            ['text' => 'Venus', 'is_correct' => false],
            ['text' => 'Mercurius', 'is_correct' => true],
            ['text' => 'Aarde', 'is_correct' => false],
            ['text' => 'Mars', 'is_correct' => false],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
