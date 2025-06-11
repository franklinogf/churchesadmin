<x-layouts.pdf :title="$title">
    @php
        $columnPositions = ['left' => 'text-left', 'center' => 'text-center', 'right' => 'text-right'];
    @endphp

    <table class="mx-auto min-w-[700px] max-w-[780px] border border-gray-400">
        <thead>
            <tr>
                <th class="bg-primary/30 border border-gray-400 p-1.5">
                    #
                </th>
                @foreach ($columns as $name => $col)
                    <th class="bg-primary/30 border border-gray-400 p-1.5">{{ $col['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td class="border border-gray-400 p-1">
                        {{ $loop->iteration }}
                    </td>
                    @foreach ($columns as $name => $col)
                        <td class="{{ $columnPositions[$col['position']] ?? 'text-left' }} border border-gray-400 p-1">
                            @if ($row[$name] instanceof \Carbon\CarbonImmutable)
                                {{ $row[$name]->format('Y-m-d') }}
                            @elseif($row[$name] instanceof \BackedEnum)
                                {{ $row[$name]->label() }}
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
