<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wedding_settings', function (Blueprint $table) {
            $table->string('story_video')->nullable()->after('our_story');
            $table->json('gallery_videos')->nullable()->after('gallery');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_settings', function (Blueprint $table) {
            $table->dropColumn(['story_video', 'gallery_videos']);
        });
    }
};
