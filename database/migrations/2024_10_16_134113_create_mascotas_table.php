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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tipo')->constrained('tipos')->onUpdate('cascade')->onDelete('restrict');
            $table->string('raza',50);
            $table->string('nombre',50);
            $table->string('cuidados',50);
            $table->date('fecha_nacimiento');
            $table->decimal('precio',10);
            $table->string('foto',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
