@extends($isAdmin ? 'layouts.admin' : 'layouts.authenticated')

@section('title', 'Salary Tracker')

@section('content')
<style>
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    h1 { font-size: 1.5rem; }
    .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-width: 1000px; }
    th, td { padding: 0.5rem; text-align: center; border-bottom: 1px solid #eee; }
    th { background: #f3f4f6; font-weight: 600; white-space: nowrap; }
    td:first-child, th:first-child { text-align: left; padding-left: 0.75rem; }
    .year-select { display: flex; gap: 0.5rem; align-items: center; }
    select, button { padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
    .total-row td { font-weight: 700; background: #f9fafb; }
</style>

<div class="header">
    <h1>Salary Tracker – {{ $year }}</h1>
    <form method="GET" class="year-select">
        <select name="year">
            @for ($y = date('Y') - 5; $y <= date('Y') + 2; $y++)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit">Go</button>
    </form>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>May</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Aug</th>
                <th>Sep</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dec</th>
                <th>Total Paid</th>
                <th>Expected</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse ($people as $person)
                @php
                    $totalPaid = array_sum($data[$person]);
                    $grandTotal += $totalPaid;
                    $expectedAmount = $expected[$person] ?? 0;
                @endphp
                <tr>
                    <td>{{ $person }}</td>
                    @for ($month = 1; $month <= 12; $month++)
                        <td>{{ $data[$person][$month] ? number_format($data[$person][$month]) : '-' }}</td>
                    @endfor
                    <td><strong>{{ number_format($totalPaid) }}</strong></td>
                    <td>{{ $expectedAmount ? number_format($expectedAmount) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15">No salary transactions found for {{ $year }}. Make sure a transaction type with slug <strong>salary</strong> exists and you have added salary transactions.</td>
                </tr>
            @endforelse
        </tbody>
        @if (count($people))
            <tfoot>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    @php $monthTotals = array_fill(1, 12, 0); @endphp
                    @foreach ($data as $person => $months)
                        @for ($m = 1; $m <= 12; $m++)
                            @php $monthTotals[$m] += $months[$m]; @endphp
                        @endfor
                    @endforeach
                    @for ($m = 1; $m <= 12; $m++)
                        <td><strong>{{ $monthTotals[$m] ? number_format($monthTotals[$m]) : '-' }}</strong></td>
                    @endfor
                    <td><strong>{{ number_format(array_sum($monthTotals)) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
@endsection
