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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_title',255);
            $table->text('post_description');
            $table->date('post_status',20);
            $table->timestamp('post_create_date_time')->useCurrent();
            $table->timestamp('post_update_date_time')->useCurrentOnUpdate();
            $table->unsignedBigInteger('post_created_by' );
            $table->unsignedBigInteger('post_received_by' );
            $table->foreign('post_created_by')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->foreign('post_received_by')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
