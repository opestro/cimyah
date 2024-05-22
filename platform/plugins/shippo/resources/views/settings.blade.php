@php
    $status = setting('shipping_shippo_status', 0);
    $testKey = setting('shipping_shippo_test_key') ?: '';
    $prodKey = setting('shipping_shippo_production_key') ?: '';
    $test = setting('shipping_shippo_sandbox', 1) ?: 0;
    $logging = setting('shipping_shippo_logging', 1) ?: 0;
    $cacheResponse = setting('shipping_shippo_cache_response', 1) ?: 0;
    $webhook = setting('shipping_shippo_webhooks', 1) ?: 0;
@endphp

<x-core::card>
    <x-core::table :striped="false" :hover="false">
        <x-core::table.body>
            <x-core::table.body.cell class="border-end" style="width: 5%">
                <x-core::icon name="ti ti-truck-delivery" />
            </x-core::table.body.cell>
            <x-core::table.body.cell style="width: 20%">
                <img
                    class="filter-black"
                    src="{{ url('vendor/core/plugins/shippo/images/logo-dark.svg') }}"
                    alt="Shippo"
                >
            </x-core::table.body.cell>
            <x-core::table.body.cell>
                <a href="https://goshippo.com/" target="_blank" class="fw-semibold">Shippo</a>
                <p class="mb-0">{{ trans('plugins/shippo::shippo.description') }}</p>
            </x-core::table.body.cell>
            <x-core::table.body.row class="bg-white">
                <x-core::table.body.cell colspan="3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div @class(['payment-name-label-group', 'd-none' => ! $status])>
                                <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span>
                                <label class="ws-nm inline-display method-name-label">Shippo</label>
                            </div>
                        </div>

                        <x-core::button
                            data-bs-toggle="collapse"
                            href="#collapse-shipping-method-shippo"
                            aria-expanded="false"
                            aria-controls="collapse-shipping-method-shippo"
                        >
                            @if ($status)
                                {{ trans('core/base::layouts.settings') }}
                            @else
                                {{ trans('core/base::forms.edit') }}
                            @endif
                        </x-core::button>
                    </div>
                </x-core::table.body.cell>
            </x-core::table.body.row>
            <x-core::table.body.row class="collapse" id="collapse-shipping-method-shippo">
                <x-core::table.body.cell class="border-left" colspan="3">
                    <x-core::form :url="route('ecommerce.shipments.shippo.settings.update')">
                        <div class="row">
                            <div class="col-sm-6">
                                <x-core::alert type="warning">
                                    <x-slot:title>
                                        {{ trans('plugins/shippo::shippo.note_0') }}
                                    </x-slot:title>

                                    <ul class="ps-3">
                                        <li style="list-style-type: circle;">
                                            <span>{!! BaseHelper::clean(
                                                trans('plugins/shippo::shippo.note_1', ['link' => 'https://docs.botble.com/farmart/1.x/usage-location']),
                                            ) !!}</span>
                                        </li>
                                        <li style="list-style-type: circle;">
                                            <span>{{ trans('plugins/shippo::shippo.note_2') }}</span>
                                        </li>
                                        <li style="list-style-type: circle;">
                                            <span>{!! BaseHelper::clean(trans('plugins/shippo::shippo.note_3', ['link' => route('ecommerce.settings.shipping')])) !!}</span>
                                        </li>
                                        <li style="list-style-type: circle;">
                                            <span>{!! BaseHelper::clean(
                                                trans('plugins/shippo::shippo.note_6', ['link' => 'https://goshippo.com/docs/reference#parcels-extras']),
                                            ) !!}</span>
                                        </li>
                                        @if (is_plugin_active('marketplace'))
                                            <li style="list-style-type: circle;">
                                                <span>{{ trans('plugins/shippo::shippo.note_4') }}</span>
                                            </li>
                                        @endif
                                    </ul>
                                </x-core::alert>

                                <x-core::form.label>
                                    {{ trans('plugins/shippo::shippo.configuration_instruction', ['name' => 'Shippo']) }}
                                </x-core::form.label>

                                <div>
                                    <p>{{ trans('plugins/shippo::shippo.configuration_requirement', ['name' => 'Shippo']) }}:</p>

                                    <ol>
                                        <li>
                                            <p>
                                                <a href="https://apps.goshippo.com/join" target="_blank">
                                                    {{ trans('plugins/shippo::shippo.service_registration', ['name' => 'Shippo']) }}
                                                </a>
                                            </p>
                                        </li>
                                        <li>
                                            <p>{{ trans('plugins/shippo::shippo.after_service_registration_msg', ['name' => 'Shippo']) }}</p>
                                        </li>
                                        <li>
                                            <p>{{ trans('plugins/shippo::shippo.enter_api_key') }}</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted">
                                    {{ trans('plugins/shippo::shippo.please_provide_information') }}
                                    <a href="https://goshippo.com/" target="_blank">Shippo</a>:
                                </p>

                                <x-core::form.text-input
                                    name="shipping_shippo_test_key"
                                    :label="trans('plugins/shippo::shippo.test_api_token')"
                                    placeholder="<API-KEY>"
                                    :disabled="BaseHelper::hasDemoModeEnabled()"
                                    :value="BaseHelper::hasDemoModeEnabled() ? Str::mask($testKey, '*', 10) : $testKey"
                                />

                                <x-core::form.text-input
                                    name="shipping_shippo_production_key"
                                    :label="trans('plugins/shippo::shippo.live_api_token')"
                                    placeholder="<API-KEY>"
                                    :disabled="BaseHelper::hasDemoModeEnabled()"
                                    :value="BaseHelper::hasDemoModeEnabled() ? Str::mask($prodKey, '*', 10) : $prodKey"
                                />

                                <x-core::form-group>
                                    <x-core::form.toggle
                                        name="shipping_shippo_sandbox"
                                        :checked="$test"
                                        :label="trans('plugins/shippo::shippo.sandbox_mode')"
                                    />
                                </x-core::form-group>

                                <x-core::form-group>
                                    <x-core::form.toggle
                                        name="shipping_shippo_status"
                                        :checked="$status"
                                        :label="trans('plugins/shippo::shippo.activate')"
                                    />
                                </x-core::form-group>

                                <x-core::form-group>
                                    <x-core::form.toggle
                                        name="shipping_shippo_logging"
                                        :checked="$logging"
                                        :label="trans('plugins/shippo::shippo.logging')"
                                    />
                                </x-core::form-group>

                                <x-core::form-group>
                                    <x-core::form.toggle
                                        name="shipping_shippo_cache_response"
                                        :checked="$cacheResponse"
                                        :label="trans('plugins/shippo::shippo.cache_response')"
                                    />
                                </x-core::form-group>

                                <x-core::form-group>
                                    <x-core::form.toggle
                                        name="shipping_shippo_webhooks"
                                        :checked="$webhook"
                                        :label="trans('plugins/shippo::shippo.webhooks')"
                                    />

                                    <x-core::form.helper-text>
                                        <a
                                            class="text-warning fw-bold"
                                            href="https://goshippo.com/docs/webhooks"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <span>Webhooks</span>
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <div>URL: <i>{{ route('shippo.webhooks', ['_token' => '__API_TOKEN__']) }}</i>
                                        </div>
                                    </x-core::form.helper-text>
                                </x-core::form-group>

                                <x-core::form.on-off.checkbox
                                    name="shipping_shippo_validate"
                                    :label="trans('plugins/shippo::shippo.check_validate_token')"
                                    :checked="setting('shipping_shippo_validate')"
                                />

                                @if (! empty($logFiles))
                                    <div class="form-group mb-3">
                                        <p class="mb-0">{{ __('Log files') }}: </p>
                                        <ul class="list-unstyled">
                                            @foreach ($logFiles as $logFile)
                                                <li><a
                                                        href="{{ route('ecommerce.shipments.shippo.view-log', $logFile) }}"
                                                        target="_blank"
                                                    ><strong>- {{ $logFile }} <i
                                                                class="fa fa-external-link"></i></strong></a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <x-core::alert type="warning">
                                    {{ trans('plugins/shippo::shippo.not_available_in_cod_payment_method') }}
                                </x-core::alert>

                                @env('demo')
                                    <x-core::alert type="danger">
                                        {{ trans('plugins/shippo::shippo.disabled_in_demo_mode') }}
                                    </x-core::alert>
                                @else
                                    <div class="text-end">
                                        <x-core::button type="submit" color="primary">
                                            {{ trans('core/base::forms.update') }}
                                        </x-core::button>
                                    </div>
                                @endenv
                            </div>
                        </div>
                    </x-core::form>
                </x-core::table.body.cell>
            </x-core::table.body.row>
        </x-core::table.body>
    </x-core::table>
</x-core::card>
