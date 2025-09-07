@php
    $totalEntries = [];
    $totalExpenses = [];
    foreach ($dates as $date) {
        $totalEntries[$date] = 0;
        $totalExpenses[$date] = 0;
    }
@endphp
<x-layouts.pdf :title="$title">

    <style>
        .tab {
            padding-left: 20px;
        }

        .text-right {
            text-align: right;
            padding-right: 20px;
        }

        .table {
            border: 0;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                @foreach ($dates as $date)
                    <th class="text-right">{{ $date }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Entries</td>
            </tr>
            @foreach ($entries as $key => $entriesDates)
                <tr>
                    <td class="tab">{{ $key }}</td>
                    @foreach ($dates as $date)
                        @php
                            $amount = $entriesDates[$date] ?? 0;
                            $totalEntries[$date] += $amount;

                        @endphp
                        <td class="text-right">{{ number_format($amount, 2) }}</td>
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td class="text-right font-bold">Total</td>
                @foreach ($dates as $date)
                    <td class="text-right font-bold">{{ number_format($totalEntries[$date] ?? 0, 2) }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</x-layouts.pdf>
