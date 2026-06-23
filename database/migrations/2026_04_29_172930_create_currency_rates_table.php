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
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
            $table->string('currency', 3);                  // e.g. 'USD'
            $table->decimal('rate_to_bdt', 10, 4);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->enum('status', ['active', 'old', 'closed', 'pending'])->default('active');
            $table->string('source', 100)->nullable();      // e.g. 'Bangladesh Bank', 'Manual'
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('previous_rate', 10, 4)->nullable();
            $table->decimal('change_percent', 8, 4)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['currency', 'status']);
            $table->index(['currency', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
