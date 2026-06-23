<?php

namespace App\Http\Controllers;

use App\Models\FixedCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FixedCostController extends Controller
{
    private function currentBusinessId()
    {
        return Auth::user()->default_business_id;
    }

    public function index(Request $request)
    {
        $businessId = $this->currentBusinessId();
        $query = FixedCost::forBusiness($businessId);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting – Active first, then by effective date descending
        $query->orderByRaw("FIELD(status, 'Active', 'Paused', 'Old')")
              ->orderBy('effective_from', 'desc');

        $fixedCosts = $query->paginate(20)->appends($request->query());

        // Build unified $filters array for the reusable filter partial
        $statuses = FixedCost::forBusiness($businessId)
                    ->select('status')
                    ->distinct()
                    ->pluck('status');

        $filters = [
            [
                'name'    => 'status',
                'label'   => 'Status',
                'type'    => 'select',
                'options' => $statuses->mapWithKeys(fn($st) => [$st => $st])->toArray(),
            ],
        ];

        return view('fixed_costs.index', compact('fixedCosts', 'filters'));
    }

    public function create()
    {
        return view('fixed_costs.create');
    }

    public function store(Request $request)
    {
        $businessId = $this->currentBusinessId();

        $validated = $request->validate([
            'item'           => 'required|string|max:255',
            'type'           => 'nullable|string|max:50',
            'frequency'      => 'nullable|string|max:50',
            'amount'         => 'required|numeric|min:0',
            'currency'       => 'required|string|size:3',
            'bdt_amount'     => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to'   => 'nullable|date|after_or_equal:effective_from',
            'ask_day'        => 'nullable|integer|min:1|max:31',
            'status'         => 'required|in:Active,Old,Paused',
            'notes'          => 'nullable|string',
        ]);

        // Automatically calculate BDT if currency ≠ BDT and no manual BDT provided
        if ($validated['currency'] !== 'BDT' && empty($validated['bdt_amount'])) {
            $rate = \App\Models\CurrencyRate::where('currency', $validated['currency'])
                ->active()
                ->effectiveOn($validated['effective_from'])
                ->first();
            if ($rate) {
                $validated['bdt_amount'] = round($validated['amount'] * $rate->rate_to_bdt, 2);
            }
        } elseif ($validated['currency'] === 'BDT' && empty($validated['bdt_amount'])) {
            $validated['bdt_amount'] = $validated['amount'];
        }

        $validated['business_id'] = $businessId;

        FixedCost::create($validated);

        return redirect()->route('fixed-costs.index')->with('success', 'Fixed cost created.');
    }

    public function edit(FixedCost $fixedCost)
    {
        if ($fixedCost->business_id !== $this->currentBusinessId()) abort(403);
        return view('fixed_costs.edit', compact('fixedCost'));
    }

    public function update(Request $request, FixedCost $fixedCost)
    {
        if ($fixedCost->business_id !== $this->currentBusinessId()) abort(403);

        $validated = $request->validate([
            'item'           => 'required|string|max:255',
            'type'           => 'nullable|string|max:50',
            'frequency'      => 'nullable|string|max:50',
            'amount'         => 'required|numeric|min:0',
            'currency'       => 'required|string|size:3',
            'bdt_amount'     => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to'   => 'nullable|date|after_or_equal:effective_from',
            'ask_day'        => 'nullable|integer|min:1|max:31',
            'status'         => 'required|in:Active,Old,Paused',
            'notes'          => 'nullable|string',
        ]);

        // Recalculate BDT if currency changed or manual override cleared
        if ($validated['currency'] !== 'BDT' && empty($validated['bdt_amount'])) {
            $rate = \App\Models\CurrencyRate::where('currency', $validated['currency'])
                ->active()
                ->effectiveOn($validated['effective_from'])
                ->first();
            if ($rate) {
                $validated['bdt_amount'] = round($validated['amount'] * $rate->rate_to_bdt, 2);
            }
        } elseif ($validated['currency'] === 'BDT' && empty($validated['bdt_amount'])) {
            $validated['bdt_amount'] = $validated['amount'];
        }

        $fixedCost->update($validated);

        return redirect()->route('fixed-costs.index')->with('success', 'Fixed cost updated.');
    }

    public function destroy(FixedCost $fixedCost)
    {
        if ($fixedCost->business_id !== $this->currentBusinessId()) abort(403);
        $fixedCost->delete();
        return redirect()->route('fixed-costs.index')->with('success', 'Fixed cost deleted.');
    }
}
