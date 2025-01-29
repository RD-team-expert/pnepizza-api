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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->decimal('min_salary', 10, 2);
            $table->decimal('max_salary', 10, 2);
            $table->string('city');
            $table->string('state');
            $table->string('job_type');
            $table->text('job_description');
            $table->string('indeed_link')->nullable();
            $table->string('workstream_link')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
