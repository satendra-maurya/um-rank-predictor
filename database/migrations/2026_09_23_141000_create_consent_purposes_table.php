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
        Schema::create('consent_purposes', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100);
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->string('version', 20)->default('1.0');
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('key');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_purposes');
    }
};
