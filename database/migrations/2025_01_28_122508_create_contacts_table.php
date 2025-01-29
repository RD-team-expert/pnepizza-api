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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name field
            $table->string('email'); // Email field
            $table->string('phone')->nullable(); // Phone field, nullable
            $table->text('message')->nullable(); // Message field, nullable
            $table->boolean('contact_via_email')->default(false); // Boolean field for email contact
            $table->boolean('contact_via_phone')->default(false); // Boolean field for phone contact
            $table->string('status')->default('pending'); // Status field with default value
            $table->string('priority')->default('medium'); // Priority field with default value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
