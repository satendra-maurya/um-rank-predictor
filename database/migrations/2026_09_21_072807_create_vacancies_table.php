<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_cycle_id')->constrained('exam_cycles')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('restrict');
            $table->string('post_name', 191)->nullable();
            $table->unsignedInteger('total_vacancies')->default(0);
            $table->timestamps();

            $table->index(['exam_cycle_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
