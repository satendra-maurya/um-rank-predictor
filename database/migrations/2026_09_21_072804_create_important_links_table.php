<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('important_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('set null');
            $table->foreignId('exam_cycle_id')->nullable()->constrained('exam_cycles')->onDelete('set null');
            $table->foreignId('exam_stage_id')->nullable()->constrained('exam_stages')->onDelete('set null');
            $table->string('title', 191);
            $table->string('url', 500);
            $table->string('link_type', 50)->nullable()->index();
            $table->boolean('is_external')->default(true);
            $table->integer('sort_order')->default(0)->index();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();

            $table->index(['exam_id', 'status']);
            $table->index(['exam_cycle_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('important_links');
    }
};
