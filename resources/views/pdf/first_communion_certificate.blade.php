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
            margin-bottom: 15px;
            color: #666;
        }

        .intro-text {
            font-size: 12px;
            margin-bottom: 12px;
            line-height: 1.5;
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

        .row {
            margin-bottom: 6px;
            line-height: 1.6;
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
        <div class="certificate-title">{{ __('Certificado de Primera Comunión') }}</div>
        <div class="certificate-subtitle">First Communion Certificate</div>

        <!-- Intro Paragraph -->
        <div class="intro-text">
            El infrascrito <span class="line w-lg">{{ $firstCommunionCertificate->priest }}</span> sacerdote de la {{ tenant('name') }},
            de la Arquidiócesis de San Juan, Puerto Rico.
            <br>
            The undersigned <span class="line w-lg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> priest of the {{ tenant('name') }},
            of the Archdiocese of San Juan, Puerto Rico.
        </div>

        <!-- Main Content -->
        <div class="row">
            <span class="section-label">CERTIFICO:</span> Que según consta en los libros de esta parroquia:
            <span class="label-en">I CERTIFY: That according to the books of this parish:</span>
        </div>

        <div class="row">
            <span class="line w-xl">{{ $firstCommunionCertificate->communicant_name }}</span>
            <span class="label-en">Name of communicant</span>
        </div>

        <div class="row">
            <span class="section-label">Hijo(a) de</span>
            <span class="line w-lg">{{ $firstCommunionCertificate->father_name }}</span>
            <span class="label-en">Son/daughter of</span>
        </div>

        <div class="row">
            <span class="section-label">y de</span>
            <span class="line w-lg">{{ $firstCommunionCertificate->mother_name }}</span>
            <span class="label-en">and of</span>
        </div>

        <div class="row">
            HIZO LA PRIMERA COMUNION el día
            <span class="line w-xs">{{ $firstCommunionCertificate->communion_at?->format('d') ?? '' }}</span>
            de
            <span class="line w-md">{{ $firstCommunionCertificate->communion_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de
            <span class="line w-sm">{{ $firstCommunionCertificate->communion_at?->format('Y') ?? '' }}</span>
            <span class="label-en">Made FIRST COMMUNION on the &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; day of &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of the year</span>
        </div>

        <!-- Final Note -->
        <div class="footer-text">
            La partida anterior es copia fiel y exacta del original, del que doy fe.
            <br>
            The above is an exact copy of the original, which I certify.
        </div>

        <!-- Date Section -->
        <div class="date-section">
            {{ tenant('name') }},
            <span class="line w-xs">{{ $firstCommunionCertificate->issued_at?->format('d') ?? '' }}</span>
            de <span class="line w-md">{{ $firstCommunionCertificate->issued_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de <span class="line w-sm">{{ $firstCommunionCertificate->issued_at?->format('Y') ?? '' }}</span>
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
