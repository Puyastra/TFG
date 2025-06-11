<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('historias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('sinopsis')->nullable();
            $table->string('portada')->nullable(); 
            $table->enum('estado', ['publicada', 'borrador'])->default('publicada');
            $table->timestamps();
        });
        Schema::create('capitulo_historias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historia_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('introduccion')->nullable();
            $table->longText('contenido');
            $table->timestamps();
        });
        Schema::create('capitulo_historia_comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('capitulo_historia_id')->constrained()->onDelete('cascade');
            $table->tinyText('comentario');
        });
        Schema::create('capitulo_historia_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('capitulo_historia_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('historia_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('capitulo_historia_comentario_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historias');
    }

};
