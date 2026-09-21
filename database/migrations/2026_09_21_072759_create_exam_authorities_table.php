<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_authorities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('restrict');
            $table->string('name', 191);
            $table->string('short_name', 50);
            $table->string('slug', 191)->unique();
            $table->string('level', 20)->default('CENTRAL')->index();
            $table->string('website_url', 500)->nullable();
            $table->string('logo', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamps();

            $table->index(['state_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_authorities');
    }
};
