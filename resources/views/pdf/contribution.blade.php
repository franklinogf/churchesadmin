@php
    use Illuminate\Support\Collection;
    /**
     * @var array{name:string,contributionAmount:string,contributions:array<string, string>} $contribution
     * @var string $title
     * @var string $year
     */
@endphp
<x-layouts.pdf :title="$title" noHeader>
    @include('pdf.partials.contribution', ['contribution' => $contribution, 'year' => $year])
</x-layouts.pdf>
