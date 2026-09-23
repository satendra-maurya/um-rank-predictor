<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consent_purpose_id')->constrained('consent_purposes')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_token', 100)->nullable();
            $table->unsignedTinyInteger('consent_status')->default(1);
            $table->timestamp('consented_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->string('consent_version', 20)->default('1.0');
            $table->string('notice_version', 20)->default('1.0');
            $table->string('privacy_policy_version', 20)->default('1.0');
            $table->string('source', 100)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['consent_purpose_id', 'consent_status']);
            $table->index(['user_id', 'consent_purpose_id']);
            $table->index(['session_token', 'consent_purpose_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_records');
    }
};
