<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediction_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_submission_id')->unique()->constrained('candidate_submissions')->onDelete('cascade');
            $table->unsignedInteger('predicted_rank_overall')->nullable()->index();
            $table->unsignedInteger('predicted_rank_category')->nullable()->index();
            $table->decimal('percentile', 5, 2)->nullable();
            $table->decimal('confidence_score', 4, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('calculated_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediction_results');
    }
};
