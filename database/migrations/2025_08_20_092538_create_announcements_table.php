<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            // المحتوى
            $table->string('title', 255);
            $table->text('body');

            // النشر والفترة
            $table->timestamp('published_at')->nullable();
            $table->date('expires_at')->nullable();

            // الجمهور المستهدف
            $table->string('audience', 20)->nullable(); // القيم: الكل/طلاب/معلمون/موظفون

            // وسائط اختيارية: صورة أو فيديو قصير
            $table->enum('media_type', ['image','video'])->nullable();
            $table->string('media_path', 2048)->nullable();

            // من أنشأ الإعلان
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // فهارس مساعدة
            $table->index(['published_at']);
            $table->index(['audience']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
