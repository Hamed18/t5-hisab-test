<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();

            $table->string('item');                          // e.g. "Claude Pro", "ChatGPT Plus"
            $table->string('type')->nullable();              // Subscription, Bill, Marketing, etc.
            $table->string('frequency')->nullable();         // Monthly, Yearly, One-time

            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('BDT');
            $table->decimal('bdt_amount', 15, 2)->nullable(); // auto‑calculated or manual

            $table->date('effective_from');
            $table->date('effective_to')->nullable();        // blank = still active

            $table->unsignedTinyInteger('ask_day')->nullable(); // day of month for reminder (future bot)

            $table->enum('status', ['Active', 'Old', 'Paused'])->default('Active');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('business_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_costs');
    }
};
