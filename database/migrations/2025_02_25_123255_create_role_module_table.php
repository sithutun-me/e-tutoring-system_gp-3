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
        Schema::create('role_module', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD:database/migrations/2025_02_25_123255_create_role_module_table.php
            $table->foreignId('role_id')->constrained('roles')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->foreignId('module_id')->constrained('modules')->onDelete('NO ACTION')->onUpdate('CASCADE');
=======
            $table->string('role_name')->unique();
>>>>>>> origin/dev_stt:database/migrations/0001_01_01_000003_create_roles_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_module');
    }
};
