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
        Schema::create('meeting_schedule', function (Blueprint $table) {
            $table->id();
            $table->text('meeting_title',50);
            $table->string('meeting_description',255)->nullable();
            $table->string('meeting_type',20);
            $table->string('meeting_platform',255)->nullable();
            $table->string('meeting_link',255)->nullable();
            $table->string('meeting_location',255)->nullable();
            $table->date('meeting_date');
            $table->time('meeting_start_time');
            $table->time('meeting_end_time');
            $table->string('meeting_status',20);
            $table->foreignId('tutor_id')->constrained('users', 'id')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->foreignId('student_id')->constrained('users', 'id')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_schedule');
    }
};
