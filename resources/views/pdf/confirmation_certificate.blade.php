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

        .header-info p {
            font-size: 11px;
            margin: 2px 0;
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

        .note {
            width: 95%;
            word-wrap: break-word;
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
        <div class="certificate-title">{{ __('Certificado de Confirmación') }}</div>
        <div class="certificate-subtitle">Certificate of Confirmation</div>

        <!-- Intro Paragraph -->
        <div class="intro-text">
            Yo, el infrascrito <span class="line w-lg">{{ $confirmationCertificate->priest }}</span> sacerdote de la {{ tenant('name') }},
            de la Arquidiócesis de San Juan, Puerto Rico.
            <br>
            I, the undersigned <span class="line w-lg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> priest of the {{ tenant('name') }},
            of the Archdiocese of San Juan, Puerto Rico.
        </div>

        <!-- Main Content -->
        <div class="row">
            <span class="section-label">CERTIFICO:</span> Que según consta en el libro
            <span class="line w-sm">{{ $confirmationCertificate->book }}</span> de CONFIRMADOS, folio
            <span class="line w-sm">{{ $confirmationCertificate->folio }}</span> No.
            <span class="line w-xs">{{ $confirmationCertificate->record_number }}</span>
            en esta Parroquia:
            <span class="label-en">I CERTIFY: That according to the book
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                of CONFIRMED, folio
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                No.
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                in this Parish:</span>
        </div>

        <div class="row">
            <span class="line w-xl">{{ $confirmationCertificate->confirmed_name }}</span>
            <span class="label-en">Name of confirmed person</span>
        </div>

        <div class="row">
            <span class="section-label">Hijo(a) de</span>
            <span class="line w-lg">{{ $confirmationCertificate->father_name }}</span>
            <span class="label-en">Son/daughter of</span>
        </div>

        <div class="row">
            <span class="section-label">y de</span>
            <span class="line w-lg">{{ $confirmationCertificate->mother_name }}</span>
            <span class="label-en">and of</span>
        </div>

        <div class="row">
            <span class="section-label">CONFIRMADO(A) por</span>
            <span class="line w-lg">{{ $confirmationCertificate->confirmed_by }}</span>
            <span class="label-en">CONFIRMED by</span>
        </div>

        <div class="row">
            El día <span class="line w-xs">{{ $confirmationCertificate->confirmed_at?->format('d') ?? '' }}</span> del mes
            <span class="line w-md">{{ $confirmationCertificate->confirmed_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            del año
            <span class="line w-sm">{{ $confirmationCertificate->confirmed_at?->format('Y') ?? '' }}</span>
            <span class="label-en">On the day &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                of the month
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                of the year
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>

        <div class="row">
            <span class="section-label">PADRINO</span>
            <span class="line w-lg">{{ $confirmationCertificate->godfather_name }}</span>
            <span class="label-en">GODFATHER</span>
        </div>

        <div class="row">
            <span class="section-label">MADRINA</span>
            <span class="line w-lg">{{ $confirmationCertificate->godmother_name }}</span>
            <span class="label-en">GODMOTHER</span>
        </div>

        <!-- Marginal Note -->
        <div class="row">
            <span class="section-label">Notas Marginales</span>
            <br>
            <span class="line note">{{ $confirmationCertificate->marginal_note }}</span>
            <span class="label-en">Marginal Notation</span>
        </div>

        <!-- Final Note -->
        <div class="footer-text">
            La partida anterior es copia fiel y exacta del original, del que doy fe.
            <br>
            The above is an exact copy of the original, which I certify.
        </div>

        <!-- Date Section -->
        <div class="date-section">
            {{ $confirmationCertificate->issued_place }},
            <span class="line w-xs">{{ $confirmationCertificate->issued_at?->format('d') ?? '' }}</span>
            de <span class="line w-md">{{ $confirmationCertificate->issued_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de <span class="line w-sm">{{ $confirmationCertificate->issued_at?->format('Y') ?? '' }}</span>
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
