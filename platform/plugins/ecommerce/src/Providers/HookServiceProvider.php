<?php

namespace Botble\Ecommerce\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\Form;
use Botble\Base\Facades\Html;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Rules\OnOffRule;
use Botble\Base\Supports\TwigCompiler;
use Botble\Dashboard\Events\RenderingDashboardWidgets;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Botble\DataSynchronize\Importer\Importer;
use Botble\Ecommerce\Enums\OrderReturnStatusEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\Discount;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\FlashSale as FlashSaleFacade;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\GoogleAnalytics\GoogleTagManager;
use Botble\Ecommerce\Importers\ProductImporter;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Models\Invoice;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\HandleFrontPages;
use Botble\Ecommerce\Supports\TwigExtension;
use Botble\Faq\Contracts\Faq as FaqContract;
use Botble\Faq\FaqCollection;
use Botble\Faq\FaqItem;
use Botble\Faq\FaqSupport;
use Botble\Language\Facades\Language;
use Botble\Media\Facades\RvMedia;
use Botble\Menu\Events\RenderingMenuOptions;
use Botble\Menu\Facades\Menu;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Forms\BankTransferPaymentMethodForm;
use Botble\Payment\Forms\CODPaymentMethodForm;
use Botble\Payment\Http\Requests\PaymentMethodRequest;
use Botble\Payment\Services\Gateways\BankTransferPaymentService;
use Botble\Payment\Services\Gateways\CodPaymentService;
use Botble\Payment\Supports\PaymentHelper;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Slug\Models\Slug;
use Botble\Support\Http\Requests\Request as BaseRequest;
use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Facades\ThemeOption;
use Botble\Theme\Http\Requests\UpdateOptionsRequest;
use Botble\Theme\Supports\ThemeSupport;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Menu::addMenuOptionModel(Brand::class);
        Menu::addMenuOptionModel(ProductCategory::class);

        $this->app['events']->listen(RenderingMenuOptions::class, function () {
            add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 12);
        });

        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 208, 2);
        });

        $this->app['events']->listen(RenderingThemeOptionSettings::class, function () {
            add_action(RENDERING_THEME_OPTIONS_PAGE, [$this, 'addThemeOptions'], 35);
        });

        add_filter('cms_twig_compiler', function (TwigCompiler $twigCompiler) {
            if (! array_key_exists(TwigExtension::class, $twigCompiler->getExtensions())) {
                $twigCompiler->addExtension(new TwigExtension());
            }

            return $twigCompiler;
        }, 123);

        add_filter('cms_unauthenticated_response', function ($defaultException) {
            if (is_in_admin(true)) {
                return $defaultException;
            }

            return redirect()->guest(route('customer.login'));
        });

        add_filter('data_synchronize_import_form_before', function (?string $html, Importer $importer): ?string {
            if (! $importer instanceof ProductImporter) {
                return $html;
            }

            return $html . view('plugins/ecommerce::products.partials.import-type-selector')->render();
        }, 999, 2);

        add_filter('core_request_rules', function (array $rules, Request $request): array {
            if (! $request instanceof UpdateOptionsRequest) {
                return $rules;
            }

            $fields = $request->collect()->filter(function ($value, $key) {
                return Str::startsWith($key, 'ecommerce_') && Str::endsWith($key, '_page_slug');
            });

            if (empty($fields)) {
                return $rules;
            }

            $locale = null;

            if (
                is_plugin_active('language')
                && Language::getRefLang()
            ) {
                $locale = Language::getRefLang();
            }

            $themeName = Theme::getThemeName();

            $themeOptions = collect(ThemeOption::getOptions())
                ->filter(
                    function ($value, $key) use ($themeName, $locale) {
                        $prefix = sprintf('theme-%s-ecommerce', $themeName);

                        if ($locale) {
                            $prefix = sprintf('theme-%s-%s-ecommerce', $themeName, $locale);
                        }

                        return Str::startsWith($key, $prefix) && Str::endsWith($key, '_page_slug');
                    }
                );

            $rules = $fields->mapWithKeys(fn ($value, $key) => [$key => ['nullable', 'string']])->all();

            foreach ($fields as $key => $value) {
                $rules[$key][] = function ($attribute, $value, $fail) use ($locale, $fields, $key, $themeOptions) {
                    if (
                        collect($fields)->reject(fn ($v, $k) => $k === $key)->contains($value)
                        || $themeOptions
                            ->reject(fn ($value, $k) => $k === ThemeOption::getOptionKey($key, $locale))
                            ->contains($value)
                    ) {
                        $fail(trans('plugins/ecommerce::ecommerce.theme_options.page_slug_already_exists', [
                            'slug' => $value,
                        ]));
                    }
                };
            }

            return $rules;
        }, 999, 2);

        $this->app['events']->listen(RouteMatched::class, function () {
            add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 121);
            add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getPendingOrders'], 130, 2);
            add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getMenuItemCount'], 120);
            add_filter(RENDER_PRODUCTS_IN_CHECKOUT_PAGE, [$this, 'renderProductsInCheckoutPage'], 1000);
        });

        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function ($widgets) {
                foreach ($widgets as $key => $widget) {
                    if (in_array($key, [
                            'widget_total_themes',
                            'widget_total_users',
                            'widget_total_plugins',
                            'widget_total_pages',
                        ]) && $widget['type'] == 'stats') {
                        Arr::forget($widgets, $key);
                    }
                }

                return $widgets;
            }, 150);

            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function ($widgets, $widgetSettings) {
                $items = Order::query()->where('is_finished', 1)->count();

                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('orders.index')
                    ->setTitle(trans('plugins/ecommerce::order.menu'))
                    ->setKey('widget_total_1')
                    ->setIcon('fas fa-users')
                    ->setColor('#32c5d2')
                    ->setStatsTotal($items)
                    ->setRoute(route('orders.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->init($widgets, $widgetSettings);
            }, 2, 2);

            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function ($widgets, $widgetSettings) {
                $items = Product::query()
                    ->where('is_variation', false)
                    ->wherePublished()
                    ->count();

                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('products.index')
                    ->setTitle(trans('plugins/ecommerce::products.name'))
                    ->setKey('widget_total_2')
                    ->setIcon('ti ti-shopping-cart')
                    ->setColor('#1280f5')
                    ->setStatsTotal($items)
                    ->setRoute(route('products.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->init($widgets, $widgetSettings);
            }, 3, 2);

            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function ($widgets, $widgetSettings) {
                $items = Customer::query()->count();

                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('customers.index')
                    ->setTitle(trans('plugins/ecommerce::customer.name'))
                    ->setKey('widget_total_3')
                    ->setIcon('ti ti-user')
                    ->setColor('#75b6f9')
                    ->setStatsTotal($items)
                    ->setRoute(route('customers.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->init($widgets, $widgetSettings);
            }, 4, 2);

            add_filter(DASHBOARD_FILTER_ADMIN_LIST, function ($widgets, $widgetSettings) {
                $items = Review::query()->wherePublished()->count();

                return (new DashboardWidgetInstance())
                    ->setType('stats')
                    ->setPermission('reviews.index')
                    ->setTitle(trans('plugins/ecommerce::review.name'))
                    ->setKey('widget_total_4')
                    ->setIcon('ti ti-messages')
                    ->setColor('#074f9d')
                    ->setStatsTotal($items)
                    ->setRoute(route('reviews.index'))
                    ->setColumn('col-12 col-md-6 col-lg-3')
                    ->init($widgets, $widgetSettings);
            }, 5, 2);
        });

        $this->app['events']->listen(RouteMatched::class, function () {
            if (defined('PAYMENT_FILTER_PAYMENT_PARAMETERS')) {
                add_filter(PAYMENT_FILTER_PAYMENT_PARAMETERS, function ($html) {
                    if (! auth('customer')->check()) {
                        return $html;
                    }

                    return $html . Form::hidden('customer_id', auth('customer')->id())
                            ->toHtml() . Form::hidden('customer_type', Customer::class)->toHtml();
                }, 123);
            }

            if (defined('PAYMENT_FILTER_REDIRECT_URL')) {
                add_filter(PAYMENT_FILTER_REDIRECT_URL, function ($checkoutToken) {
                    return route('public.checkout.success', $checkoutToken ?: OrderHelper::getOrderSessionToken());
                }, 123);
            }

            if (defined('PAYMENT_FILTER_CANCEL_URL')) {
                add_filter(PAYMENT_FILTER_CANCEL_URL, function ($checkoutToken) {
                    return route(
                        'public.checkout.information',
                        [$checkoutToken ?: OrderHelper::getOrderSessionToken()] + [
                            'error' => true,
                            'error_type' => 'payment',
                        ]
                    );
                }, 123);
            }

            if (defined('PAYMENT_ACTION_PAYMENT_PROCESSED')) {
                add_action(PAYMENT_ACTION_PAYMENT_PROCESSED, function (array $data) {
                    $orderIds = (array) $data['order_id'];

                    if (! $orderIds) {
                        return;
                    }

                    $orders = Order::query()->whereIn('id', $orderIds)->get();

                    foreach ($orders as $order) {
                        $data['amount'] = $order->amount;
                        $data['order_id'] = $order->id;
                        $data['currency'] = strtoupper(cms_currency()->getDefaultCurrency()->title);

                        PaymentHelper::storeLocalPayment($data);
                    }

                    OrderHelper::processOrder($orders->pluck('id')->all(), $data['charge_id']);
                }, 123);
            }

            if (is_plugin_active('payment')) {
                CODPaymentMethodForm::extend(function (CODPaymentMethodForm $form) {
                    $form->add(
                        get_payment_setting_key('minimum_amount', PaymentMethodEnum::COD),
                        NumberField::class,
                        NumberFieldOption::make()
                            ->label(
                                trans(
                                    'plugins/ecommerce::setting.payment_method_cod_minimum_amount',
                                    ['currency' => get_application_currency()->title]
                                )
                            )
                            ->value(setting('payment_cod_minimum_amount', 0))
                            ->toArray()
                    );
                });

                BankTransferPaymentMethodForm::extend(function (BankTransferPaymentMethodForm $form) {
                    $form->add(
                        get_payment_setting_key(
                            'display_bank_info_at_the_checkout_success_page',
                            PaymentMethodEnum::BANK_TRANSFER
                        ),
                        OnOffCheckboxField::class,
                        OnOffFieldOption::make()
                            ->label(trans('plugins/ecommerce::setting.display_bank_info_at_the_checkout_success_page'))
                            ->value(
                                setting('payment_bank_transfer_display_bank_info_at_the_checkout_success_page', false)
                            )
                            ->toArray()
                    );
                });
            }

            add_filter('core_request_rules', function (array $rules, BaseRequest $request) {
                if ($request instanceof PaymentMethodRequest) {
                    $rules = match ($request->input('type')) {
                        PaymentMethodEnum::COD => [
                            ...$rules,
                            get_payment_setting_key('minimum_amount', PaymentMethodEnum::COD) => [
                                'nullable',
                                'numeric',
                                'min:0',
                            ],
                        ],
                        PaymentMethodEnum::BANK_TRANSFER => [
                            ...$rules,
                            get_payment_setting_key(
                                'display_bank_info_at_the_checkout_success_page',
                                PaymentMethodEnum::BANK_TRANSFER
                            ) => [new OnOffRule()],
                        ],
                        default => $rules,
                    };
                }

                return $rules;
            }, 999, 2);

            if (config('packages.theme.general.enable_custom_js')) {
                add_filter('ecommerce_checkout_header', function ($html) {
                    return $html . ThemeSupport::getCustomJS('header');
                }, 15);

                if (setting('custom_body_js')) {
                    add_filter('ecommerce_checkout_body', function ($html) {
                        return $html . ThemeSupport::getCustomJS('body');
                    }, 15);
                }

                if (setting('custom_footer_js')) {
                    add_filter('ecommerce_checkout_footer', function ($html) {
                        return $html . ThemeSupport::getCustomJS('footer');
                    }, 15);
                }
            }

            add_filter('ecommerce_checkout_header', function ($html) {
                $customCSSFile = Theme::getStyleIntegrationPath();

                if (File::exists($customCSSFile)) {
                    $html .= Html::style(
                        Theme::asset()
                            ->url('css/style.integration.css?v=' . filectime($customCSSFile))
                    );
                }

                return $html;
            }, 15);

            add_filter([THEME_FRONT_HEADER, 'ecommerce_checkout_header'], function ($html) {
                $pixelID = get_ecommerce_setting('facebook_pixel_id');

                if (BaseHelper::hasDemoModeEnabled() || ! $pixelID) {
                    return $html;
                }

                return $html . view('plugins/ecommerce::orders.partials.facebook-pixel', compact('pixelID'))->render();
            }, 16);

            add_filter('ecommerce_checkout_header', function ($html) {
                return $html . ThemeSupport::renderGoogleTagManagerScript();
            }, 17);

            if (
                defined('FAQ_MODULE_SCREEN_NAME')
                && config('plugins.ecommerce.general.enable_faq_in_product_details', false)
            ) {
                add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
                    if (
                        ! $object
                        || $context != 'advanced'
                        || ! is_in_admin()
                        || ! $object instanceof Product
                        || ! in_array(Route::currentRouteName(), [
                            'products.create',
                            'products.edit',
                            'marketplace.vendor.products.create',
                            'marketplace.vendor.products.edit',
                        ])
                    ) {
                        return false;
                    }

                    MetaBox::addMetaBox('faq_schema_config_wrapper', __('Product FAQs'), function () {
                        return (new FaqSupport())->renderMetaBox(func_get_args()[0] ?? null);
                    }, $object::class, $context);

                    return true;
                }, 139, 2);
            }

            add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object) {
                if (
                    ! defined('FAQ_MODULE_SCREEN_NAME') ||
                    ! $object instanceof Product ||
                    ! config('plugins.ecommerce.general.enable_faq_in_product_details', false)
                ) {
                    return;
                }

                $schemaItems = new FaqCollection();

                foreach ($object->faq_items as $item) {
                    $schemaItems->push(
                        new FaqItem(BaseHelper::clean($item[0]['value']), BaseHelper::clean(strip_tags($item[1]['value'])))
                    );
                }

                app(FaqContract::class)->registerSchema($schemaItems);
            }, 139, 2);

            add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object) {
                add_filter(THEME_FRONT_HEADER, function (?string $html) use ($object) {
                    if (! $object instanceof Product) {
                        return $html;
                    }

                    $schema = [
                        '@context' => 'https://schema.org',
                        '@type' => 'Product',
                        'category' => trim(implode(', ', $object->categories->pluck('name')->all())),
                        'url' => $object->url,
                        'description' => Str::limit(
                            BaseHelper::clean(strip_tags($object->description ?: $object->content)),
                            255
                        ),
                        'name' => BaseHelper::clean($object->name),
                        'image' => RvMedia::getImageUrl($object->image, null, false, RvMedia::getDefaultImage()),
                        'sku' => $object->sku ?: $object->getKey(),
                        'offers' => [
                            '@type' => 'Offer',
                            'price' => format_price($object->front_sale_price, null, true),
                            'priceCurrency' => strtoupper(cms_currency()->getDefaultCurrency()->title),
                            'priceValidUntil' => Carbon::now()->addDay()->toDateString(),
                            'itemCondition' => 'https://schema.org/NewCondition',
                            'availability' => $object->isOutOfStock(
                            ) ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
                            'url' => $object->url,
                        ],
                    ];

                    if ($object->brand->name) {
                        $schema['brand'] = [
                            '@type' => 'Brand',
                            'name' => $object->brand->name,
                        ];
                    }

                    if (EcommerceHelper::isReviewEnabled() && $object->reviews_count > 0) {
                        $schema['aggregateRating'] = [
                            '@type' => 'AggregateRating',
                            'ratingValue' => $object->reviews_avg ? number_format($object->reviews_avg, 2) : '5.00',
                            'reviewCount' => $object->reviews_count,
                        ];

                        $bestRating = $object->reviews->sortByDesc('star')->first();

                        if ($bestRating) {
                            $schema['review'] = [
                                '@type' => 'Review',
                                'reviewRating' => [
                                    '@type' => 'Rating',
                                    'ratingValue' => number_format($object->reviews_avg, 2) ?: '5.00',
                                    'bestRating' => $bestRating->star,
                                ],
                                'author' => [
                                    '@type' => 'Person',
                                    'name' => $bestRating->user_name,
                                ],
                            ];
                        }
                    }

                    $schema = json_encode($schema);

                    return $html . Html::tag('script', $schema, ['type' => 'application/ld+json'])->toHtml();
                });
            }, 139, 2);
        });

        add_action(BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION, function ($request, $data = null) {
            if (
                ! $data instanceof Product
                || Route::currentRouteName() != 'products.edit'
                || ! FlashSaleFacade::isEnabled()
            ) {
                return;
            }

            $flashSale = null;

            $flashSalePrice = $data->getFlashSalePrice();

            if ($flashSalePrice != $data->price) {
                $flashSale = FlashSaleFacade::getFacadeRoot()->flashSaleForProduct($data);

                if ($flashSale) {
                    $flashSale = FlashSale::query()->find($flashSale->pivot->flash_sale_id);
                }
            }

            $discount = null;

            $discountPrice = $data->getDiscountPrice();

            if ($discountPrice != $data->price) {
                if ($discountPrice < $flashSalePrice) {
                    $flashSale = null;

                    $discount = Discount::getFacadeRoot()->promotionForProduct([$data->getKey()]);
                }
            }

            if ($flashSale || $discount) {
                echo view(
                    'plugins/ecommerce::products.partials.product-price-warning',
                    compact('flashSale', 'discount', 'data')
                )->render();
            }
        }, 145, 2);

        add_action(
            BASE_ACTION_TOP_FORM_CONTENT_NOTIFICATION,
            function (Request $request, Model|string|null $data = null) {
                if (! EcommerceHelper::isEnableEmailVerification()) {
                    return;
                }

                if (! $data instanceof Customer || Route::currentRouteName() !== 'customers.edit') {
                    return;
                }

                if (Auth::user()->hasPermission('customers.edit')) {
                    echo view(
                        'plugins/ecommerce::customers.notification',
                        compact('data')
                    )->render();
                }
            },
            45,
            2
        );

        add_filter(FILTER_ECOMMERCE_PROCESS_PAYMENT, function (array $data, Request $request) {
            session()->put('selected_payment_method', $data['type']);

            $orderIds = (array) $request->input('order_id', []);

            $request->merge([
                'name' => trans('plugins/payment::payment.payment_description', [
                    'order_id' => implode(', #', $orderIds),
                    'site_url' => $request->getHost(),
                ]),
                'amount' => $data['amount'],
            ]);

            $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

            switch ($request->input('payment_method')) {
                case PaymentMethodEnum::COD:

                    $minimumOrderAmount = setting('payment_cod_minimum_amount', 0);

                    if ($minimumOrderAmount > Cart::instance('cart')->rawSubTotal()) {
                        $data['error'] = true;
                        $data['message'] = __(
                            'Minimum order amount to use COD (Cash On Delivery) payment method is :amount, you need to buy more :more to place an order!',
                            [
                                'amount' => format_price($minimumOrderAmount),
                                'more' => format_price($minimumOrderAmount - Cart::instance('cart')->rawSubTotal()),
                            ]
                        );

                        break;
                    }

                    $data['charge_id'] = $this->app->make(CodPaymentService::class)->execute($paymentData);

                    break;

                case PaymentMethodEnum::BANK_TRANSFER:

                    $data['charge_id'] = $this->app->make(BankTransferPaymentService::class)->execute($paymentData);

                    break;

                default:
                    $data = apply_filters(PAYMENT_FILTER_AFTER_POST_CHECKOUT, $data, $request);

                    break;
            }

            return $data;
        }, 120, 2);

        add_filter('payment-transaction-card-actions', function ($data, $payment) {
            $invoice = Invoice::query()->where('payment_id', $payment->id)->first();

            if (! $invoice) {
                return $data;
            }

            $button = view('plugins/ecommerce::invoices.invoice-buttons', compact('invoice'))->render();

            return $data . $button;
        }, 3, 2);

        if (defined('PAYMENT_FILTER_PAYMENT_DATA')) {
            add_filter(PAYMENT_FILTER_PAYMENT_DATA, function (array $data, Request $request) {
                $orderIds = (array) $request->input('order_id', []);

                $orders = Order::query()
                    ->whereIn('id', $orderIds)
                    ->with(['address', 'products'])
                    ->get();

                $products = [];

                foreach ($orders as $order) {
                    foreach ($order->products as $product) {
                        $products[] = [
                            'id' => $product->product_id,
                            'name' => $product->product_name,
                            'image' => RvMedia::getImageUrl($product->product_image),
                            'price' => $this->convertOrderAmount($product->price),
                            'price_per_order' => $this->convertOrderAmount(
                                ($product->price * $product->qty)
                                + ($order->tax_amount / $order->products->count())
                                - ($order->discount_amount / $order->products->count())
                            ),
                            'qty' => $product->qty,
                        ];
                    }
                }

                $firstOrder = $orders->sortByDesc('created_at')->first();

                $address = $firstOrder->address;

                return [
                    'amount' => $this->convertOrderAmount((float) $orders->sum('amount')),
                    'shipping_amount' => $this->convertOrderAmount((float) $orders->sum('shipping_amount')),
                    'shipping_method' => $firstOrder->shipping_method->label(),
                    'tax_amount' => $this->convertOrderAmount((float) $orders->sum('tax_amount')),
                    'discount_amount' => $this->convertOrderAmount((float) $orders->sum('discount_amount')),
                    'currency' => strtoupper(get_application_currency()->title),
                    'order_id' => $orderIds,
                    'description' => trans('plugins/payment::payment.payment_description', [
                        'order_id' => implode(', #', $orderIds),
                        'site_url' => $request->getHost(),
                    ]),
                    'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
                    'customer_type' => Customer::class,
                    'return_url' => PaymentHelper::getCancelURL(),
                    'callback_url' => PaymentHelper::getRedirectURL(),
                    'products' => $products,
                    'orders' => $orders,
                    'address' => [
                        'name' => $address->name ?: $firstOrder->user->name,
                        'email' => $address->email ?: $firstOrder->user->email,
                        'phone' => $address->phone ?: $firstOrder->user->phone,
                        'country' => $address->country_name,
                        'state' => $address->state_name,
                        'city' => $address->city_name,
                        'address' => $address->address,
                        'zip_code' => $address->zip_code,
                    ],
                    'checkout_token' => OrderHelper::getOrderSessionToken(),
                ];
            }, 120, 2);
        }

        if (function_exists('add_shortcode')) {
            add_shortcode(
                'recently-viewed-products',
                __('Customer Recently Viewed Products'),
                __('Customer Recently Viewed Products'),
                function (Shortcode $shortcode) {
                    if (! EcommerceHelper::isEnabledCustomerRecentlyViewedProducts()) {
                        return '';
                    }

                    $queryParams = array_merge([
                        'paginate' => [
                            'per_page' => 12,
                            'current_paged' => request()->integer('page', 1) ?: 1,
                        ],
                        'with' => ['slugable'],
                    ], EcommerceHelper::withReviewsParams());

                    $productRepository = $this->app->make(ProductInterface::class);

                    if (auth('customer')->check()) {
                        $products = $productRepository->getProductsRecentlyViewed(auth('customer')->id(), $queryParams);
                    } else {
                        $products = new LengthAwarePaginator([], 0, 12);

                        $itemIds = collect(Cart::instance('recently_viewed')->content())
                            ->sortBy([['updated_at', 'desc']])
                            ->pluck('id')
                            ->all();

                        if ($itemIds) {
                            $products = $productRepository->getProductsByIds($itemIds, $queryParams);
                        }
                    }

                    $productItemView = EcommerceHelper::viewPath('includes.product-item');

                    return view(
                        EcommerceHelper::viewPath('shortcodes.recently-viewed-products'),
                        compact('products', 'shortcode', 'productItemView')
                    )->render();
                }
            );

            if (EcommerceHelper::isEnabledCustomerRecentlyViewedProducts()) {
                shortcode()->setAdminConfig('recently-viewed-products', function (array $attributes) {
                    return ShortcodeForm::createFromArray($attributes)
                        ->withLazyLoading()
                        ->add('title', 'text', [
                            'label' => trans('core/base::forms.title'),
                        ]);
                });
            }
        }

        add_action(INVOICE_PAYMENT_CREATED, function (Invoice $invoice) {
            try {
                $invoicePath = InvoiceHelper::generateInvoice($invoice);

                EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
                    ->setVariableValues([
                        'customer_name' => $invoice->customer_name,
                        'invoice_code' => $invoice->code,
                        'invoice_link' => $invoice->order?->user->id ? route(
                            'customer.invoices.show',
                            $invoice->getKey()
                        ) : null,
                    ])
                    ->sendUsingTemplate('invoice-payment-created', $invoice->customer_email, [
                        'attachments' => [$invoicePath],
                    ]);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        });

        add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, [$this, 'handleSingleView'], 30);

        if (defined('ACTION_AFTER_UPDATE_PAYMENT')) {
            add_action(ACTION_AFTER_UPDATE_PAYMENT, function ($request, $payment) {
                if (
                    in_array($payment->payment_channel, [PaymentMethodEnum::COD, PaymentMethodEnum::BANK_TRANSFER])
                    && $request->input('status') == PaymentStatusEnum::COMPLETED
                    && EcommerceHelper::isEnabledSupportDigitalProducts()
                ) {
                    /**
                     * @var Order $order
                     */
                    $order = Order::query()->where('id', $payment->order_id)->with('products')->first();

                    if ($order) {
                        OrderHelper::confirmPayment($order);
                    }
                }
            }, 123, 2);
        }

        $this->app->make(GoogleTagManager::class)->pushScriptsToFooter();
    }

    protected function convertOrderAmount(float $amount): float
    {
        $currentCurrency = get_application_currency();

        if ($currentCurrency->is_default) {
            return $amount;
        }

        return (float) format_price($amount * $currentCurrency->exchange_rate, $currentCurrency, true);
    }

    public function addThemeOptions(): void
    {
        $fields = [];

        foreach (EcommerceHelper::getDefaultPageSlug() as $key => $value) {
            $fields[] = [
                'id' => sprintf('ecommerce_%s_page_slug', $key),
                'type' => 'text',
                'label' => trans(
                    'plugins/ecommerce::ecommerce.theme_options.page_slug_name',
                    ['page' => trans("plugins/ecommerce::ecommerce.theme_options.page_slugs.$key")]
                ),
                'attributes' => [
                    'name' => sprintf('ecommerce_%s_page_slug', $key),
                    'value' => $value,
                    'options' => [
                        'class' => 'form-control',
                    ],
                ],
                'helper' => trans(
                    'plugins/ecommerce::ecommerce.theme_options.page_slug_description',
                    [
                        'slug' => Html::link(
                            url(EcommerceHelper::getPageSlug($key)),
                            attributes: ['target' => '_blank']
                        ),
                        'default' => "<code>$value</code>",
                    ]
                ),
            ];
        }

        theme_option()
            ->setSection([
                'title' => trans('plugins/ecommerce::ecommerce.theme_options.name'),
                'id' => 'opt-text-subsection-ecommerce',
                'subsection' => true,
                'icon' => 'ti ti-shopping-bag',
                'fields' => [
                    [
                        'id' => 'number_of_products_per_page',
                        'type' => 'number',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.number_products_per_page'),
                        'attributes' => [
                            'name' => 'number_of_products_per_page',
                            'value' => 12,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'number_of_cross_sale_product',
                        'type' => 'number',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.number_of_cross_sale_product'),
                        'attributes' => [
                            'name' => 'number_of_cross_sale_product',
                            'value' => 4,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'max_filter_price',
                        'type' => 'number',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.max_price_filter'),
                        'attributes' => [
                            'name' => 'max_filter_price',
                            'value' => EcommerceHelper::getProductMaxPrice(),
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'logo_in_the_checkout_page',
                        'type' => 'mediaImage',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.logo_in_the_checkout_page'),
                        'attributes' => [
                            'name' => 'logo_in_the_checkout_page',
                            'value' => null,
                            'attributes' => [
                                'allow_thumb' => false,
                            ],
                        ],
                    ],
                    [
                        'id' => 'login_background',
                        'type' => 'mediaImage',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.login_background_image'),
                        'attributes' => [
                            'name' => 'login_background',
                        ],
                    ],
                    [
                        'id' => 'register_background',
                        'type' => 'mediaImage',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.register_background_image'),
                        'attributes' => [
                            'name' => 'register_background',
                        ],
                    ],
                    [
                        'id' => 'ecommerce_term_and_privacy_policy_url',
                        'type' => 'text',
                        'label' => trans('plugins/ecommerce::ecommerce.theme_options.term_and_privacy_policy_url'),
                        'attributes' => [
                            'name' => 'ecommerce_term_and_privacy_policy_url',
                            'value' => null,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ])
            ->setSection([
                'title' => trans('plugins/ecommerce::ecommerce.theme_options.slug_name'),
                'description' => trans('plugins/ecommerce::ecommerce.theme_options.slug_description'),
                'id' => 'opt-text-subsection-ecommerce-slug',
                'subsection' => true,
                'icon' => 'ti ti-link',
                'fields' => $fields,
            ]);
    }

    public function registerMenuOptions(): bool
    {
        if (Auth::user()->hasPermission('brands.index')) {
            Menu::registerMenuOptions(Brand::class, trans('plugins/ecommerce::brands.menu'));
        }

        if (Auth::user()->hasPermission('product-categories.index')) {
            Menu::registerMenuOptions(ProductCategory::class, trans('plugins/ecommerce::product-categories.menu'));
        }

        return true;
    }

    public function registerDashboardWidgets(array $widgets, Collection $widgetSettings): array
    {
        if (! Auth::user()->hasPermission('ecommerce.report.index')) {
            return $widgets;
        }

        if (! is_plugin_active('payment')) {
            return $widgets;
        }

        Assets::addScriptsDirectly(['vendor/core/plugins/ecommerce/js/dashboard-widgets.js']);

        return (new DashboardWidgetInstance())
            ->setPermission('ecommerce.report.index')
            ->setKey('widget_ecommerce_report_general')
            ->setTitle(trans('plugins/ecommerce::ecommerce.name'))
            ->setIcon('fas fa-shopping-basket')
            ->setColor('#7ad03a')
            ->setRoute(route('ecommerce.report.dashboard-widget.general'))
            ->setBodyClass('scroll-table')
            ->setColumn('col-md-6 col-sm-6')
            ->init($widgets, $widgetSettings);
    }

    public function registerTopHeaderNotification(?string $options): ?string
    {
        try {
            if (Auth::user()->hasPermission('orders.edit')) {
                $orders = Order::query()
                    ->where([
                        'status' => BaseStatusEnum::PENDING,
                        'is_finished' => 1,
                    ])
                    ->orderByDesc('created_at')
                    ->with(['address', 'user'])
                    ->paginate(10);

                if ($orders->count() == 0) {
                    return $options;
                }

                return $options . view('plugins/ecommerce::orders.notification', compact('orders'))->render();
            }
        } catch (Exception) {
            return $options;
        }

        return $options;
    }

    public function getPendingOrders(int|string|null $number, string $menuId): string
    {
        switch ($menuId) {
            case 'cms-plugins-ecommerce':
                if (! Auth::user()->hasPermission('orders.index')) {
                    return $number;
                }

                return view('core/base::partials.navbar.badge-count', ['class' => 'ecommerce-count'])->render();

            case 'cms-plugins-ecommerce-order':
                if (! Auth::user()->hasPermission('orders.index')) {
                    return $number;
                }

                return view('core/base::partials.navbar.badge-count', ['class' => 'pending-orders'])->render();

            case 'cms-plugins-ecommerce-order-return':
                if (! Auth::user()->hasPermission('orders.index')) {
                    return $number;
                }

                return view('core/base::partials.navbar.badge-count', ['class' => 'pending-order-returns'])->render();

            case 'cms-ecommerce-review':
                if (! Auth::user()->hasPermission('reviews.index')) {
                    return $number;
                }

                $pendingCount = Review::query()->where('status', BaseStatusEnum::PENDING)->count();

                if ($pendingCount > 0) {
                    return BaseHelper::renderBadge(number_format($pendingCount));
                }

                break;
        }

        return $number;
    }

    public function getMenuItemCount(array $data = []): array
    {
        if (Auth::check() && Auth::user()->hasPermission('orders.index')) {
            $pendingOrders = Order::query()
                ->where([
                    'status' => BaseStatusEnum::PENDING,
                    'is_finished' => 1,
                ])
                ->count();

            $data[] = [
                'key' => 'pending-orders',
                'value' => $pendingOrders,
            ];

            $pendingOrderReturns = OrderReturn::query()
                ->whereIn('return_status', [OrderReturnStatusEnum::PENDING, OrderReturnStatusEnum::PROCESSING])
                ->count();

            $data[] = [
                'key' => 'pending-order-returns',
                'value' => $pendingOrderReturns,
            ];

            $data[] = [
                'key' => 'ecommerce-count',
                'value' => $pendingOrders + $pendingOrderReturns,
            ];
        }

        return $data;
    }

    public function renderProductsInCheckoutPage(Collection|string $products): string|Collection
    {
        if ($products instanceof Collection) {
            return view('plugins/ecommerce::orders.checkout.products', compact('products'))->render();
        }

        return $products;
    }

    public function handleSingleView(Slug|array $slug): BaseHttpResponse|array|Slug|RedirectResponse
    {
        return app(HandleFrontPages::class)->handle($slug);
    }
}
