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
        <div class="mb-8 mt-3 text-center">
            <h1 class="text-3xl font-extrabold">{{ config('app.name') }}</h1>
            <p class="text-lg">{{ $title }}</p>
        </div>
    @endif
    {{ $slot }}
</body>

</html>
