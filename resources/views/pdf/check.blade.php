<x-layouts.pdf :title="$title" noHeader>
    @foreach ($check->layout->fields as $fieldLayout)
        <div
             style="position: absolute; top: {{ $fieldLayout['position']['y'] }}px; left: {{ $fieldLayout['position']['x'] }}px;">
            {{ $check->fields->toArray()[$fieldLayout['target']] }}
        </div>
    @endforeach

</x-layouts.pdf>
