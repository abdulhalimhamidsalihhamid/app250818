<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('news', function (Blueprint $table) {
        if (!Schema::hasColumn('news','excerpt')) {
            $table->string('excerpt', 500)->nullable()->after('published_at');
        }
        if (!Schema::hasColumn('news','category')) {
            $table->string('category', 50)->nullable()->after('excerpt');
        }
        if (!Schema::hasColumn('news','media_type')) {
            $table->enum('media_type', ['image','video'])->nullable()->after('body');
        }
        if (!Schema::hasColumn('news','media_path')) {
            $table->string('media_path', 2048)->nullable()->after('media_type');
        }
    });
}

public function down(): void
{
    Schema::table('news', function (Blueprint $table) {
        if (Schema::hasColumn('news','media_path')) $table->dropColumn('media_path');
        if (Schema::hasColumn('news','media_type')) $table->dropColumn('media_type');
        if (Schema::hasColumn('news','category'))   $table->dropColumn('category');
        if (Schema::hasColumn('news','excerpt'))    $table->dropColumn('excerpt');
    });
}

};
