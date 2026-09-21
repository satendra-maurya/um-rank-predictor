<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_authority_id')->constrained('exam_authorities')->onDelete('restrict');
            $table->string('name', 191);
            $table->string('short_name', 50)->nullable();
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->string('exam_type', 50)->nullable();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();

            $table->index(['exam_authority_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
