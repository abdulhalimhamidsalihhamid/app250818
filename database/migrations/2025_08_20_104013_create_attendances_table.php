<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // student | teacher | staff
            $table->enum('role', ['student','teacher','staff']);

            // المعرّف داخل جدول الدور (students / teachers / staff)
            $table->unsignedBigInteger('person_id');

            // تاريخ الحضور
            $table->date('date');

            // الفصل والسنة (اختياريان)
            $table->string('term', 20)->nullable(); // الفصل الأول | الفصل الثاني
            $table->integer('year')->nullable();

            // الحالة
            $table->enum('status', ['حاضر','غائب','متأخر','مأذون']);

            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // عدم التكرار لنفس الشخص في نفس اليوم
            $table->unique(['role','person_id','date']);
            $table->index(['role','date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
