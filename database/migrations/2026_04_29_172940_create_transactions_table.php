<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable();
            $table->date('date');
            $table->string('type', 50);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            
            // 🆕 UPDATED: Ensuring type validation mapping support
            $table->string('category_type', 50)->nullable(); 
            $table->string('description', 500)->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('BDT');
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->decimal('bdt_amount', 15, 2)->nullable();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('related_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('contact_id')->nullable();
            $table->string('reference_type', 50)->nullable();
            $table->bigInteger('reference_id')->nullable();
            $table->string('receipt_path', 255)->nullable();
            $table->string('receipt_id', 100)->nullable();
            $table->boolean('has_receipt')->default(false);
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->foreignId('added_by_user_id')->constrained('users');
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'reconciled'])->default('approved');
            $table->boolean('is_recurring')->default(false);
            $table->bigInteger('parent_recurring_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'date']);
            $table->index(['business_id', 'type']);
            $table->index('account_id');
            $table->index(['reference_type', 'reference_id']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};