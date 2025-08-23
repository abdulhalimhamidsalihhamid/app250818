<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('national_id', 32)->unique();
            $table->string('email', 255)->nullable()->index();
            $table->string('phone', 50)->nullable();

            $table->string('job_title', 120)->nullable();
            $table->string('department', 120)->nullable(); // مثال: إداري/تعليمي/تقني
            $table->date('hire_date')->nullable();

            $table->decimal('salary', 10, 2)->nullable();
            $table->string('status', 20)->default('نشط');  // نشط/موقوف/منتهٍ

            $table->text('address')->nullable();
            $table->string('avatar_path', 2048)->nullable(); // صورة الموظف (اختياري)

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['department']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
