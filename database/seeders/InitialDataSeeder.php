<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;
use App\Models\Account;
use App\Models\Category;
use App\Models\CurrencyRate;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Owner user – create only if missing
        $user = User::firstOrCreate(
            ['email' => 'akib@example.com'],
            [
                'name' => 'Abdul Kadher Akib',
                'password' => bcrypt('password'),
                'locale' => 'en',
                'timezone' => 'Asia/Dhaka',
                'is_active' => true,
            ]
        );

        // 2. Businesses – use firstOrCreate based on unique slug
        $top5way = Business::firstOrCreate(
            ['slug' => 'top5way'],
            [
                'owner_user_id' => $user->id,
                'name' => 'Top5Way',
                'type' => 'service',
                'currency' => 'BDT',
                'is_active' => true,
            ]
        );

        $personal = Business::firstOrCreate(
            ['slug' => 'personal'],
            [
                'owner_user_id' => $user->id,
                'name' => 'Personal',
                'type' => 'personal',
                'currency' => 'BDT',
                'is_active' => true,
            ]
        );

        // 3. Attach user to businesses with roles (sync to avoid duplicates)
        $user->businesses()->syncWithoutDetaching([
            $top5way->id => ['role' => 'owner'],
            $personal->id => ['role' => 'owner'],
        ]);
        $user->default_business_id = $top5way->id;
        $user->save();

        // 4. Accounts – use firstOrCreate with unique combination (name + business_id)
        $t5Accounts = [
            ['business_id' => $top5way->id, 'name' => 'Brac Business', 'type' => 'bank', 'currency' => 'BDT'],
            ['business_id' => $top5way->id, 'name' => 'Payoneer', 'type' => 'bank', 'currency' => 'USD'],
            ['business_id' => $top5way->id, 'name' => 'Fiverr', 'type' => 'bank', 'currency' => 'USD'],
        ];
        $personalAccounts = [
            ['business_id' => $personal->id, 'name' => 'IBBL', 'type' => 'bank', 'currency' => 'BDT'],
            ['business_id' => $personal->id, 'name' => 'bKash', 'type' => 'mobile_wallet', 'currency' => 'BDT'],
            ['business_id' => $personal->id, 'name' => 'Cash', 'type' => 'cash', 'currency' => 'BDT'],
        ];

        if (Account::where('business_id', $top5way->id)->count() === 0) {
            foreach (array_merge($t5Accounts, $personalAccounts) as $acc) {
                Account::firstOrCreate(
                    ['business_id' => $acc['business_id'], 'name' => $acc['name']],
                    array_merge($acc, ['is_active' => true, 'current_balance' => 0, 'opening_balance' => 0])
                );
            }
        }

        // 5. Categories – firstOrCreate by name + business_id
        $t5Categories = [
            ['business_id' => $top5way->id, 'name' => 'T5', 'type' => 'income'],
            ['business_id' => $top5way->id, 'name' => 'CMO', 'type' => 'income'],
            ['business_id' => $top5way->id, 'name' => 'Freelance', 'type' => 'income'],
            ['business_id' => $top5way->id, 'name' => 'Office Rent', 'type' => 'expense'],
            ['business_id' => $top5way->id, 'name' => 'Marketing', 'type' => 'expense'],
            ['business_id' => $top5way->id, 'name' => 'Salary', 'type' => 'expense'],
        ];
        $personalCategories = [
            ['business_id' => $personal->id, 'name' => 'Salary', 'type' => 'income'],
            ['business_id' => $personal->id, 'name' => 'Family', 'type' => 'expense'],
            ['business_id' => $personal->id, 'name' => 'Groceries', 'type' => 'expense'],
        ];

        if (Category::where('business_id', $top5way->id)->count() === 0) {
            foreach (array_merge($t5Categories, $personalCategories) as $cat) {
                Category::firstOrCreate(
                    ['business_id' => $cat['business_id'], 'name' => $cat['name']],
                    $cat
                );
            }
        }

        // 6. Currency Rate – firstOrCreate by currency and status
        CurrencyRate::firstOrCreate(
            ['currency' => 'USD', 'status' => 'active'],
            [
                'rate_to_bdt' => 122.5000,
                'effective_from' => now()->subDays(1),
                'source' => 'Manual',
                'changed_by_user_id' => $user->id,
            ]
        );
    }
}
