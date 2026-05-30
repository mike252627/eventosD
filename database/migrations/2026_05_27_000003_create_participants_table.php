<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $blueprint->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
