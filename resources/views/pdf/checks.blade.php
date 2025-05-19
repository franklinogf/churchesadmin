@use(App\Dtos\CheckLayoutFieldsDto)

<x-layouts.pdf :title="__('Checks')">
    @foreach ($checks as $check)
        @php
            $fields = new CheckLayoutFieldsDto($check->date->format('Y-m-d'), $check->transaction->amountFloat, "{$check->member->name} {$check->member->last_name}", $check->note)->toArray();
        @endphp
        @foreach ($check->layout->fields as $fieldId => $fieldLayout)
            <div style="position: absolute; top: {{ $fieldLayout['position']['y'] }}px; left: {{ $fieldLayout['position']['x'] }}px;">
                {{ $check->fields->toArray()[$fieldId] }}
            </div>
        @endforeach
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</x-layouts.pdf>
