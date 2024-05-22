@php
    $supportedLocales = Language::getSupportedLocales();
    if (empty($options)) {
        $options = [
            'before' => '',
            'lang_flag' => true,
            'lang_name' => true,
            'class' => '',
            'after' => '',
        ];
    }
@endphp

@if ($supportedLocales && count($supportedLocales) > 1)
    @php
        $languageDisplay = setting('language_display', 'all');
    @endphp

    @if (setting('language_switcher_display', 'dropdown') === 'dropdown')
        {!! Arr::get($options, 'before') !!}

        <x-core::dropdown class="{{ Arr::get($options, 'class') }}">
            <x-slot:trigger>
                <a
                    class="d-flex align-items-center gap-2 dropdown-toggle text-muted text-decoration-none"
                    href="#"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                        {!! language_flag(Language::getCurrentLocaleFlag(), Language::getCurrentLocaleName()) !!}
                    @endif
                    @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                        {{ Language::getCurrentLocaleName() }}
                    @endif
                </a>
            </x-slot:trigger>

            @foreach ($supportedLocales as $localeCode => $properties)
                @continue($localeCode === Language::getCurrentLocale())

                <x-core::dropdown.item
                    :href="Language::getSwitcherUrl($localeCode, $properties['lang_code'])"
                    class="d-flex gap-2 align-items-center"
                >
                    @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                        {!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}
                    @endif
                    @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                        {{ $properties['lang_name'] }}
                    @endif
                </x-core::dropdown.item>
            @endforeach
        </x-core::dropdown>

        {!! Arr::get($options, 'after') !!}
    @else
        <div class="d-flex gap-3 align-items-center">
            @foreach ($supportedLocales as $localeCode => $properties)
                @continue($localeCode === Language::getCurrentLocale())

                <a
                    href="{{ Language::getSwitcherUrl($localeCode, $properties['lang_code']) }}"
                    class="text-decoration-none small"
                >
                    @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag'))
                        {!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}
                    @endif
                    @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))
                        {{ $properties['lang_name'] }}
                    @endif
                </a>
            @endforeach
        </div>
    @endif
@endif
