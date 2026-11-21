<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wedding_settings', function (Blueprint $table) {
            $table->id();
            $table->string('bride_name')->default('Sarah');
            $table->string('groom_name')->default('James');
            $table->dateTime('wedding_date')->nullable();
            $table->string('venue')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('dress_code')->nullable();
            $table->text('our_story')->nullable();
            $table->string('stream_url')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('live')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_settings');
    }
};
