<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    private function currentcontactId()
    {
        return Auth::user()->default_contact_id;
    }

    private function currentBusinessId()
    {
        return Auth::user()->default_business_id;
    }

    public function index(Request $request)
    {
        $businessId = $this->currentBusinessId();
        $query = Contact::forBusiness($businessId);   // 👈 use forBusiness, not forcontact

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $contacts = $query->orderBy('name')->paginate(20)->appends($request->query());

        $types = Contact::forBusiness($businessId)->select('type')->distinct()->pluck('type');  // 👈 fix here too

        $filters = [
            [
                'name'    => 'type',
                'label'   => 'Type',
                'type'    => 'select',
                'options' => $types->mapWithKeys(fn($t) => [$t => ucfirst($t)])->toArray(),
            ],
        ];

        return view('contacts.index', compact('contacts', 'filters'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        // Release session lock so concurrent requests don't block
        session_write_close();

        if ($request->ajax() || $request->wantsJson()) {
            // ---------- AJAX / inline creation ----------
            $validated = $request->validate([
                'name'    => 'required|string|max:255',
                'type'    => 'sometimes|in:client,customer,vendor,employee,other',
                'company' => 'nullable|string|max:255',
                'email'   => 'nullable|email|max:255',
                'phone'   => 'nullable|string|max:20',
            ]);

            $validated['type']        = $validated['type'] ?? 'client';
            $validated['business_id'] = $this->currentBusinessId();

            $contact = Contact::create($validated);

            return response()->json([
                'id'   => $contact->id,
                'name' => $contact->name,
            ]);
        }

        // ---------- Normal form submission (page reload) ----------
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'type'    => 'required|in:client,customer,vendor,employee,other',
            'company' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'image'   => 'nullable|image|max:2048',
        ]);

        $validated['business_id'] = $this->currentBusinessId();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('contacts', 'public');
        }

        $contact = Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact created.');
    }

    public function edit(Contact $contact)
    {
        if ($contact->contact_id !== $this->currentcontactId()) {
            abort(403);
        }
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        if ($contact->contact_id !== $this->currentcontactId()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'type'    => 'required|in:client,customer,vendor,employee,other',
            'company' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'image'   => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($contact->image && Storage::disk('public')->exists($contact->image)) {
                Storage::disk('public')->delete($contact->image);
            }
            $validated['image'] = $request->file('image')->store('images', 'public');
        }

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        if ($contact->contact_id !== $this->currentcontactId()) {
            abort(403);
        }

        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact deleted.');
    }
}
