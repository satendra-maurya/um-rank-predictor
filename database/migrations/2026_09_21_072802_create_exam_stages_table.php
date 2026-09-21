<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_cycle_id')->constrained('exam_cycles')->onDelete('restrict');
            $table->string('name', 100);
            $table->string('slug', 191)->nullable();
            $table->unsignedTinyInteger('stage_order')->default(1);
            $table->string('type', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();

            $table->unique(['exam_cycle_id', 'stage_order']);
            $table->index(['exam_cycle_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_stages');
    }
};
