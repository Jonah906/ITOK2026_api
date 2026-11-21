<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('type')->default('text')->after('approved'); // 'text' | 'video'
            $table->string('video_path')->nullable()->after('type');
            $table->text('message')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['type', 'video_path']);
        });
    }
};
