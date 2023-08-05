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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255); // required, max 255 characters
            $table->integer('release_year'); // required, integer
            $table->string('imdb_id', 255)->unique()->nullable(); // required, unique, max 255 characters
            $table->text('images')->nullable(); // nullable, text can hold more characters than string
            $table->text('metadata')->nullable(); // json metadata
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record');
    }
};
