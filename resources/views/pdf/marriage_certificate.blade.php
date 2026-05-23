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
        <div class="certificate-title">{{ __('Certificado de Matrimonio') }}</div>
        <div class="certificate-subtitle">Marriage Certificate</div>

        <!-- Marriage Date and Priest -->
        <div class="intro-text">
            EL DIA
            <span class="line w-xs">{{ $marriageCertificate->married_at?->format('d') ?? '' }}</span>
            DE
            <span class="line w-md">{{ $marriageCertificate->married_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            DEL AÑO
            <span class="line w-sm">{{ $marriageCertificate->married_at?->format('Y') ?? '' }}</span>
            CONTRAJERON MATRIMONIO ANTE
            <br>
            EL RVDO. <span class="line w-lg">{{ $marriageCertificate->priest }}</span>
            <span class="label-en">On the &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; day of &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of the year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; they contracted marriage before the Rev. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>

        <!-- Groom -->
        <div class="row">
            <span class="section-label">DON</span>
            <span class="line w-xl">{{ $marriageCertificate->groom_name }}</span>
            <span class="label-en">MR.</span>
        </div>

        @if ($marriageCertificate->groom_age || $marriageCertificate->groom_birthplace || $marriageCertificate->groom_residence)
        <div class="row">
            DE <span class="line w-xs">{{ $marriageCertificate->groom_age }}</span> AÑOS DE EDAD,
            NATURAL DE <span class="line w-md">{{ $marriageCertificate->groom_birthplace }}</span>
            Y VECINO DE <span class="line w-md">{{ $marriageCertificate->groom_residence }}</span>
            <span class="label-en">years old, born in &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; residing in</span>
        </div>
        @endif

        <div class="row">
            <span class="section-label">HIJO DE DON</span>
            <span class="line w-lg">{{ $marriageCertificate->groom_father_name }}</span>
            <span class="label-en">Son of Mr.</span>
        </div>

        <div class="row">
            <span class="section-label">Y DE DOÑA</span>
            <span class="line w-lg">{{ $marriageCertificate->groom_mother_name }}</span>
            <span class="label-en">and Mrs.</span>
        </div>

        <!-- Bride -->
        <div class="row" style="margin-top: 8px;">
            <span class="section-label">DOÑA</span>
            <span class="line w-xl">{{ $marriageCertificate->bride_name }}</span>
            <span class="label-en">MRS.</span>
        </div>

        @if ($marriageCertificate->bride_age || $marriageCertificate->bride_birthplace || $marriageCertificate->bride_residence)
        <div class="row">
            DE <span class="line w-xs">{{ $marriageCertificate->bride_age }}</span> AÑOS DE EDAD,
            NATURAL DE <span class="line w-md">{{ $marriageCertificate->bride_birthplace }}</span>
            Y VECINO DE <span class="line w-md">{{ $marriageCertificate->bride_residence }}</span>
            <span class="label-en">years old, born in &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; residing in</span>
        </div>
        @endif

        <div class="row">
            <span class="section-label">HIJA DE DON</span>
            <span class="line w-lg">{{ $marriageCertificate->bride_father_name }}</span>
            <span class="label-en">Daughter of Mr.</span>
        </div>

        <div class="row">
            <span class="section-label">Y DOÑA</span>
            <span class="line w-lg">{{ $marriageCertificate->bride_mother_name }}</span>
            <span class="label-en">and Mrs.</span>
        </div>

        <!-- Witnesses -->
        <div class="row" style="margin-top: 8px;">
            <span class="section-label">TESTIGOS DON</span>
            <span class="line w-lg">{{ $marriageCertificate->witness1_name }}</span>
            Y <span class="line w-lg">{{ $marriageCertificate->witness2_name }}</span>
            <span class="label-en">Witnesses Mr. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and</span>
        </div>

        <!-- Book Reference -->
        <div class="row" style="margin-top: 10px;">
            ESTA CONFORME CON EL LIBRO
            <span class="line w-xs">{{ $marriageCertificate->book }}</span>
            FO. <span class="line w-xs">{{ $marriageCertificate->folio }}</span>
            NO. <span class="line w-xs">{{ $marriageCertificate->record_number }}</span>
            DE MATRIMONIO
            <span class="label-en">This matches book &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; folio &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of Marriage records</span>
        </div>

        <!-- Marginal Note -->
        @if ($marriageCertificate->marginal_note)
        <div class="row">
            <span class="section-label">Notas Marginales</span>
            <br>
            <span class="line note">{{ $marriageCertificate->marginal_note }}</span>
            <span class="label-en">Marginal Notation</span>
        </div>
        @endif

        <!-- Final Note -->
        <div class="footer-text">
            La partida anterior es copia fiel y exacta del original, del que doy fe.
            <br>
            The above is an exact copy of the original, which I certify.
        </div>

        <!-- Date Section -->
        <div class="date-section">
            {{ tenant('name') }},
            <span class="line w-xs">{{ $marriageCertificate->issued_at?->format('d') ?? '' }}</span>
            de <span class="line w-md">{{ $marriageCertificate->issued_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            de <span class="line w-sm">{{ $marriageCertificate->issued_at?->format('Y') ?? '' }}</span>
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
