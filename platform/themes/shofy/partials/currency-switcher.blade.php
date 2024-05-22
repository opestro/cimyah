<ul @class([$class ?? null])>
    @foreach (get_all_currencies() as $currency)
        @continue($currency->getKey() === get_application_currency()->getKey())
        <li>
            <a href="{{ route('public.change-currency', $currency->title) }}">{{ $currency->title }}</a>
        </li>
    @endforeach
</ul>
