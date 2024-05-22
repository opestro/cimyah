<ul class="bb-social-sharing">
    @foreach($socials as $social)
        <li class="bb-social-sharing__item">
            <a
                href="{{ $social['url'] }}"
                target="_blank"
                title="{{ __('Share on :social', ['social' => $social['name']]) }}"
                @style(["background-color: {$social['background_color']}" => $social['background_color'], "color: {$social['color']}" => $social['color']])
            >
                {!! $social['icon'] !!}
            </a>
        </li>
    @endforeach

    <li class="bb-social-sharing__item">
        <button title="{{ __('Copy link') }}" data-bb-toggle="social-sharing-clipboard" data-clipboard-text="{{ $url }}">
            <x-core::icon name="ti ti-copy" data-clipboard-icon="copy" />
            <x-core::icon name="ti ti-check" data-clipboard-icon="copied" style="display: none;" />
        </button>
    </li>
</ul>

@once
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            function toggleClipboardActionIcon(element) {
                const copiedState = element.querySelector('[data-clipboard-icon="copy"]');
                const copyState = element.querySelector('[data-clipboard-icon="copied"]');

                copiedState.style.display = 'none';
                copyState.style.display = 'inline-block';

                setTimeout(function () {
                    copiedState.style.display = 'inline-block';
                    copyState.style.display = 'none';
                }, 3000);
            }

            document.querySelectorAll('[data-bb-toggle="social-sharing-clipboard"]').forEach(function (element) {
                element.addEventListener('click', function (event) {
                    event.preventDefault();

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(element.dataset.clipboardText).then(function () {
                            toggleClipboardActionIcon(element);
                        });
                    } else {
                        const input = document.createElement('input');
                        input.value = element.dataset.clipboardText;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);

                        toggleClipboardActionIcon(element);
                    }
                });
            });
        });
    </script>
@endonce
