<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->default_business_id;

        // ---------- 1. Determine date range ----------
        $viewType = $request->input('view', 'monthly'); // monthly, yearly, range

        $year  = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $from = $request->input('from');
        $to   = $request->input('to');

        switch ($viewType) {
            case 'yearly':
                $from = "$year-01-01";
                $to   = "$year-12-31";
                break;
            case 'monthly':
                $from = "$year-$month-01";
                $to   = date('Y-m-t', strtotime($from)); // last day of month
                break;
            case 'range':
                $from = $from ?? date('Y-m-01');
                $to   = $to ?? date('Y-m-d');
                break;
            default:
                $from = date('Y-m-01');
                $to   = date('Y-m-d');
        }

        // ---------- 2. Target (optional) ----------
        $target = $request->input('target', 0);

        // ---------- 3. Base query for transactions in period ----------
        $baseQuery = Transaction::where('business_id', $businessId)
            ->whereBetween('date', [$from, $to]);

        // ---------- 4. Income (type in / in-partial) ----------
        $incomeQuery = (clone $baseQuery)->whereIn('type', ['in', 'in-partial']);
        $incomeItems = $incomeQuery
            ->selectRaw('description, category_id, SUM(bdt_amount) as total')
            ->with('category')
            ->groupBy('description', 'category_id')
            ->orderByDesc('total')
            ->get();

        $totalIncome = $incomeItems->sum('total');

        // ---------- 5. Business Expense (type ex) ----------
        $expenseQuery = (clone $baseQuery)->where('type', 'ex');
        $businessExpenseItems = $expenseQuery
            ->selectRaw('category_id, SUM(bdt_amount) as total')
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        $totalBusinessExpense = $businessExpenseItems->sum('total');

        // ---------- 6. Personal Expense (type px) ----------
        $personalExpenseQuery = (clone $baseQuery)->where('type', 'px');
        $personalExpenseItems = $personalExpenseQuery
            ->selectRaw('category_id, SUM(bdt_amount) as total')
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        $totalPersonalExpense = $personalExpenseItems->sum('total');

        // ---------- 7. Net summary ----------
        $netProfit = $totalIncome - $totalBusinessExpense;
        $gap = $totalIncome - $target;

        return view('report.index', compact(
            'viewType', 'year', 'month', 'from', 'to', 'target',
            'incomeItems', 'totalIncome',
            'businessExpenseItems', 'totalBusinessExpense',
            'personalExpenseItems', 'totalPersonalExpense',
            'netProfit', 'gap'
        ));
    }
}
