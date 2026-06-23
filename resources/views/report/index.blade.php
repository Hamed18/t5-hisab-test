@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Report')

@section('content')
<style>
    .filter-bar { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; margin-bottom: 1.5rem; }
    .filter-bar select, .filter-bar input { padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
    .btn { background: #4f46e5; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; }
    .card { background: white; padding: 1.25rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.5rem; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; }
    .summary-row td { font-weight: 700; }
    .positive { color: #16a34a; }
    .negative { color: #dc2626; }
</style>

<h1 style="margin-bottom: 1rem;">
    @if($viewType == 'monthly')
        Report: {{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }}
    @elseif($viewType == 'yearly')
        Report: Full Year {{ $year }}
    @else
        Report: {{ $from }} to {{ $to }}
    @endif
</h1>

{{-- Filter form --}}
<form method="GET" class="filter-bar">
    <select name="view">
        <option value="monthly" {{ $viewType == 'monthly' ? 'selected' : '' }}>Monthly</option>
        <option value="yearly" {{ $viewType == 'yearly' ? 'selected' : '' }}>Yearly</option>
        <option value="range" {{ $viewType == 'range' ? 'selected' : '' }}>Date Range</option>
    </select>

    @if($viewType != 'range')
        <select name="year">
            @for($y = date('Y')-5; $y <= date('Y')+2; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    @endif

    @if($viewType == 'monthly')
        <select name="month">
            @foreach(range(1,12) as $m)
                <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ date('F', mktime(0,0,0,$m,1)) }}
                </option>
            @endforeach
        </select>
    @endif

    @if($viewType == 'range')
        <input type="date" name="from" value="{{ $from }}">
        <input type="date" name="to" value="{{ $to }}">
    @endif

    <input type="number" name="target" placeholder="Target (BDT)" value="{{ $target }}" style="max-width: 120px;">

    <button type="submit" class="btn">Go</button>
</form>

{{-- Income --}}
<div class="card">
    <h2>Income</h2>
    <table>
        <thead><tr><th>Source</th><th>Category</th><th>Amount (BDT)</th></tr></thead>
        <tbody>
            @foreach($incomeItems as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot><tr class="summary-row"><td colspan="2">Total Income</td><td>{{ number_format($totalIncome, 2) }}</td></tr></tfoot>
    </table>
</div>

{{-- Business Expense --}}
<div class="card">
    <h2>Business Expense</h2>
    <table>
        <thead><tr><th>Category</th><th>Amount (BDT)</th></tr></thead>
        <tbody>
            @foreach($businessExpenseItems as $item)
            <tr>
                <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot><tr class="summary-row"><td>Total Business Expense</td><td>{{ number_format($totalBusinessExpense, 2) }}</td></tr></tfoot>
    </table>
</div>

{{-- Personal Expense --}}
<div class="card">
    <h2>Personal Expense</h2>
    <table>
        <thead><tr><th>Category</th><th>Amount (BDT)</th></tr></thead>
        <tbody>
            @foreach($personalExpenseItems as $item)
            <tr>
                <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot><tr class="summary-row"><td>Total Personal Expense</td><td>{{ number_format($totalPersonalExpense, 2) }}</td></tr></tfoot>
    </table>
</div>

{{-- Net Summary --}}
<div class="card">
    <h2>Net Summary</h2>
    <table>
        <tr><td>Total Income</td><td>{{ number_format($totalIncome, 2) }}</td></tr>
        <tr><td>Total Business Expense</td><td>{{ number_format($totalBusinessExpense, 2) }}</td></tr>
        <tr><td>Total Personal Expense</td><td>{{ number_format($totalPersonalExpense, 2) }}</td></tr>
        <tr class="summary-row"><td>Net Profit (Income - Business Expense)</td><td class="{{ $netProfit >= 0 ? 'positive' : 'negative' }}">{{ number_format($netProfit, 2) }}</td></tr>
        @if($target > 0)
        <tr><td>Target</td><td>{{ number_format($target, 2) }}</td></tr>
        <tr class="summary-row"><td>Gap (Income vs Target)</td><td class="{{ $gap >= 0 ? 'positive' : 'negative' }}">{{ number_format($gap, 2) }}</td></tr>
        @endif
    </table>
</div>
@endsection
