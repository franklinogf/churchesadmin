<x-mail::message>
    @if ($customMessage)
        {!! nl2br(e($customMessage)) !!}

        ---
    @endif

    # {{ __('Calendar Events') }}

    @foreach ($events as $event)
        <x-mail::panel>
            ## {{ $event->title }}

            @if ($event->description)
                {{ $event->description }}
            @endif

            @if ($event->location)
                **{{ __('Location') }}:** {{ $event->location }}
            @endif

            **{{ __('Start') }}:** {{ $event->start_at->format('l, F j, Y - g:i A') }}
            **{{ __('End') }}:** {{ $event->end_at->format('l, F j, Y - g:i A') }}
        </x-mail::panel>
    @endforeach

    {{ __('Thank you') }},
    {{ tenant('church_name') }}
</x-mail::message>
