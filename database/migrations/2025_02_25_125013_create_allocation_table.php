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
        Schema::create('allocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('staff_id');
            $table->timestamp('allocation_date_time');
            $table->boolean('active')->default(true);
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->index('student_id');
            $table->index('tutor_id');
            $table->index('staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation');
    }
};
