<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    private function currentBusinessId()
    {
        return Auth::user()->default_business_id;
    }

    public function index(Request $request)
    {
        $businessId = $this->currentBusinessId();
        $query = Loan::forBusiness($businessId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('person', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->orderBy('date', 'desc')->paginate(20)->appends($request->query());

        $filters = [
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'select',
                'options' => ['borrowed' => 'Borrowed', 'lent' => 'Lent'],
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => ['Active' => 'Active', 'Repaid' => 'Repaid', 'Written Off' => 'Written Off'],
            ],
        ];

        return view('loans.index', compact('loans', 'filters'));
    }

    public function create()
    {
        return view('loans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'          => 'required|in:borrowed,lent',
            'person'        => 'required|string|max:255',
            'date'          => 'required|date',
            'amount'        => 'required|numeric|min:0',
            'currency'      => 'required|string|size:3',
            'bdt_amount'    => 'nullable|numeric',
            'purpose'       => 'nullable|string',
            'due_date'      => 'nullable|date',
            'repaid_amount' => 'nullable|numeric|min:0',
            'status'        => 'required|in:Active,Repaid,Written Off',
            'notes'         => 'nullable|string',
        ]);

        // Auto-calculate BDT if not provided and currency is BDT
        if ($validated['currency'] === 'BDT' && empty($validated['bdt_amount'])) {
            $validated['bdt_amount'] = $validated['amount'];
        }
        $validated['repaid_amount'] = $validated['repaid_amount'] ?? 0;
        $validated['business_id'] = $this->currentBusinessId();

        Loan::create($validated);

        return redirect()->route('loans.index')->with('success', 'Loan created.');
    }

    public function edit(Loan $loan)
    {
        if ($loan->business_id !== $this->currentBusinessId()) abort(403);
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        if ($loan->business_id !== $this->currentBusinessId()) abort(403);

        $validated = $request->validate([
            'type'          => 'required|in:borrowed,lent',
            'person'        => 'required|string|max:255',
            'date'          => 'required|date',
            'amount'        => 'required|numeric|min:0',
            'currency'      => 'required|string|size:3',
            'bdt_amount'    => 'nullable|numeric',
            'purpose'       => 'nullable|string',
            'due_date'      => 'nullable|date',
            'repaid_amount' => 'nullable|numeric|min:0',
            'status'        => 'required|in:Active,Repaid,Written Off',
            'notes'         => 'nullable|string',
        ]);

        if ($validated['currency'] === 'BDT' && empty($validated['bdt_amount'])) {
            $validated['bdt_amount'] = $validated['amount'];
        }
        $validated['repaid_amount'] = $validated['repaid_amount'] ?? 0;

        $loan->update($validated);

        return redirect()->route('loans.index')->with('success', 'Loan updated.');
    }

    public function destroy(Loan $loan)
    {
        if ($loan->business_id !== $this->currentBusinessId()) abort(403);
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted.');
    }
}
