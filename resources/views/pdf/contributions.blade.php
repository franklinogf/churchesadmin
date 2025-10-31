@php
    use Illuminate\Support\Collection;
    /**
     * @var Collection<int, array{name:string,contributionAmount:string,contributions:array<string, string>}> $contributions
     * @var string $title
     * @var string $year
     */
@endphp
<x-layouts.pdf :title="$title" noHeader>

    @if ($contributions->count() > 0)
        @foreach ($contributions as $contribution)
            @include('pdf.partials.contribution', ['contribution' => $contribution, 'year' => $year])
            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @else
        <div class="no-data">
            <p>No contributions found for the selected criteria.</p>
        </div>
    @endif

</x-layouts.pdf>
