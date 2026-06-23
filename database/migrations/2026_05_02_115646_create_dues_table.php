<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->unsignedBigInteger('contact_id');          // client / vendor
            $table->string('invoice_number', 50)->nullable();                 // INV-001
            $table->string('description', 500)->nullable();                  // purpose
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining', 15, 2)->storedAs('total_amount - paid_amount'); // virtual computed
            $table->string('currency', 3)->default('BDT');
            $table->enum('type', ['receivable', 'payable'])->default('receivable');
            $table->date('due_date');
            $table->date('last_payment_date')->nullable();
            $table->decimal('last_payment_amount', 15, 2)->nullable();
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue', 'written_off'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'critical'])->default('normal');
            $table->text('notes')->nullable();
            $table->text('follow_up')->nullable();           // call log / WhatsApp message
            $table->integer('reminder_count')->default(0);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('business_id');
            $table->index('status');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dues');
    }
};
