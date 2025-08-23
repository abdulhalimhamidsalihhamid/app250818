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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('student_name', 150);
        $table->string('email', 150)->nullable();
        $table->date('dob')->nullable();
        $table->string('national_id', 32)->unique();   // رقم وطني فريد
        $table->string('phone', 32)->nullable();
        $table->enum('gender', ['male','female'])->nullable();
        $table->string('department', 60)->nullable();   // science, math, it, literature
        $table->string('class_name', 60)->nullable();   // الصف/الشعبة
        $table->date('enrollment_date')->nullable();
        $table->string('blood_type', 8)->nullable();
        $table->text('address')->nullable();
        $table->string('guardian_name', 150)->nullable();
        $table->string('guardian_phone', 32)->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
