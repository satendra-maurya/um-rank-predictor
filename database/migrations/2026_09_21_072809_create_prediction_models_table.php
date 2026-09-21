<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediction_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_stage_id')->constrained('exam_stages')->onDelete('restrict');
            $table->string('name', 191);
            $table->string('version', 50)->default('v1.0');
            $table->decimal('total_marks', 8, 2)->default(200.00);
            $table->decimal('negative_marking_ratio', 4, 2)->nullable()->default(0.25);
            $table->json('formula_config')->nullable();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();

            $table->unique(['exam_stage_id', 'version']);
            $table->index(['exam_stage_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_models');
    }
};
