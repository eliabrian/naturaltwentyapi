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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('image_path')->nullable();
            $table->enum('difficulty', ['kids', 'easy', 'medium', 'hard', 'expert'])
                ->default('kids');

            $table->smallInteger('age')
                ->default(0);

            $table->smallInteger('player_min')
                ->default(0);

            $table->smallInteger('player_max')
                ->default(0);

            $table->smallInteger('duration')
                ->default(0);

            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
