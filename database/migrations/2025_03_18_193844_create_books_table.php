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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->foreignId('author_id');
            $table->foreignId('publisher_id');
            $table->foreignId('category_id');
            $table->date('checked_out_date')->nullable()->index();
            $table->decimal('average_rating', total: 3, places: 2)->default(0);
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('page_count')->nullable();
            $table->timestamps();
            $table->unique(['author_id', 'title']); // also acts as a compound index, which also acts as an index for author_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
