<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::orderBy('status')->orderBy('effective_from', 'desc')->paginate(20);
        return view('currency_rates.index', compact('rates'));
    }

    public function create()
    {
        return view('currency_rates.create');
    }

    public function store(Request $request)
    {
        // Release session lock so concurrent requests don't block
        session_write_close();

        $validated = $request->validate([
            'currency'        => 'required|string|size:3',
            'rate_to_bdt'     => 'required|numeric|min:0',
            'effective_from'  => 'required|date',
            'effective_to'    => 'nullable|date|after_or_equal:effective_from',
            'source'          => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
        ]);

        $validated['status'] = 'active';
        $validated['changed_by_user_id'] = auth()->id();

        $currency_rate = CurrencyRate::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['id' => $currency_rate->id, 'name' => $currency_rate->name]);
        }

        return redirect()->route('currency-rates.index')->with('success', 'Currency rate created.');
    }

    public function edit(CurrencyRate $currencyRate)
    {
        return view('currency_rates.edit', compact('currencyRate'));
    }

    public function update(Request $request, CurrencyRate $currencyRate)
    {
        $validated = $request->validate([
            'currency'        => 'required|string|size:3',
            'rate_to_bdt'     => 'required|numeric|min:0',
            'effective_from'  => 'required|date',
            'effective_to'    => 'nullable|date|after_or_equal:effective_from',
            'source'          => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
        ]);

        $currencyRate->update($validated);

        return redirect()->route('currency-rates.index')->with('success', 'Currency rate updated.');
    }

    public function destroy(CurrencyRate $currencyRate)
    {
        $currencyRate->delete();
        return redirect()->route('currency-rates.index')->with('success', 'Currency rate deleted.');
    }
}
