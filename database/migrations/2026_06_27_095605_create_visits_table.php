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
       Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->dateTime('appointment_date');

            $table->string('blood_pressure')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();

            $table->text('doctor_notes')->nullable();

            $table->enum('status', [
                'waiting',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('waiting');

            $table->foreignId('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
