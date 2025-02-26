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
        Schema::create('meeting_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_description');
            $table->text('meeting_title');
            $table->string('meeting_type');
            $table->string('meeting_platform');
            $table->string('meeting_link')->nullable();
            $table->string('meeting_location')->nullable();
            $table->date('meeting_date');
            $table->time('meeting_start_time');
            $table->time('meeting_end_time');
            $table->string('meeting_status');
            $table->foreignId('tutor_id')->constrained('users', 'id');
            $table->foreignId('student_id')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_schedules');
    }
};
