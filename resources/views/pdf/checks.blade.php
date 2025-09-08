<x-layouts.pdf :title="$title" noHeader>
    @foreach ($checks as $check)
        @foreach ($check->layout->fields as $fieldLayout)
            <p
               style="position: absolute; top: {{ $fieldLayout['position']['y'] }}px; left: {{ $fieldLayout['position']['x'] }}px;">
                {{ $check->fields->toArray()[$fieldLayout['target']] }}
            </p>
        @endforeach
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</x-layouts.pdf>
