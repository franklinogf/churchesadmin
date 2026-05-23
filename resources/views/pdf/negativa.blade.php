<x-layouts.pdf :$title noHeader>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.2;
        }

        .header {
            position: relative;
            text-align: center;
            margin-bottom: 15px;
            min-height: 80px;
        }

        .header-logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: auto;
        }

        .header-info h1 {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 3px 0;
        }

        .certificate-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 0 0;
        }

        .certificate-subtitle {
            font-size: 11px;
            text-align: center;
            font-style: italic;
            margin-bottom: 20px;
            color: #666;
        }

        .line {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-height: 0;
            margin: 0 2px;
            vertical-align: bottom;
        }

        .w-xs {
            width: 35px;
        }

        .w-sm {
            width: 70px;
        }

        .w-md {
            width: 130px;
        }

        .w-lg {
            width: 200px;
        }

        .w-xl {
            width: 280px;
        }

        .body-text {
            font-size: 12px;
            line-height: 2;
            margin-bottom: 10px;
        }

        .row {
            margin-bottom: 8px;
            line-height: 1.8;
        }

        .label-en {
            display: block;
            font-size: 10px;
            margin-top: -2px;
            color: #666;
        }

        .section-label {
            font-weight: bold;
            display: inline-block;
            margin-right: 2px;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 12px;
            line-height: 1.5;
        }

        .signature-section {
            margin-top: 80px;
            font-size: 11px;
        }

        .signature-box {
            display: inline-block;
            width: 45%;
            padding: 0 10px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            font-weight: bold;
        }

        .date-section {
            margin: 25px 0 20px 0;
            line-height: 1.8;
        }
    </style>

    <!-- Header -->
    <div class="header">
        @if (tenant('logo'))
            <img class="header-logo" src="{{ tenant('logoPath') }}" />
        @endif
        <div class="header-info">
            <h1>{{ tenant('name') }}</h1>
        </div>
    </div>

    <div>
        <!-- Title -->
        <div class="certificate-title">{{ __('NEGATIVA') }}</div>
        <div class="certificate-subtitle">Certificate of Absence</div>

        <!-- Body -->
        <div class="body-text">
            El que suscribe, certifica que después de haber buscado en los archivos de esta parroquia
            desde el
            <span class="line w-xs">{{ $negativa->searched_from?->format('d') ?? '' }}</span>
            de
            <span class="line w-md">{{ $negativa->searched_from?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de
            <span class="line w-sm">{{ $negativa->searched_from?->format('Y') ?? '' }}</span>
            al
            <span class="line w-xs">{{ $negativa->searched_to?->format('d') ?? '' }}</span>
            de
            <span class="line w-md">{{ $negativa->searched_to?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de
            <span class="line w-sm">{{ $negativa->searched_to?->format('Y') ?? '' }}</span>
            <br>
            <span class="label-en">The undersigned certifies that after searching the archives of this parish from &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to</span>
        </div>

        <div class="row">
            <span class="section-label">NO APARECE</span> la partida de
            <span class="line w-xl">{{ $negativa->person_name }}</span>
            <span class="label-en">there is NO RECORD of</span>
        </div>

        <div class="row">
            <span class="section-label">Hijo(a) de</span>
            <span class="line w-lg">{{ $negativa->father_name }}</span>
            <span class="label-en">Son/daughter of</span>
        </div>

        <div class="row">
            <span class="section-label">y de</span>
            <span class="line w-lg">{{ $negativa->mother_name }}</span>
            <span class="label-en">and of</span>
        </div>

        <!-- Final Note -->
        <div class="footer-text">
            La presente se expide a solicitud de parte interesada.
            <br>
            This document is issued at the request of the interested party.
        </div>

        <!-- Date Section -->
        <div class="date-section">
            {{ tenant('name') }},
            <span class="line w-xs">{{ $negativa->issued_at?->format('d') ?? '' }}</span>
            de <span class="line w-md">{{ $negativa->issued_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de <span class="line w-sm">{{ $negativa->issued_at?->format('Y') ?? '' }}</span>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Sello<br>Seal</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Párroco<br>Parish Priest</div>
            </div>
        </div>
    </div>
</x-layouts.pdf>
