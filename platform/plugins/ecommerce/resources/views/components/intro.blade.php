<x-core::card>
    <div class="page page-center" style="min-height: calc(100vh - 25rem)">
        <div class="container container-tight py-4">
            <div class="empty">
                <div class="empty-img">
                    {{ $icon }}
                </div>
                <p class="empty-title">{{ $title }}</p>
                <p class="empty-subtitle text-secondary">
                    {{ $subtitle }}
                </p>
                @if (isset($actionUrl) && isset($actionLabel))
                    <div class="empty-action">
                        <x-core::button
                            color="primary"
                            tag="a"
                            :href="$actionUrl"
                        >
                            {{ $actionLabel }}
                        </x-core::button>

                        {!! $extraButtons ?? '' !!}
                    </div>
                @endif

                {!! $extra ?? '' !!}
            </div>
        </div>
    </div>
</x-core::card>
