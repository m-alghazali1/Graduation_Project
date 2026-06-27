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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('national_id')->unique();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();

            $table->enum('gender', ['male', 'female']);

            $table->foreignId('neighborhood_id')
                ->nullable()
                ->constrained('neighborhoods')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
