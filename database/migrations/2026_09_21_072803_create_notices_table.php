<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('set null');
            $table->foreignId('exam_cycle_id')->nullable()->constrained('exam_cycles')->onDelete('set null');
            $table->foreignId('exam_stage_id')->nullable()->constrained('exam_stages')->onDelete('set null');
            $table->string('title', 255);
            $table->string('slug', 191)->nullable();
            $table->string('notice_type', 50)->nullable()->index();
            $table->date('notice_date')->nullable();
            $table->string('official_url', 500)->nullable();
            $table->string('attachment_url', 500)->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_important')->default(false)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();

            $table->index(['exam_id', 'status']);
            $table->index(['exam_cycle_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
