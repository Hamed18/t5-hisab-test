<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('branch', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        $businesses = $query->orderBy('name')->paginate(20)->appends($request->query());

        // Build filters
        $types = Business::select('type')->distinct()->pluck('type');
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

        return view('businesses.index', compact('businesses', 'filters'));
    }

    public function create()
    {
        return view('businesses.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Inline creation – only name is needed, rest gets defaults
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:businesses,name',
                'is_primary' => 'sometimes|boolean'
            ]);

            $validated['slug']         = Str::slug($validated['name']);
            $validated['type']         = 'service';            // default
            $validated['owner_user_id'] = auth()->id();
            $validated['is_active']    = true;

            $business = Business::create($validated);

            // Attach the creator as owner in the pivot
            auth()->user()->businesses()->attach($business->id, ['role' => 'owner']);

            return response()->json([
                'id'   => $business->id,
                'name' => $business->name,
            ]);
        }

        // ---------- Normal form submission (page reload) ----------
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:businesses,slug',
            'type'        => 'required|in:service,product,hybrid,personal,investment',
            'description' => 'nullable|string',
            'branch'      => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'is_active'   => 'boolean',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            $count = Business::where('slug', $validated['slug'])->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        $validated['owner_user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $business = Business::create($validated);
        auth()->user()->businesses()->attach($business->id, ['role' => 'owner']);

        return redirect()->route('businesses.index')->with('success', 'Business created.');
    }

    public function edit(Business $business)
    {
        // Log::info('from edit businesscon...', $request->all());

        return view('businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        // Log::info('from update businesscon...', $request->all());

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:businesses,name,'.$business->id,
            'slug'        => 'nullable|string|max:255|unique:businesses,slug,'.$business->id,
            'type'        => 'required|in:service,product,hybrid,personal,investment',
            'description' => 'nullable|string',
            'branch'      => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'is_active'   => 'boolean',
            'is_primary'  => 'sometimes|boolean',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            if (Business::where('slug', $validated['slug'])->where('id', '!=', $business->id)->exists()) {
                $validated['slug'] .= '-' . ($business->id);
            }
        }

        if ($request->hasFile('logo')) {
            if ($business->logo && Storage::disk('public')->exists($business->logo)) {
                Storage::disk('public')->delete($business->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $business->update($validated);

        return redirect()->route('businesses.index')->with('success', 'Business updated.');
    }

    public function destroy(Business $business)
    {
        if ($business->transactions()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete business with transactions.']);
        }

        $business->delete();
        return redirect()->route('businesses.index')->with('success', 'Business deleted.');
    }

    public function checkSlug(Request $request)
    {
        $slug = $request->get('slug');
        $excludeId = $request->get('exclude_id');  

        $query = Business::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkName(Request $request)
    {
        $name = $request->get('name');
        $excludeId = $request->get('exclude_id');

        $query = Business::where('name', $name);
        if ($excludeId) {

            $query->where('id', '!=', $excludeId);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
