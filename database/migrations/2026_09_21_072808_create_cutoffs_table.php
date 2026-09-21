<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cutoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_stage_id')->constrained('exam_stages')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('restrict');
            $table->string('post_name', 191)->nullable();
            $table->decimal('cutoff_marks', 8, 2);
            $table->unsignedInteger('cutoff_rank')->nullable();
            $table->timestamps();

            $table->index(['exam_stage_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cutoffs');
    }
};
