@once
    <script>
        var lazyLoadShortcodeBlocks = function () {
            $('.shortcode-lazy-loading').each(function (index, element) {
                var $element = $(element);
                var name = $element.data('name');
                var attributes = $element.data('attributes');

                $.ajax({
                    url: '{{ route('public.ajax.render-ui-block') }}',
                    type: 'POST',
                    data: {
                        name,
                        attributes: {
                            ...attributes,
                        },
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function ({ error, data }) {
                        if (error) {
                            return;
                        }

                        $element.replaceWith(data);

                        document.dispatchEvent(new CustomEvent('shortcode.loaded', {
                            detail: {
                                name,
                                attributes,
                                html: data,
                            }
                        }));
                    },
                });
            });
        };

        window.addEventListener('load', function () {
            lazyLoadShortcodeBlocks();
        });
    </script>
@endonce
