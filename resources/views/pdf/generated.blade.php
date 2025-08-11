<x-layouts.pdf :title="$title">
    @php
        $columnPositions = ['left' => 'text-left', 'center' => 'text-center', 'right' => 'text-right'];
    @endphp

    <table class="table">
        <thead>
            <tr>
                <th class="table-header">
                    #
                </th>
                @foreach ($columns as $name => $col)
                    <th class="table-header">{{ $col['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td class="table-col">
                        {{ $loop->iteration }}
                    </td>
                    @foreach ($columns as $name => $col)
                        <td class="{{ $columnPositions[$col['position']] ?? 'text-left' }} table-col">
                            @if ($col['type'] === 'date')
                                {{ $row[$name]?->format('Y-m-d') }}
                            @elseif($col['type'] === 'datetime')
                                {{ $row[$name]?->format('Y-m-d H:i:s') }}
                            @elseif($col['type'] === 'enum')
                                {{ $row[$name]?->label() }}
                            @elseif($col['type'] === 'boolean')
                                {{ $row[$name] ? __('Yes') : __('No') }}
                            @elseif($col['type'] === 'currency')
                                {{ $row[$name] ? "$ $row[$name]" : '' }}
                            @else
                                {{ $row[$name] }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</x-layouts.pdf>
