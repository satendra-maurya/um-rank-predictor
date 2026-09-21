<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('restrict');
            $table->unsignedSmallInteger('year');
            $table->string('title', 191)->nullable();
            $table->date('notification_date')->nullable();
            $table->date('application_start_date')->nullable();
            $table->date('application_end_date')->nullable();
            $table->date('exam_start_date')->nullable();
            $table->date('exam_end_date')->nullable();
            $table->date('result_date')->nullable();
            $table->string('status', 20)->default('DRAFT')->index();
            $table->timestamps();

            $table->unique(['exam_id', 'year']);
            $table->index(['exam_id', 'status']);
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_cycles');
    }
};
