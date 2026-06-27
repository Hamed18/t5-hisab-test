<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private function currentBusinessId()
    {
        return Auth::user()->default_business_id;
    }

    public function index()
    {
        $filters = [
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'select',
                'options' => [
                    'income' => 'Income',
                    'expense' => 'Expense',
                    'both' => 'Both',
                ],
            ],
        ];

        $query = Category::where('business_id', $this->currentBusinessId());

        if (request()->filled('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }
        if (request()->filled('type')) {
            $query->where('type', request('type'));
        }

        $categories = $query->with('parent')->orderBy('type')->orderBy('name')->paginate(20)->appends(request()->query());

        return view('categories.index', compact('categories', 'filters'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        // Release session lock so concurrent requests don't block
        session_write_close();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense,both',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_system'] = false;  // manually created categories are not system

        $category = Category::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['id' => $category->id, 'name' => $category->name]);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense,both',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        // Prevent deletion if used by transactions or has children
        if ($category->transactions()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete category that has transactions.']);
        }
        if ($category->children()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete category with sub-categories.']);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted.');
    }
}