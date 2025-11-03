<x-mail::message>

Dear {{ $member->name }} {{ $member->last_name }},

<p>We are pleased to provide you with your contribution report for the year {{ $year }}. Below is a summary
    of your contributions:</p>

<ul>
    <li><strong>Total Contributions:</strong> {{ $contributionAmount }}</li>
</ul>

<p>Thank you for your continued support and generosity.</p>

<p>Sincerely,<br>{{ tenant('name') }}</p>

</x-mail::message>
