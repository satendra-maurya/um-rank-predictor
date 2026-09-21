<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_model_id')->constrained('prediction_models')->onDelete('restrict');
            $table->foreignId('exam_stage_id')->constrained('exam_stages')->onDelete('restrict');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('candidate_identifier', 191)->nullable();
            $table->unsignedSmallInteger('total_attempted')->nullable();
            $table->unsignedSmallInteger('correct_answers')->nullable();
            $table->unsignedSmallInteger('incorrect_answers')->nullable();
            $table->decimal('raw_score', 8, 2);
            $table->decimal('normalized_score', 8, 2)->nullable();
            $table->string('session_token', 100)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->string('device_fingerprint', 191)->nullable();
            $table->unsignedSmallInteger('risk_score')->default(0);
            $table->string('trust_status', 20)->default('PENDING_AUDIT')->index();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('ip_hash');
            $table->index('candidate_identifier');
            $table->index('raw_score');
            $table->index(['exam_stage_id', 'trust_status', 'raw_score'], 'cs_stage_trust_score_idx');
            // $table->index(['exam_stage_id', 'category_id', 'trust_status']);
            $table->index(['exam_stage_id', 'category_id', 'trust_status'], 'cs_stage_category_trust_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_submissions');
    }
};
