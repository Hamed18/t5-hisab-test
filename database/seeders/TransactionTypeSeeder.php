<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionType;

class TransactionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['slug' => 'in',          'label' => 'Income',               'effect' => 'add',      'transfer' => false],
            ['slug' => 'in-partial',  'label' => 'Income Partial',       'effect' => 'add',      'transfer' => false],
            ['slug' => 'ex',          'label' => 'Expense',              'effect' => 'subtract', 'transfer' => false],
            ['slug' => 'px',          'label' => 'Personal Expense',     'effect' => 'subtract', 'transfer' => false],
            ['slug' => 'pi',          'label' => 'Personal Income',      'effect' => 'add',      'transfer' => false],
            ['slug' => 'tr-in',       'label' => 'Transfer In',          'effect' => 'add',      'transfer' => true],
            ['slug' => 'tr-out',      'label' => 'Transfer Out',         'effect' => 'subtract', 'transfer' => true],
            ['slug' => 'loan-in',     'label' => 'Loan Received',        'effect' => 'add',      'transfer' => false],
            ['slug' => 'loan-out',    'label' => 'Loan Given',           'effect' => 'subtract', 'transfer' => false],
            ['slug' => 'adv-out',     'label' => 'Advance Paid',         'effect' => 'subtract', 'transfer' => false],
            ['slug' => 'deduct',      'label' => 'Deduction',            'effect' => 'subtract', 'transfer' => false],
            ['slug' => 'refund-in',   'label' => 'Refund Received',      'effect' => 'add',      'transfer' => false],
            ['slug' => 'refund-out',  'label' => 'Refund Given',         'effect' => 'subtract', 'transfer' => false],
        ];

        foreach ($types as $type) {
            TransactionType::firstOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
