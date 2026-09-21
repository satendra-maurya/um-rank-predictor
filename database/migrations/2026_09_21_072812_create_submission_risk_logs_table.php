<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_risk_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_submission_id')->constrained('candidate_submissions')->onDelete('cascade');
            $table->string('risk_factor', 100)->index();
            $table->unsignedSmallInteger('risk_weight')->default(10);
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['candidate_submission_id', 'risk_factor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_risk_logs');
    }
};
