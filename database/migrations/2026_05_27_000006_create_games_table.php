<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('discipline_id')->constrained('disciplines')->cascadeOnDelete();
            $blueprint->foreignId('home_team_id')->constrained('teams')->cascadeOnDelete();
            $blueprint->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();
            $blueprint->foreignId('referee_id')->nullable()->constrained('users')->nullOnDelete();
            $blueprint->integer('home_team_score')->default(0);
            $blueprint->integer('away_team_score')->default(0);
            $blueprint->string('status')->default('pending');
            $blueprint->dateTime('match_date')->nullable();
            $blueprint->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
