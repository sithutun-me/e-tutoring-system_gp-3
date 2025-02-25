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
            $table->string('meeting_title');
            $table->string('meeting_type');
            $table->string('meeting_platform');
            $table->string('meeting_link');
            $table->string('meeting_location');
            $table->date('meeting_start_time');
            $table->time('meeting_end_time');
            $table->timestamp('create_date_time')->useCurrent();
            $table->timestamp('update_date_time')->useCurrentOnUpdate();
            $table->string('meeting_status',20);
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->index('tutor_id');
            $table->index('student_id');
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
