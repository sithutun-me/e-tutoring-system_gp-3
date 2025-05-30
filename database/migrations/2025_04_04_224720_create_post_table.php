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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('post_title',255);
            $table->text('post_description',500)->nullable();
            $table->string('post_status',20);
            $table->foreignId('post_create_by')->constrained('users', 'id')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->foreignId('post_received_by')->constrained('users', 'id')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->boolean('is_meeting')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
