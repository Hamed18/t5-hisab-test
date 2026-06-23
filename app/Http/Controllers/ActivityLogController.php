<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->default_business_id;
        $query = ActivityLog::forBusiness($businessId)->with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        // Filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(30)->appends($request->query());

        // Data for filter dropdowns
        $actions = ActivityLog::forBusiness($businessId)->select('action')->distinct()->pluck('action');
        $modelTypes = ActivityLog::forBusiness($businessId)->select('model_type')->distinct()->pluck('model_type');

        $filters = [
            ['name' => 'action', 'label' => 'Action', 'type' => 'select', 'options' => $actions->mapWithKeys(fn($a) => [$a => ucfirst($a)])->toArray()],
            ['name' => 'model_type', 'label' => 'Model', 'type' => 'select', 'options' => $modelTypes->mapWithKeys(fn($m) => [$m => class_basename($m)])->toArray()],
            ['name' => 'date_from', 'label' => 'From Date', 'type' => 'date'],
            ['name' => 'date_to', 'label' => 'To Date', 'type' => 'date'],
        ];

        return view('activity_logs.index', compact('logs', 'filters'));
    }
}
