<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->string('title',255);
            $table->date('date');
            $table->string('location',255)->nullable();
            $table->string('category',50)->nullable();
            $table->text('description');

            $table->enum('media_type',['image','video'])->nullable();
            $table->string('media_path',2048)->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['date']);
            $table->index(['category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
