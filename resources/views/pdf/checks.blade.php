@use(App\Dtos\CheckLayoutFieldsDto)

<x-layouts.pdf :title="__('Checks')">
    @foreach ($checks as $check)
        @foreach ($check->layout->fields as $fieldLayout)
            <div style="position: absolute; top: {{ $fieldLayout['position']['y'] }}px; left: {{ $fieldLayout['position']['x'] }}px;">
                {{ $check->fields->toArray()[$fieldLayout['target']] }}
            </div>
        @endforeach
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</x-layouts.pdf>
