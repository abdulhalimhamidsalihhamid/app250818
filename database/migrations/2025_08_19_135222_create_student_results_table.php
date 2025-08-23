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
    Schema::create('student_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->string('subject', 150);
        $table->decimal('mark', 5, 2)->default(0);
        $table->string('term', 50)->default('الفصل الأول');
        $table->integer('year')->default(date('Y'));
        $table->string('specialization', 20); // علمي/أدبي للتتبع
        $table->timestamps();

        $table->unique(['student_id','subject','term','year']);
    });
}

public function down(): void
{
    Schema::dropIfExists('student_results');
}



};
