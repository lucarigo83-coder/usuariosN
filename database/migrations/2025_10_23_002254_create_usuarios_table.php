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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 100)->unique();
            $table->string('nombre_usuario', 50)->unique();
            $table->string('nip', 255);
            $table->boolean('sesion_activa')->default(false);
            $table->integer('intentos_fallidos')->default(0);
            $table->timestamp('ultimo_intento')->nullable();
            $table->boolean('bloqueado')->default(false);
            $table->timestamp('bloqueado_hasta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
