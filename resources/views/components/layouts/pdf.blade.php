@props(['title', 'noHeader' => false])
@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = json_decode(file_get_contents($manifestPath), true);
    $cssFile = $manifest['resources/css/app.css']['file'];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    @if (app()->isLocal())
        @vite(['resources/css/app.css'])
    @else
        <style>
            {!! file_get_contents(public_path('build/' . $cssFile)) !!}
        </style>
    @endif
    <style>
        .page-break {
            @apply break-after-page;
        }
    </style>
</head>

<body class="bg-white text-black antialiased">
    @if (!$noHeader)
        <div class="relative mb-12 mt-3 text-center">
            @if (tenant('logo'))
                <div class="absolute left-4 top-0 mb-2 aspect-square h-auto w-20">
                    @inlinedImage(tenant('logo'))
                </div>
            @endif
            <h1 class="text-3xl font-extrabold">{{ tenant('name') }}</h1>
            <p class="text-xl">{{ $title }}</p>
        </div>
    @endif
    {{ $slot }}
</body>

</html>
