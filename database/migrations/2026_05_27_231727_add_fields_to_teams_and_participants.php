<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('logo')->nullable();
        });
 
        Schema::table('participants', function (Blueprint $table) {
            $table->string('photo')->nullable();
        });
    }
 
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'logo']);
        });
 
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
