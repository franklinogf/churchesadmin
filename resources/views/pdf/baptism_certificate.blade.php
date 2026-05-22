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
        <div class="certificate-title">{{ __('Certificado de Bautismo') }}</div>
        <div class="certificate-subtitle">Certificate of Baptism</div>

        <!-- Intro Paragraph -->
        <div class="intro-text">
            Yo, el infrascrito sacerdote de la {{ tenant('name') }}.
            <br>
            I, the undersigned priest of the {{ tenant('name') }}.
        </div>

        <!-- Main Content -->
        <div class="row">
            <span class="section-label">Certifico</span> que en el libro <span
                  class="line w-sm">{{ $baptismCertificate->book }}</span> de bautismo, folio
            <span class="line w-sm">{{ $baptismCertificate->folio }}</span> No. <span
                  class="line w-xs">{{ $baptismCertificate->record_number }}</span> aparece la siguiente partida:
            <span class="label-en">Certify that in the book
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                of baptisms, on page
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                No.
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                appears the
                following entry</span>
        </div>

        <div class="row">
            En el día <span class="line w-xs">{{ $baptismCertificate->baptized_at?->format('d') ?? '' }}</span> de
            <span
                  class="line w-md">{{ $baptismCertificate->baptized_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            del año
            <span class="line w-sm">{{ $baptismCertificate->baptized_at?->format('Y') ?? '' }}</span> el prebistero
            <span class="line w-md">{{ $baptismCertificate->priest }}</span>
            <span class="label-en">On the day &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                of
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                year
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                the priest</span>
        </div>

        <div class="row">
            <span class="section-label">Bautizó a</span>
            <span class="line w-xl">{{ $baptismCertificate->baptized_name }}</span>
            <span class="label-en">baptized</span>
        </div>

        <div class="row">
            que nació en <span class="line w-md">{{ $baptismCertificate->birth_place }}</span> el día
            <span class="line w-xs">{{ $baptismCertificate->birth_date?->format('d') ?? '' }}</span> de
            <span
                  class="line w-md">{{ $baptismCertificate->birth_date?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            del año
            <span class="line w-sm">{{ $baptismCertificate->birth_date?->format('Y') ?? '' }}</span> hijo(a)
            <span class="label-en">born in
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                on the day &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; of
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                of
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                son,daughter</span>
        </div>

        <div class="row">
            de Don <span class="line w-lg">{{ $baptismCertificate->father_name }}</span> natural de <span
                  class="line w-md">{{ $baptismCertificate->father_origin_place }}</span> vecino de <span
                  class="line w-md">{{ $baptismCertificate->father_residence_place }}</span>
            <span class="label-en">of Mr.
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                born in
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                neighbour of</span>
        </div>

        <div class="row">
            y Doña <span class="line w-lg">{{ $baptismCertificate->mother_name }}</span> natural de <span
                  class="line w-md">{{ $baptismCertificate->mother_origin_place }}</span> vecina de <span
                  class="line w-md">{{ $baptismCertificate->mother_residence_place }}</span>
            <span class="label-en">and Mrs.
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                born in
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                neighbour of</span>
        </div>

        <div class="row">
            <span class="section-label">Abuelos Paternos</span>
            <span class="line w-lg">{{ $baptismCertificate->paternal_grandfather_name }}</span> y
            <span class="line w-lg">{{ $baptismCertificate->paternal_grandmother_name }}</span>
            <span class="label-en">Grandparents</span>
        </div>

        <div class="row">
            <span class="section-label">Abuelos Maternos</span>
            <span class="line w-lg">{{ $baptismCertificate->maternal_grandfather_name }}</span> y
            <span class="line w-lg">{{ $baptismCertificate->maternal_grandmother_name }}</span>
            <span class="label-en">Grandparents</span>
        </div>

        <div class="row">
            <span class="section-label">Padrinos</span>
            <span class="line w-lg">{{ $baptismCertificate->godfather_name }}</span> y
            <span class="line w-lg">{{ $baptismCertificate->godmother_name }}</span>
            <span class="label-en">Godparents</span>
        </div>

        <!-- Marginal Note -->
        <div class="row">
            <span class="section-label">Notas Marginales</span>
            <br>
            <span class="line note">{{ $baptismCertificate->marginal_note }}</span>
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
            {{ $baptismCertificate->issued_place }},
            <span class="line w-xs">{{ $baptismCertificate->issued_at?->format('d') ?? '' }}</span>
            del mes de <span
                  class="line w-md">{{ $baptismCertificate->issued_at?->locale('es')->isoFormat('MMMM') ?? '' }}</span>
            del <span class="line w-sm">{{ $baptismCertificate->issued_at?->format('Y') ?? '' }}</span>
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
