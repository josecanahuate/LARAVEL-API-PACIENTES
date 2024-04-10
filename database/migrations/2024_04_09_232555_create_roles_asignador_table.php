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
        Schema::create('roles_asignador', function (Blueprint $table) {
            /* $table->id(); */
            $table->unsignedBigInteger('user_id'); // Usuario que asigna el rol
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedBigInteger('role_id'); // Rol a ser asignado al usuario
            $table->string('estado')->default(1); // Estado del registro: 1 = Activo, 0 = Inactivo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_asignador');
    }
};
