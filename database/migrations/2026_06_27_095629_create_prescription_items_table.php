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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();

            $table->string('dosage')->nullable();
            $table->integer('prescribed_quantity')->default(1);
            $table->string('instructions')->nullable(); // تعليمات الاستخدام (مثل: حبة 3 مرات يوميا بعد الأكل)
            $table->boolean('is_dispensed')->default(false); // حالة الصرف من الصيدلية
            $table->dateTime('dispensed_at')->nullable(); // تاريخ ووقت الصرف

            $table->foreignId('visit_id')
                ->constrained('visits')
                ->cascadeOnDelete();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
