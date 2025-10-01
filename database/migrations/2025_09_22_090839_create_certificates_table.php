<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique(); // الرقم المميز
            $table->string('student_name');
            $table->string('class_name')->nullable();
            $table->string('department')->nullable();
            $table->string('round_name')->nullable();
            $table->string('seat_no')->nullable();
            $table->string('academic_year');
            $table->string('grade_of_year');
            $table->string('general_remark')->nullable();
            $table->string('total_marks')->nullable();
            $table->string('percentage')->nullable();
            $table->date('issue_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('certificates');
    }
};
