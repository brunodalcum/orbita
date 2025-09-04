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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // super_admin, admin, funcionario, licenciado
            $table->string('display_name'); // Super Admin, Admin, Funcionário, Licenciado
            $table->text('description')->nullable();
            $table->integer('level')->default(1); // 1=Super Admin, 2=Admin, 3=Funcionário, 4=Licenciado
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
