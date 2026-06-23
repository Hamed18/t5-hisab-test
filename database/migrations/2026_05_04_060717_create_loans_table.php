<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->enum('type', ['borrowed', 'lent']);            // borrowed = আমি নিয়েছি, lent = আমি দিয়েছি
            $table->string('person', 255);                         // From/To whom
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('BDT');
            $table->decimal('bdt_amount', 15, 2)->nullable();
            $table->string('purpose')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('repaid_amount', 15, 2)->default(0);   // for borrowed: repaid, for lent: received back
            $table->enum('status', ['Active', 'Repaid', 'Written Off'])->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('business_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
