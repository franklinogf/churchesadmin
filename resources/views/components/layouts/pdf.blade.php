@props(['title', 'noHeader' => false])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        .page-break {
            @apply break-after-page;
        }
    </style>
</head>

<body class="bg-white font-sans text-black antialiased">
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
