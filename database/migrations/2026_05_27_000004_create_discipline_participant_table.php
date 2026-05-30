<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discipline_participant', function (Blueprint $blueprint) {
            $blueprint->foreignId('discipline_id')->constrained('disciplines')->cascadeOnDelete();
            $blueprint->foreignId('participant_id')->constrained('participants')->cascadeOnDelete();
            $blueprint->primary(['discipline_id', 'participant_id']);
        });
 
        $driver = DB::getDriverName();
 
        if ($driver === 'sqlite') {
            DB::unprepared("
                CREATE TRIGGER check_max_disciplines_before_insert
                BEFORE INSERT ON discipline_participant
                FOR EACH ROW
                BEGIN
                    SELECT CASE
                        WHEN (SELECT COUNT(*) FROM discipline_participant WHERE participant_id = NEW.participant_id) >= 2
                        THEN RAISE(ABORT, 'Un participante no puede estar registrado en más de 2 disciplinas.')
                    END;
                END;
            ");
 
            DB::unprepared("
                CREATE TRIGGER check_max_disciplines_before_update
                BEFORE UPDATE ON discipline_participant
                FOR EACH ROW
                BEGIN
                    SELECT CASE
                        WHEN (SELECT COUNT(*) FROM discipline_participant WHERE participant_id = NEW.participant_id AND discipline_id != OLD.discipline_id) >= 2
                        THEN RAISE(ABORT, 'Un participante no puede estar registrado en más de 2 disciplinas.')
                    END;
                END;
            ");
        } elseif ($driver === 'mysql') {
            DB::unprepared("
                CREATE TRIGGER check_max_disciplines_before_insert
                BEFORE INSERT ON discipline_participant
                FOR EACH ROW
                BEGIN
                    DECLARE cnt INT;
                    SELECT COUNT(*) INTO cnt FROM discipline_participant WHERE participant_id = NEW.participant_id;
                    IF cnt >= 2 THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Un participante no puede estar registrado en más de 2 disciplinas.';
                    END IF;
                END;
            ");
 
            DB::unprepared("
                CREATE TRIGGER check_max_disciplines_before_update
                BEFORE UPDATE ON discipline_participant
                FOR EACH ROW
                BEGIN
                    DECLARE cnt INT;
                    SELECT COUNT(*) INTO cnt FROM discipline_participant WHERE participant_id = NEW.participant_id AND discipline_id != OLD.discipline_id;
                    IF cnt >= 2 THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Un participante no puede estar registrado en más de 2 disciplinas.';
                    END IF;
                END;
            ");
        }
    }
 
    public function down(): void
    {
        $driver = DB::getDriverName();
 
        if ($driver === 'sqlite' || $driver === 'mysql') {
            DB::unprepared("DROP TRIGGER IF EXISTS check_max_disciplines_before_insert;");
            DB::unprepared("DROP TRIGGER IF EXISTS check_max_disciplines_before_update;");
        }
 
        Schema::dropIfExists('discipline_participant');
    }
};
