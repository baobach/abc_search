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
        Schema::create('abc_files', function (Blueprint $table) { // Table name: abc_files
            $table->id(); // Auto-incrementing primary key

            // Columns based on your CSV
            $table->string('mxl_path')->nullable(); // Stores the original path from the 'mxl' column
            $table->string('abc_filename'); // Stores the filename from the 'abc' column
            $table->string('title')->nullable();
            $table->text('composer_name')->nullable(); // Changed from string to text
            $table->integer('n_tracks')->nullable();
            $table->text('score')->nullable(); // Using text as the example value is complex
            $table->text('tracks_y')->nullable(); // Using text for potentially long/complex track info
            $table->string('original_key')->nullable(); // Renamed from 'ori_key' for clarity
            $table->integer('song_length_bars')->nullable(); // Renamed from 'song_length.bars'
            $table->integer('n_notes')->nullable();

            // Optional: Columns for storing paths managed by Laravel Storage
            $table->string('stored_abc_path')->nullable()->unique(); // Path where the .abc file is stored by Laravel
            $table->string('stored_mxl_path')->nullable()->unique(); // Path where the .mxl file is stored by Laravel

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abc_files');
    }
};
