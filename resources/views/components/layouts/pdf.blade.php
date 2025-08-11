@props(['title', 'noHeader' => false])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>{{ $title }}</title>

    <style>
        body {
            margin: 1px;
        }

        .page-break {
            break-after: page;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .left-4 {
            left: 1rem;
        }

        .top-0 {
            top: 0;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .aspect-square {
            aspect-ratio: 1 / 1;
        }

        .h-auto {
            height: auto;
        }

        .w-20 {
            width: 5rem;
        }

        .text-black {
            color: black;
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .bg-white {
            background-color: white;
        }

        .mb-12 {
            margin-bottom: 3rem;
        }

        .mt-3 {
            margin-top: 0.75rem;
        }

        .text-center {
            text-align: center;
        }

        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .font-extrabold {
            font-weight: 800;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .table {
            /* "max-w-screen mx-auto min-w-[700px] border border-gray-400 */
            max-width: 100vw;
            margin-left: auto;
            margin-right: auto;
            min-width: 700px;
            border: 1px solid #cbd5e1;
        }

        .table-header {
            /* bg-primary/30 border border-gray-400 p-1.5 */
            background-color: rgba(37, 99, 235, 0.3);
            border: 1px solid #cbd5e1;
            padding: 0.375rem 0.75rem;
        }

        .table-col {
            /* border border-gray-400 p-1 */
            border: 1px solid #cbd5e1;
            padding: 0.375rem 0.75rem;
        }
    </style>
</head>

<body class="bg-white text-black antialiased">
    @if (!$noHeader)
        <div class="relative mb-12 mt-3 text-center">
            @if (tenant('logo'))
                <img class="absolute left-4 top-0 mb-2 aspect-square h-auto w-20" src="{{ tenant('logoPath') }}"
                     width="250px" />
            @endif
            <h1 class="text-3xl font-extrabold">{{ tenant('name') }}</h1>
            <p class="text-xl">{{ $title }}</p>
        </div>
    @endif
    {{ $slot }}
</body>

</html>
