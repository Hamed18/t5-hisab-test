<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\FixedCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryReportController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->default_business_id;

        // Default to current year
        $year = $request->input('year', date('Y'));

        // Fetch salary transactions for the year, grouped by description (person) and month
        $salaries = Transaction::where('business_id', $businessId)
            ->where('type', 'salary')               // <-- dynamic type slug
            ->whereYear('date', $year)
            ->select(
                'description',
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(bdt_amount) as total')
            )
            ->groupBy('description', 'month')
            ->get();

        // Get all unique person names from these transactions
        $people = $salaries->pluck('description')->unique()->sort()->values();

        // Build a structured array: [person => [month => total]]
        $data = [];
        foreach ($people as $person) {
            $data[$person] = array_fill(1, 12, 0);
        }
        foreach ($salaries as $row) {
            $data[$row->description][$row->month] = $row->total;
        }

        // Expected amounts from Fixed Costs (Active items with the same name as the person)
        $expected = FixedCost::where('business_id', $businessId)
            ->where('status', 'Active')
            ->whereIn('item', $people)
            ->select('item', DB::raw('SUM(bdt_amount) as expected'))
            ->groupBy('item')
            ->pluck('expected', 'item');

        return view('salary_report.index', compact('year', 'data', 'people', 'expected'));
    }
}
