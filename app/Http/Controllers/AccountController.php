<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function index()
    {
        $filters = [
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'select',
                'options' => [
                    'bank' => 'Bank',
                    'mobile_wallet' => 'Mobile Wallet',
                    'cash' => 'Cash',
                    'card' => 'Card',
                    'crypto' => 'Crypto',
                    'other' => 'Other',
                ],
            ],
        ];

        // Changed from where('business_id', ...) to a clean base query
        $query = Account::query();

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }
        if (request()->filled('type')) {
            $query->where('type', request('type'));
        }

        // Changed ordering from 'display_order' to 'id'
        $accounts = $query->orderBy('id', 'desc')->paginate(20)->appends(request()->query());

        return view('accounts.index', compact('accounts', 'filters'));
    }

    public function create()
    {
        // Removed business filtering because the column no longer exists
        $accounts = Account::all();

        return view('accounts.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        // Log::info('from accountcon...', $request->all());
    
        // Release session lock so concurrent requests don't block
        session_write_close();

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'nullable|in:bank,mobile_wallet,cash,card,crypto,other',
            'opening_balance' => 'nullable|numeric|min:0',
            'account_number'  => 'nullable|string|max:50',
            'bank_name'       => 'nullable|string|max:100',
            'branch_name'     => 'nullable|string|max:100',
            'is_active'       => 'boolean',
        ]);

        // Default 'is_active' to true if not passed explicitly in validation payload
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        // Cleaned up the creation block to prevent double record creation queries
        $account = Account::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['id' => $account->id, 'name' => $account->name]);
        }

        return redirect()->route('accounts.index')
            ->with('success', 'Account created.');
    }

    public function edit(Account $account)
    {
        // Removed business_id cross-checking restriction
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        // Removed business_id cross-checking restriction
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|in:bank,mobile_wallet,cash,card,crypto,other',
            'opening_balance' => 'required|numeric|min:0',
            'account_number'  => 'nullable|string|max:50',
            'bank_name'       => 'nullable|string|max:100',
            'branch_name'     => 'nullable|string|max:100',
            'is_active'       => 'boolean',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated.');
    }

    public function destroy(Account $account)
    {
        // Removed business_id cross-checking restriction
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted.');
    }
}