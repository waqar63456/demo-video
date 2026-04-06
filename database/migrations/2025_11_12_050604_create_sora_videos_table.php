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
        Schema::create('sora_videos', function (Blueprint $table) {
            $table->id();
            $table->text('prompt')->nullable();
            $table->string('duration')->default('10');
            $table->string('resolution')->default('1080p');
            $table->string('status')->default('queued'); // queued | processing | complete | failed
            $table->text('video_url')->nullable();
            $table->text('user_id')->nullable();
            $table->string('openai_video_id')->nullable();
            $table->string('progress')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sora_videos');
    }
};
