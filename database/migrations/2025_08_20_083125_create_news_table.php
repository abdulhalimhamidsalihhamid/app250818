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
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // بيانات الخبر
            $table->string('title', 255);                 // عنوان الخبر
            $table->timestamp('published_at')->nullable(); // تاريخ النشر
            $table->string('excerpt', 500)->nullable();    // ملخص قصير (اختياري)
            $table->text('body');                          // نص الخبر

            // تصنيف بسيط (اختياري)
            $table->string('category', 50)->nullable();    // عام/طلاب/معلمون/إعلانات

            // وسائط (اختياري): صورة أو فيديو قصير
            $table->enum('media_type', ['image','video'])->nullable(); // نوع الوسائط
            $table->string('media_path', 2048)->nullable();            // مسار الملف في storage

            // من أنشأ الخبر (اختياري)
            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
