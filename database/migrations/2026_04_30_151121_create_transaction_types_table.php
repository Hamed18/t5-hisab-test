<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();                // e.g. 'in', 'ex'
            $table->string('label');                         // e.g. 'Income', 'Expense'
            $table->enum('effect', ['add', 'subtract']);     // balance direction
            $table->boolean('transfer')->default(false);     // is it a transfer?
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};
