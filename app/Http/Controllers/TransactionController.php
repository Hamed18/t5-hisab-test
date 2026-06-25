<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Business;
use App\Models\Category;
use App\Models\Contact;
use App\Models\CurrencyRate;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    private function currentBusinessId()
    {
        return Auth::user()->default_business_id;
    }

    public function index(Request $request)
{
    $query = Transaction::query();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhere('type', 'like', "%{$search}%")
              ->orWhere('receipt_id', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%");
        });
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active == '1');
    }

    $transactions = $query->orderBy('date', 'desc')  // Changed from 'name' to 'date'
        ->orderBy('id', 'desc')  // Secondary sort
        ->paginate(20)
        ->appends($request->query());

    // Build filters
    $types = Transaction::select('type')->distinct()->pluck('type');
    $filters = [
        [
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select',
            'options' => $types->mapWithKeys(fn($t) => [$t => ucfirst($t)])->toArray(),
        ],
        [
            'name' => 'is_active',
            'label' => 'Active',
            'type' => 'select',
            'options' => ['1' => 'Yes', '0' => 'No'],
        ],
    ];

    return view('transactions.index', compact('transactions', 'filters'));
}

    public function create()
    {
        $businessId = $this->currentBusinessId();

        $accounts = Account::active()->get();
        $categories = Category::where('business_id', $businessId)->active()->get();

        $activeRates = CurrencyRate::active()
            ->where(function ($q) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', now());
            })
            ->where('effective_from', '<=', now())
            ->get()
            ->keyBy('currency');

        $currencies = $activeRates->keys();
        $transactionTypes = TransactionType::active()->orderBy('label')->get();
        $contacts = Contact::where('business_id', $businessId)->orderBy('name')->get();

        $userBusinesses = Auth::user()->businesses()->orderBy('name')->get();
        if ($userBusinesses->isEmpty()) {
            $userBusinesses = collect([Business::find(Auth::user()->default_business_id)]);
        }

        $categoryTypes = [
            'income' => 'Income',
            'expense' => 'Expense',
            'asset' => 'Asset',
            'liability' => 'Liability'
        ];

        return view('transactions.create', compact(
            'accounts', 'categories', 'activeRates', 'currencies',
            'transactionTypes', 'contacts', 'userBusinesses', 'categoryTypes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'            => 'required|date',
            'type'            => 'required|string|max:50',
            'business_id'     => 'required|exists:businesses,id',
            'category_id'     => 'nullable|exists:categories,id',
            'category_custom' => 'required|in:xyz,abx,pqr', // ✅ Validating custom frontend dropdown selection
            'account_id'      => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:0',
            'currency'        => 'required|string|max:3',
            'exchange_rate'   => 'nullable|numeric|min:0',
            'description'     => 'nullable|string|max:500',
            'notes'           => 'nullable|string',
            'receipt_id'      => 'nullable|string|max:100',
            'receipt_file'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validated['currency'] !== 'BDT') {
            if (isset($validated['exchange_rate'])) {
                $validated['bdt_amount'] = round($validated['amount'] * $validated['exchange_rate'], 2);
            } else {
                $rate = CurrencyRate::where('currency', $validated['currency'])
                    ->active()
                    ->effectiveOn($validated['date'])
                    ->first();
                if ($rate) {
                    $validated['bdt_amount'] = round($validated['amount'] * $rate->rate_to_bdt, 2);
                    $validated['exchange_rate'] = $rate->rate_to_bdt;
                }
            }
        } else {
            $validated['bdt_amount'] = $validated['amount'];
            $validated['exchange_rate'] = 1;
        }

        $validated['added_by_user_id'] = Auth::id();
        $validated['status'] = 'approved';

$validated['type'] = $validated['category_custom'];
        $transaction = Transaction::create($validated);

        if ($request->hasFile('receipt_file')) {
            $path = $request->file('receipt_file')->store('receipts', 'public');
            $transaction->update([
                'receipt_path' => $path,
                'has_receipt'  => true,
                ]);
        }

        if ($request->filled('receipt_id')) {
            $transaction->update(['receipt_id' => $request->receipt_id]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction added successfully.');
    }

    public function edit(Transaction $transaction)
    {
        // Log::info('from accountcon...', $request->all());

        if ($transaction->business_id !== $this->currentBusinessId()) {
            abort(403);
        }

        $businessId = $this->currentBusinessId();
        
        $accounts = Account::active()->get();
        $categories = Category::where('business_id', $businessId)->active()->get();

        $activeRates = CurrencyRate::active()
            ->where(function ($q) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', now());
            })
            ->where('effective_from', '<=', now())
            ->get()
            ->keyBy('currency');

        $currencies = $activeRates->keys();
        $transactionTypes = TransactionType::active()->orderBy('label')->get();
        $contacts = Contact::where('business_id', $businessId)->orderBy('name')->get();

        $userBusinesses = Auth::user()->businesses()->orderBy('name')->get();
        if ($userBusinesses->isEmpty()) {
            $userBusinesses = collect([Business::find(Auth::user()->default_business_id)]);
        }

        $categoryTypes = [
            'income' => 'Income',
            'expense' => 'Expense',
            'asset' => 'Asset',
            'liability' => 'Liability'
        ];

        return view('transactions.edit', compact(
            'transaction', 'accounts', 'categories', 'activeRates', 'currencies',
            'transactionTypes', 'contacts', 'userBusinesses', 'categoryTypes'
        ));
    }

    public function update(Request $request, Transaction $transaction)
    { 
        // Log::info('from accountcon...', $request->all());

        if ($transaction->business_id !== $this->currentBusinessId()) {
            abort(403);
        }

        $validated = $request->validate([
            'business_id' => [
                'required', 'exists:businesses,id',
                function ($attribute, $value, $fail) {
                    $userBusinessIds = Auth::user()->businesses()->pluck('id')->toArray();
                    if (!in_array((int)$value, $userBusinessIds)) {
                        $fail('You do not have access to the selected business.');
                    }
                },
            ],
            'date' => 'required|date',
            'type' => 'required|in:'.TransactionType::active()->pluck('slug')->implode(','),
            'category_id' => 'nullable|exists:categories,id,business_id,'.$request->business_id,
            'category_custom' => 'required|in:xyz,abx,pqr', 
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'exchange_rate' => 'nullable|numeric',
            'bdt_amount' => 'nullable|numeric',
            'related_account_id' => 'nullable|exists:accounts,id',
            'receipt_id'   => 'nullable|string|max:100',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validated['currency'] !== 'BDT') {
            if (isset($validated['exchange_rate'])) {
                $validated['bdt_amount'] = round($validated['amount'] * $validated['exchange_rate'], 2);
            } else {
                $rate = CurrencyRate::where('currency', $validated['currency'])
                    ->active()
                    ->effectiveOn($validated['date'])
                    ->first();
                if ($rate) {
                    $validated['bdt_amount'] = round($validated['amount'] * $rate->rate_to_bdt, 2);
                    $validated['exchange_rate'] = $rate->rate_to_bdt;
                }
            }
        } else {
            $validated['bdt_amount'] = $validated['amount'];
            $validated['exchange_rate'] = 1;
        }

        // Map custom view selection cleanly to database column name framework during update
        $validated['category_type'] = $validated['category_custom'];

        $transaction->update($validated);

        if ($request->hasFile('receipt_file')) {
            if ($transaction->receipt_path && Storage::disk('public')->exists($transaction->receipt_path)) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $path = $request->file('receipt_file')->store('receipts', 'public');
            $transaction->update([
                'receipt_path' => $path,
                'has_receipt'  => true,
            ]);
        }

        if ($request->filled('receipt_id')) {
            $transaction->update(['receipt_id' => $request->receipt_id]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->business_id !== $this->currentBusinessId()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted.');
    }
}
