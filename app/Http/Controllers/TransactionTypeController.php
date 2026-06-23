<?php

namespace App\Http\Controllers;

use App\Models\TransactionType;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    public function index()
    {
        $filters = [
            [
                'name' => 'effect',
                'label' => 'Effect',
                'type' => 'select',
                'options' => [
                    'add' => 'Add',
                    'subtract' => 'Subtract',
                ],
            ],
        ];

        $query = TransactionType::query();

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('slug', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%");
        }
        if (request()->filled('effect')) {
            $query->where('effect', request('effect'));
        }

        $types = $query->orderBy('slug')->paginate(20)->appends(request()->query());

        return view('transaction_types.index', compact('types', 'filters'));
    }

    public function create()
    {
        return view('transaction_types.create');
    }

    public function store(Request $request)
    {
        // Release session lock so concurrent requests don't block
        session_write_close();

        if ($request->ajax() || $request->wantsJson()) {
            // Validate the inline creation data
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'effect'   => 'sometimes|in:add,subtract',
                'transfer' => 'sometimes|boolean',
            ]);

            $label = $validated['name'];
            $slug  = \Illuminate\Support\Str::slug($label);

            // Ensure unique slug
            $count = TransactionType::where('slug', $slug)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }

            $type = TransactionType::create([
                'slug'      => $slug,
                'label'     => $label,
                'effect'    => $validated['effect'] ?? 'add',
                'transfer'  => $request->boolean('transfer', false),
                'is_active' => true,
            ]);

            // Return the new type's slug and label so the select can use it
            return response()->json([
                'id'   => $type->slug,    // the value used in the transaction form (the slug)
                'name' => $type->label,   // displayed label
                'effect' => $type->effect,
                'transfer' => $type->transfer,
            ]);
        }

        // --- Normal form submission (page reload) ---
        $validated = $request->validate([
            'slug'     => 'required|alpha_dash|unique:transaction_types,slug',
            'label'    => 'required|string|max:255',
            'effect'   => 'required|in:add,subtract',
            'transfer' => 'boolean',
            'is_active' => 'boolean',
        ]);

        TransactionType::create($validated);

        return redirect()->route('transaction-types.index')
            ->with('success', 'Transaction type created.');
    }

    public function edit(TransactionType $transactionType)
    {
        return view('transaction_types.edit', compact('transactionType'));
    }

    public function update(Request $request, TransactionType $transactionType)
    {
        $validated = $request->validate([
            'slug' => 'required|alpha_dash|unique:transaction_types,slug,'.$transactionType->id,
            'label' => 'required|string|max:255',
            'effect' => 'required|in:add,subtract',
            'transfer' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $transactionType->update($validated);

        return redirect()->route('transaction-types.index')
            ->with('success', 'Transaction type updated.');
    }

    public function destroy(TransactionType $transactionType)
    {
        // Prevent deletion if any transaction uses this type
        $count = \App\Models\Transaction::where('type', $transactionType->slug)->count();
        if ($count > 0) {
            return back()->withErrors('Cannot delete type that is used by '.$count.' transactions.');
        }

        $transactionType->delete();
        return redirect()->route('transaction-types.index')
            ->with('success', 'Transaction type deleted.');
    }
}
