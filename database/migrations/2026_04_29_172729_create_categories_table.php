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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['income', 'expense', 'both']);
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            // $table->string('icon', 50)->nullable();
            // $table->string('color', 7)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index(['business_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
