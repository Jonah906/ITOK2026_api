<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->string('access_code')->nullable()->unique()->after('stream_token');
            $table->text('qr_code')->nullable()->after('access_code'); // base64 SVG/PNG
            $table->boolean('scanned')->default(false)->after('qr_code');
            $table->timestamp('scanned_at')->nullable()->after('scanned');
        });
    }

    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropColumn(['access_code', 'qr_code', 'scanned', 'scanned_at']);
        });
    }
};
