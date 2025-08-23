<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('national_id', 32)->unique();
            $table->string('email', 255)->nullable()->index();
            $table->string('phone', 50)->nullable();

            $table->string('subject', 120)->nullable();       // المادة الأساسية
            $table->string('qualification', 120)->nullable(); // المؤهل: بكالوريوس/ماجستير...
            $table->string('department', 120)->nullable();    // القسم: علمي/أدبي/لغات...
            $table->string('grade_levels', 120)->nullable();  // المرحلة: الأولى/الثانية/الثالثة...

            $table->date('hire_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('status', 20)->default('نشط');     // نشط/موقوف/منتهٍ

            $table->text('address')->nullable();
            $table->string('avatar_path', 2048)->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['department']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
