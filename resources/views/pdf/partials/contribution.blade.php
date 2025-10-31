 <style>
     body {
         font-family: Arial, Helvetica, sans-serif;
         margin: 0px;
         padding: 40px 0px;
         color: #000;
         font-size: 10.5pt;
         line-height: 1.5;
     }

     .header {
         text-align: center;
         margin-bottom: 20px;
         position: relative;
     }

     .logo {
         max-width: 120px;
         max-height: 120px;
         margin-bottom: 10px;
     }

     .church-name {
         font-size: 16pt;
         font-weight: bold;
         margin-bottom: 5px;
         text-transform: uppercase;
     }

     .church-address {
         font-size: 11pt;
         margin-bottom: 20px;
     }

     .date {
         text-align: left;
         margin-bottom: 20px;
         font-size: 11pt;
     }

     .salutation {
         margin-bottom: 10px;
     }

     .content {
         margin-bottom: 10px;
         text-align: justify;
     }

     .contribution-details {
         margin-left: 0px;
     }

     .contribution-line {
         margin: 8px 0;
         padding-left: 60px;
     }

     .signature {
         margin-top: 5px;
     }

     .no-data {
         text-align: center;
         padding: 60px 20px;
         font-size: 14pt;
     }
 </style>

 <div class="header">
     @if (tenant('logo'))
         <img alt="{{ tenant('name') }} Logo" class="logo" src="{{ tenant('logoPath') }}" />
     @endif
     <div class="church-name">{{ tenant('name') }}</div>
     <div class="church-address">2740 Doyle Rd. Deltona, FL</div>
 </div>

 <div class="date">{{ now()->format('F d, Y') }}</div>

 <div class="salutation">
     <p>Dear {{ $contribution['name'] }},</p>
 </div>

 <div class="content">
     <p>We thank God for you! Your gifts to {{ tenant('name') }} throughout {{ $year }} are
         gratefully acknowledged.</p>
 </div>

 <div class="content">
     <p>Because of contributions, our congregation has been able to support the work of Jesus Christ
         locally,
         regionally, and around the world.</p>
 </div>

 <div class="content">
     <p>If you have any concerns about the accuracy of this information, please let us know.</p>
 </div>

 <div class="content">
     <p>For income tax purposes, it is important for us to state here that you did not receive any goods
         or
         services in return for any of these contributions other than intangible religious benefits. You
         made
         these gifts out of your own generosity and commitment to Jesus Christ.</p>
 </div>

 <div class="contribution-details">
     <p><strong>Our records show that you contributed</strong></p>

     @foreach ($contribution['contributions'] as $name => $amount)
         <div class="contribution-line">
             {{ $name }} {{ str_repeat('.', 30 - strlen($name)) }} {{ $amount }}
         </div>
     @endforeach

     <div class="contribution-line">
         Total {{ str_repeat('.', 30 - strlen('Total')) }} {{ $contribution['contributionAmount'] }}
     </div>
 </div>

 <div class="content">
     <p>Once again, thank you for your generous commitment to the work of Jesus Christ through this
         church.
     </p>
 </div>

 <div class="signature">
     <p>Sincerely,</p>
     <p style="margin-top: 10px;">The Finance Committee</p>
 </div>
