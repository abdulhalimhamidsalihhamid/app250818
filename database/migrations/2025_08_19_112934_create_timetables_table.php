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
Schema::create('timetables', function (Blueprint $table) {
    $table->id();
    $table->string('day');                    // اليوم (الأحد ..الخ)
    $table->string('specialization');         // أدبي أو علمي
    $table->enum('grade', ['الأولى','الثانية','الثالثة']); // الصف الدراسي
    $table->string('period1')->nullable();
    $table->string('period2')->nullable();
    $table->string('period3')->nullable();
    $table->string('period4')->nullable();
    $table->string('period5')->nullable();
    $table->string('period6')->nullable();
    $table->string('period7')->nullable();
    $table->timestamps();

    // منع التكرار لنفس اليوم + التخصص + الصف
    $table->unique(['day','specialization','grade']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
