<?php

namespace App\Http\Controllers;

use App\Models\Due;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DueController extends Controller
{
    private function currentBusinessId()
    {
        return Auth::user()->default_business_id;
    }

    public function index(Request $request)
    {
        $businessId = $this->currentBusinessId();

        $query = Due::with('contact')->forBusiness($businessId);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('contact', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'due_date');
        $sortDir = $request->get('sort_dir', 'asc');
        if (in_array($sortBy, ['due_date', 'total_amount', 'paid_amount', 'status'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('due_date');
        }

        $dues = $query->paginate(20)->appends($request->query());

        // Build unified $filters array for the reusable filter partial
        $statuses = Due::where('business_id', $businessId)
            ->select('status')
            ->distinct()
            ->pluck('status');

        $filters = [
            [
                'name'    => 'status',
                'label'   => 'Status',
                'type'    => 'select',
                'options' => $statuses->mapWithKeys(fn($st) => [$st => ucfirst($st)])->toArray(),
            ],
            [
                'name'    => 'type',
                'label'   => 'Type',
                'type'    => 'select',
                'options' => ['receivable' => 'Receivable', 'payable' => 'Payable'],
            ],
        ];

        return view('dues.index', compact('dues', 'filters'));
    }

    public function create()
    {
        $contacts = Contact::where('business_id', $this->currentBusinessId())->get();
        return view('dues.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        $businessId = $this->currentBusinessId();

        $validated = $request->validate([
            'contact_id'        => 'required|exists:contacts,id',
            'invoice_number'    => 'nullable|string|max:50',
            'description'       => 'nullable|string|max:500',
            'total_amount'      => 'required|numeric|min:0',
            'paid_amount'       => 'nullable|numeric|min:0',
            'currency'          => 'required|string|size:3',
            'type'              => 'required|in:receivable,payable',
            'due_date'          => 'required|date',
            'last_payment_date' => 'nullable|date',
            'last_payment_amount'=> 'nullable|numeric|min:0',
            'status'            => 'nullable|in:pending,partial,paid,overdue,written_off',
            'priority'          => 'nullable|in:low,normal,high,critical',
            'notes'             => 'nullable|string',
            'follow_up'         => 'nullable|string',
        ]);

        $validated['business_id'] = $businessId;
        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;

        Due::create($validated);

        return redirect()->route('dues.index')->with('success', 'Due created.');
    }

    public function edit(Due $due)
    {
        if ($due->business_id !== $this->currentBusinessId()) abort(403);
        $contacts = Contact::where('business_id', $this->currentBusinessId())->get();
        return view('dues.edit', compact('due', 'contacts'));
    }

    public function update(Request $request, Due $due)
    {
        if ($due->business_id !== $this->currentBusinessId()) abort(403);

        $businessId = $this->currentBusinessId();

        $validated = $request->validate([
            'contact_id'        => 'required|exists:contacts,id',
            'invoice_number'    => 'nullable|string|max:50',
            'description'       => 'nullable|string|max:500',
            'total_amount'      => 'required|numeric|min:0',
            'paid_amount'       => 'nullable|numeric|min:0',
            'currency'          => 'required|string|size:3',
            'type'              => 'required|in:receivable,payable',
            'due_date'          => 'required|date',
            'last_payment_date' => 'nullable|date',
            'last_payment_amount'=> 'nullable|numeric|min:0',
            'status'            => 'nullable|in:pending,partial,paid,overdue,written_off',
            'priority'          => 'nullable|in:low,normal,high,critical',
            'notes'             => 'nullable|string',
            'follow_up'         => 'nullable|string',
        ]);

        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;

        $due->update($validated);

        return redirect()->route('dues.index')->with('success', 'Due updated.');
    }

    public function destroy(Due $due)
    {
        if ($due->business_id !== $this->currentBusinessId()) abort(403);
        $due->delete();
        return redirect()->route('dues.index')->with('success', 'Due deleted.');
    }
}
