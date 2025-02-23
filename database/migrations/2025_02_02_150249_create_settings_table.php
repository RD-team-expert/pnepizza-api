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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_title');
            $table->string('keywords');
            $table->text('description');
            $table->string('Google_Maps_API_Key');
            $table->string('Google_Analytics_ID');
            $table->string('logo_image');
            $table->string('instagram_url');
            $table->string('facebook_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
