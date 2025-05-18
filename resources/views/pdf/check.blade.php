<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check Test</title>
    <style>
        @page {
            margin: 0;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    {{-- <div style="width: {{ $dimensions['width'] }}px; height: {{ $dimensions['height'] }}px; position: relative;"> --}}
        @foreach ($fieldsLayout as $fieldId => $fieldLayout)
            <div style="position: absolute; top: {{ $fieldLayout['position']['y'] }}px; left: {{ $fieldLayout['position']['x'] }}px;">
                {{ $fields[$fieldId]}}
            </div>
        @endforeach

        {{--
    </div> --}}

</body>

</html>
