<?php

namespace Botble\Ecommerce\Providers;

use Botble\Api\Facades\ApiHelper;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\DataSynchronize\PanelSections\ExportPanelSection;
use Botble\DataSynchronize\PanelSections\ImportPanelSection;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\Currency as CurrencyFacade;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\FlashSale as FlashSaleFacade;
use Botble\Ecommerce\Facades\InvoiceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Facades\OrderReturnHelper;
use Botble\Ecommerce\Facades\ProductCategoryHelper;
use Botble\Ecommerce\Forms\Fronts\Auth\ForgotPasswordForm;
use Botble\Ecommerce\Forms\Fronts\Auth\LoginForm;
use Botble\Ecommerce\Forms\Fronts\Auth\RegisterForm;
use Botble\Ecommerce\Forms\Fronts\Auth\ResetPasswordForm;
use Botble\Ecommerce\GoogleAnalytics\GoogleTagManager;
use Botble\Ecommerce\Http\Middleware\CaptureCouponMiddleware;
use Botble\Ecommerce\Http\Middleware\CaptureFootprintsMiddleware;
use Botble\Ecommerce\Http\Middleware\RedirectIfCustomer;
use Botble\Ecommerce\Http\Middleware\RedirectIfNotCustomer;
use Botble\Ecommerce\Http\Requests\Fronts\Auth\ForgotPasswordRequest;
use Botble\Ecommerce\Http\Requests\Fronts\Auth\ResetPasswordRequest;
use Botble\Ecommerce\Http\Requests\LoginRequest;
use Botble\Ecommerce\Http\Requests\RegisterRequest;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Currency;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Models\GlobalOption;
use Botble\Ecommerce\Models\GlobalOptionValue;
use Botble\Ecommerce\Models\GroupedProduct;
use Botble\Ecommerce\Models\Invoice;
use Botble\Ecommerce\Models\Option;
use Botble\Ecommerce\Models\OptionValue;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Ecommerce\Models\OrderReturnItem;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductAttribute;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductCollection;
use Botble\Ecommerce\Models\ProductLabel;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Models\ShipmentHistory;
use Botble\Ecommerce\Models\Shipping;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Ecommerce\Models\ShippingRuleItem;
use Botble\Ecommerce\Models\StoreLocator;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\Wishlist;
use Botble\Ecommerce\PanelSections\SettingEcommercePanelSection;
use Botble\Ecommerce\Repositories\Eloquent\AddressRepository;
use Botble\Ecommerce\Repositories\Eloquent\BrandRepository;
use Botble\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Botble\Ecommerce\Repositories\Eloquent\CustomerRepository;
use Botble\Ecommerce\Repositories\Eloquent\DiscountRepository;
use Botble\Ecommerce\Repositories\Eloquent\FlashSaleRepository;
use Botble\Ecommerce\Repositories\Eloquent\GlobalOptionRepository;
use Botble\Ecommerce\Repositories\Eloquent\GroupedProductRepository;
use Botble\Ecommerce\Repositories\Eloquent\InvoiceRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderHistoryRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderProductRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderReturnItemRepository;
use Botble\Ecommerce\Repositories\Eloquent\OrderReturnRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductCollectionRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductLabelRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductTagRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductVariationItemRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Botble\Ecommerce\Repositories\Eloquent\ReviewRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShipmentHistoryRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShipmentRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShippingRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShippingRuleItemRepository;
use Botble\Ecommerce\Repositories\Eloquent\ShippingRuleRepository;
use Botble\Ecommerce\Repositories\Eloquent\StoreLocatorRepository;
use Botble\Ecommerce\Repositories\Eloquent\TaxRepository;
use Botble\Ecommerce\Repositories\Eloquent\WishlistRepository;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Ecommerce\Repositories\Interfaces\GlobalOptionInterface;
use Botble\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\InvoiceInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductLabelInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Ecommerce\Repositories\Interfaces\TaxInterface;
use Botble\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Botble\Ecommerce\Services\ExchangeRates\ApiLayerExchangeRateService;
use Botble\Ecommerce\Services\ExchangeRates\ExchangeRateInterface;
use Botble\Ecommerce\Services\ExchangeRates\OpenExchangeRatesService;
use Botble\Ecommerce\Services\Footprints\Footprinter;
use Botble\Ecommerce\Services\Footprints\FootprinterInterface;
use Botble\Ecommerce\Services\Footprints\TrackingFilter;
use Botble\Ecommerce\Services\Footprints\TrackingFilterInterface;
use Botble\Ecommerce\Services\Footprints\TrackingLogger;
use Botble\Ecommerce\Services\Footprints\TrackingLoggerInterface;
use Botble\Ecommerce\Services\Products\ProductCrossSalePriceService;
use Botble\Ecommerce\Services\Products\ProductPriceService;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Payment\Models\Payment;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\SocialLogin\Facades\SocialService;
use Botble\Theme\Events\ThemeRoutingBeforeEvent;
use Botble\Theme\Facades\SiteMapManager;
use Botble\Theme\FormFrontManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EcommerceServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'auth.guards.customer' => [
                'driver' => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table' => 'ec_customer_password_resets',
                'expire' => 60,
            ],
        ]);

        $this->app->bind(ProductInterface::class, function () {
            return new ProductRepository(new Product());
        });

        $this->app->bind(ProductCategoryInterface::class, function () {
            return new ProductCategoryRepository(new ProductCategory());
        });

        $this->app->bind(ProductTagInterface::class, function () {
            return new ProductTagRepository(new ProductTag());
        });

        $this->app->bind(GlobalOptionInterface::class, function () {
            return new GlobalOptionRepository(new GlobalOption());
        });

        $this->app->bind(BrandInterface::class, function () {
            return new BrandRepository(new Brand());
        });

        $this->app->bind(ProductCollectionInterface::class, function () {
            return new ProductCollectionRepository(new ProductCollection());
        });

        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyRepository(new Currency());
        });

        $this->app->bind(ProductAttributeSetInterface::class, function () {
            return new ProductAttributeSetRepository(new ProductAttributeSet());
        });

        $this->app->bind(ProductAttributeInterface::class, function () {
            return new ProductAttributeRepository(new ProductAttribute());
        });

        $this->app->bind(ProductVariationInterface::class, function () {
            return new ProductVariationRepository(new ProductVariation());
        });

        $this->app->bind(ProductVariationItemInterface::class, function () {
            return new ProductVariationItemRepository(new ProductVariationItem());
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxRepository(new Tax());
        });

        $this->app->bind(ReviewInterface::class, function () {
            return new ReviewRepository(new Review());
        });

        $this->app->bind(ShippingInterface::class, function () {
            return new ShippingRepository(new Shipping());
        });

        $this->app->bind(ShippingRuleInterface::class, function () {
            return new ShippingRuleRepository(new ShippingRule());
        });

        $this->app->bind(ShippingRuleItemInterface::class, function () {
            return new ShippingRuleItemRepository(new ShippingRuleItem());
        });

        $this->app->bind(ShipmentInterface::class, function () {
            return new ShipmentRepository(new Shipment());
        });

        $this->app->bind(ShipmentHistoryInterface::class, function () {
            return new ShipmentHistoryRepository(new ShipmentHistory());
        });

        $this->app->bind(OrderInterface::class, function () {
            return new OrderRepository(new Order());
        });

        $this->app->bind(OrderHistoryInterface::class, function () {
            return new OrderHistoryRepository(new OrderHistory());
        });

        $this->app->bind(OrderProductInterface::class, function () {
            return new OrderProductRepository(new OrderProduct());
        });

        $this->app->bind(OrderAddressInterface::class, function () {
            return new OrderAddressRepository(new OrderAddress());
        });

        $this->app->bind(OrderReturnInterface::class, function () {
            return new OrderReturnRepository(new OrderReturn());
        });

        $this->app->bind(OrderReturnItemInterface::class, function () {
            return new OrderReturnItemRepository(new OrderReturnItem());
        });

        $this->app->bind(DiscountInterface::class, function () {
            return new DiscountRepository(new Discount());
        });

        $this->app->bind(WishlistInterface::class, function () {
            return new WishlistRepository(new Wishlist());
        });

        $this->app->bind(AddressInterface::class, function () {
            return new AddressRepository(new Address());
        });
        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerRepository(new Customer());
        });

        $this->app->bind(GroupedProductInterface::class, function () {
            return new GroupedProductRepository(new GroupedProduct());
        });

        $this->app->bind(StoreLocatorInterface::class, function () {
            return new StoreLocatorRepository(new StoreLocator());
        });

        $this->app->bind(FlashSaleInterface::class, function () {
            return new FlashSaleRepository(new FlashSale());
        });

        $this->app->bind(ProductLabelInterface::class, function () {
            return new ProductLabelRepository(new ProductLabel());
        });

        $this->app->bind(InvoiceInterface::class, function () {
            return new InvoiceRepository(new Invoice());
        });

        $this->app->bind(TrackingFilterInterface::class, function ($app) {
            return $app->make(TrackingFilter::class);
        });

        $this->app->bind(TrackingLoggerInterface::class, function ($app) {
            return $app->make(TrackingLogger::class);
        });

        $this->app->singleton(FootprinterInterface::class, function ($app) {
            return $app->make(Footprinter::class);
        });

        $this->app->singleton(ExchangeRateInterface::class, function () {
            if (get_ecommerce_setting('exchange_rate_api_provider') === 'api_layer') {
                return new ApiLayerExchangeRateService();
            }

            return new OpenExchangeRatesService();
        });

        $this->app->singleton(ProductPriceService::class);

        $this->app->singleton(ProductCrossSalePriceService::class);

        $this->app->singleton(GoogleTagManager::class);

        Request::macro('footprint', function () {
            return app(FootprinterInterface::class)->footprint(app()->make('request'));
        });

        $this->setNamespace('plugins/ecommerce')->loadHelpers();

        $loader = AliasLoader::getInstance();
        $loader->alias('Cart', Cart::class);
        $loader->alias('OrderHelper', OrderHelper::class);
        $loader->alias('OrderReturnHelper', OrderReturnHelper::class);
        $loader->alias('EcommerceHelper', EcommerceHelper::class);
        $loader->alias('ProductCategoryHelper', ProductCategoryHelper::class);
        $loader->alias('CurrencyHelper', CurrencyFacade::class);
        $loader->alias('InvoiceHelper', InvoiceHelper::class);
    }

    public function boot(): void
    {
        SlugHelper::registerModule(Product::class, 'Products');
        SlugHelper::registerModule(Brand::class, 'Brands');
        SlugHelper::registerModule(ProductCategory::class, 'Product Categories');
        SlugHelper::registerModule(ProductTag::class, 'Product Tags');
        SlugHelper::setPrefix(Product::class, 'products', true);
        SlugHelper::setPrefix(Brand::class, 'brands', true);
        SlugHelper::setPrefix(ProductTag::class, 'product-tags', true);
        SlugHelper::setPrefix(ProductCategory::class, 'product-categories', true);

        $this->app['events']->listen(ThemeRoutingBeforeEvent::class, function () {
            SiteMapManager::registerKey([
                'product-categories',
                'product-tags',
                'product-brands',
                'products-((?:19|20|21|22)\d{2})-(0?[1-9]|1[012])',
            ]);
        });

        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes([
                'base',
                'product',
                'product-inventory',
                'product-price',
                'tax',
                'review',
                'shipping',
                'order',
                'discount',
                'customer',
                'cart',
                'shipment',
                'wishlist',
                'compare',
                'invoice',
                'setting',
            ])
            ->loadAndPublishConfigurations([
                'general',
                'shipping',
                'order',
                'cart',
                'email',
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAnonymousComponents()
            ->publishAssets();

        if (class_exists('ApiHelper') && ApiHelper::enabled()) {
            ApiHelper::setConfig([
                'model' => Customer::class,
                'guard' => 'customer',
                'password_broker' => 'customers',
                'verify_email' => true,
            ]);
        }

        if (File::exists(storage_path('app/invoices/template.blade.php'))) {
            $this->loadViewsFrom(storage_path('app/invoices'), 'plugins/ecommerce/invoice');
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Product::class, [
                'name',
                'description',
                'content',
            ]);

            if (config('plugins.ecommerce.general.enable_faq_in_product_details', false)) {
                LanguageAdvancedManager::addTranslatableMetaBox('faq_schema_config_wrapper');

                LanguageAdvancedManager::registerModule(
                    Product::class,
                    array_merge(
                        LanguageAdvancedManager::getTranslatableColumns(Product::class),
                        ['faq_schema_config']
                    )
                );
            }

            LanguageAdvancedManager::registerModule(ProductCategory::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductAttribute::class, [
                'title',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('attributes_list');

            LanguageAdvancedManager::registerModule(
                ProductAttribute::class,
                array_merge(
                    LanguageAdvancedManager::getTranslatableColumns(ProductAttribute::class),
                    ['attributes']
                )
            );

            LanguageAdvancedManager::registerModule(ProductAttributeSet::class, [
                'title',
            ]);

            LanguageAdvancedManager::registerModule(Brand::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductCollection::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductLabel::class, [
                'name',
                'description',
            ]);

            if (FlashSaleFacade::isEnabled()) {
                LanguageAdvancedManager::registerModule(FlashSale::class, [
                    'name',
                    'description',
                ]);
            }

            LanguageAdvancedManager::registerModule(ProductTag::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(GlobalOption::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(Option::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(GlobalOptionValue::class, [
                'option_value',
            ]);

            LanguageAdvancedManager::registerModule(OptionValue::class, [
                'option_value',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('product_options_box');

            add_action(LANGUAGE_ADVANCED_ACTION_SAVED, function ($data, $request) {
                switch ($data::class) {
                    case Product::class:
                        $variations = $data->variations()->get();

                        foreach ($variations as $variation) {
                            if (! $variation->product->id) {
                                continue;
                            }

                            LanguageAdvancedManager::save($variation->product, $request);
                        }

                        $options = $request->input('options', []) ?: [];

                        if (! $options) {
                            return;
                        }

                        $newRequest = new Request();

                        $newRequest->replace([
                            'language' => $request->input('language'),
                            'ref_lang' => $request->input('ref_lang'),
                        ]);

                        foreach ($options as $item) {
                            $option = Option::query()->find($item['id']);

                            if ($option) {
                                $newRequest->merge(['name' => $item['name']]);

                                LanguageAdvancedManager::save($option, $newRequest);
                            }

                            $newRequest = new Request();

                            $newRequest->replace([
                                'language' => $request->input('language'),
                                'ref_lang' => $request->input('ref_lang'),
                            ]);

                            foreach ($item['values'] as $value) {
                                if (! isset($value['id']) || ! isset($value['option_value'])) {
                                    continue;
                                }

                                $optionValue = OptionValue::query()->find($value['id']);

                                if ($optionValue) {
                                    $newRequest->merge([
                                        'option_value' => $value['option_value'],
                                    ]);

                                    LanguageAdvancedManager::save($optionValue, $newRequest);
                                }
                            }
                        }

                        break;
                    case ProductAttributeSet::class:

                        $attributes = json_decode($request->input('attributes', '[]'), true) ?: [];

                        if (! $attributes) {
                            break;
                        }

                        $request = new Request();
                        $request->replace([
                            'language' => request()->input('language'),
                            'ref_lang' => request()->input('ref_lang'),
                        ]);

                        foreach ($attributes as $item) {
                            $request->merge([
                                'title' => $item['title'],
                            ]);

                            $attribute = ProductAttribute::query()->find($item['id']);

                            if ($attribute) {
                                LanguageAdvancedManager::save($attribute, $request);
                            }
                        }

                        break;
                    case GlobalOption::class:

                        $option = GlobalOption::query()->find($request->input('id'));

                        if ($option) {
                            LanguageAdvancedManager::save($option, $request);
                        }

                        $options = $request->input('options', []) ?: [];

                        if (! $options) {
                            return;
                        }

                        $newRequest = new Request();

                        $newRequest->replace([
                            'language' => $request->input('language'),
                            'ref_lang' => $request->input('ref_lang'),
                        ]);

                        foreach ($options as $value) {
                            if (! isset($value['id']) || ! isset($value['option_value'])) {
                                continue;
                            }

                            $optionValue = GlobalOptionValue::query()->find($value['id']);

                            if ($optionValue) {
                                $newRequest->merge([
                                    'option_value' => $value['option_value'],
                                ]);

                                LanguageAdvancedManager::save($optionValue, $newRequest);
                            }
                        }

                        break;
                }
            }, 1234, 2);
        }

        $this->app->register(HookServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            $router = $this->app['router'];

            $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
            $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);
            $router->pushMiddlewareToGroup('web', CaptureFootprintsMiddleware::class);
            $router->pushMiddlewareToGroup('web', CaptureCouponMiddleware::class);

            $emailConfig = config('plugins.ecommerce.email', []);

            if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
                Arr::forget($emailConfig, 'templates.download_digital_products');
            }

            if (! EcommerceHelper::isReviewEnabled()) {
                Arr::forget($emailConfig, 'templates.review_products');
            }

            EmailHandler::addTemplateSettings(ECOMMERCE_MODULE_SCREEN_NAME, $emailConfig);
        });

        DashboardMenu::beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce',
                    'priority' => 0,
                    'name' => 'plugins/ecommerce::ecommerce.name',
                    'icon' => 'ti ti-shopping-bag',
                    'url' => fn () => route('products.index'),
                    'permissions' => ['plugins.ecommerce'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-report',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::reports.name',
                    'icon' => 'ti ti-report-analytics',
                    'url' => fn () => route('ecommerce.report.index'),
                    'permissions' => ['ecommerce.report.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-order',
                    'priority' => 10,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::order.menu',
                    'icon' => 'ti ti-truck-delivery',
                    'url' => fn () => route('orders.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-incomplete-order',
                    'priority' => 20,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::order.incomplete_order',
                    'icon' => 'ti ti-basket-cancel',
                    'url' => fn () => route('orders.incomplete-list'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-order-return',
                    'priority' => 30,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::order.order_return',
                    'icon' => 'ti ti-basket-down',
                    'url' => fn () => route('order_returns.index'),
                    'permissions' => ['orders.edit'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-shipping-shipments',
                    'priority' => 40,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::shipping.shipments',
                    'icon' => 'ti ti-truck-loading',
                    'url' => fn () => route('ecommerce.shipments.index'),
                    'permissions' => ['ecommerce.shipments.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-invoice',
                    'priority' => 50,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::invoice.name',
                    'icon' => 'ti ti-file-invoice',
                    'url' => fn () => route('ecommerce.invoice.index'),
                    'permissions' => ['ecommerce.invoice.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-product',
                    'priority' => 60,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::products.name',
                    'icon' => 'ti ti-package',
                    'url' => fn () => route('products.index'),
                    'permissions' => ['products.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-product-price',
                    'priority' => 70,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-prices.name',
                    'icon' => 'ti ti-currency-dollar',
                    'url' => fn () => route('ecommerce.product-prices.index'),
                    'permissions' => ['ecommerce.product-prices.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-product-inventory',
                    'priority' => 80,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-inventory.name',
                    'icon' => 'ti ti-home-check',
                    'url' => fn () => route('ecommerce.product-inventory.index'),
                    'permissions' => ['ecommerce.product-inventory.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-categories',
                    'priority' => 90,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-categories.name',
                    'icon' => 'ti ti-archive',
                    'url' => fn () => route('product-categories.index'),
                    'permissions' => ['product-categories.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-tag',
                    'priority' => 100,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-tag.name',
                    'icon' => 'ti ti-tag',
                    'url' => fn () => route('product-tag.index'),
                    'permissions' => ['product-tag.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-attribute',
                    'priority' => 110,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-attributes.name',
                    'icon' => 'ti ti-album',
                    'url' => fn () => route('product-attribute-sets.index'),
                    'permissions' => ['product-attribute-sets.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-global-options',
                    'priority' => 120,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-option.name',
                    'icon' => 'ti ti-database',
                    'url' => fn () => route('global-option.index'),
                    'permissions' => ['global-option.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-collections',
                    'priority' => 130,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-collections.name',
                    'icon' => 'ti ti-album',
                    'url' => fn () => route('product-collections.index'),
                    'permissions' => ['product-collections.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-label',
                    'priority' => 140,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-label.name',
                    'icon' => 'ti ti-tags',
                    'url' => fn () => route('product-label.index'),
                    'permissions' => ['product-label.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-brands',
                    'priority' => 150,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::brands.name',
                    'icon' => 'ti ti-registered',
                    'url' => fn () => route('brands.index'),
                    'permissions' => ['brands.index'],
                ])
                ->registerItem([
                    'id' => 'cms-ecommerce-review',
                    'priority' => 160,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::review.name',
                    'icon' => 'ti ti-star',
                    'url' => fn () => route('reviews.index'),
                    'permissions' => ['reviews.index'],
                ])
                ->when(FlashSaleFacade::isEnabled(), function (DashboardMenuSupport $dashboardMenu) {
                    $dashboardMenu->registerItem([
                        'id' => 'cms-plugins-flash-sale',
                        'priority' => 170,
                        'parent_id' => 'cms-plugins-ecommerce',
                        'name' => 'plugins/ecommerce::flash-sale.name',
                        'icon' => 'ti ti-bolt',
                        'url' => fn () => route('flash-sale.index'),
                        'permissions' => ['flash-sale.index'],
                    ]);
                })
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-discount',
                    'priority' => 180,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::discount.name',
                    'icon' => 'ti ti-discount',
                    'url' => fn () => route('discounts.index'),
                    'permissions' => ['discounts.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-customer',
                    'priority' => 190,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::customer.name',
                    'icon' => 'ti ti-users',
                    'url' => fn () => route('customers.index'),
                    'permissions' => ['customers.index'],
                ]);
        });

        DashboardMenu::for('customer')->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-customer-overview',
                    'priority' => 10,
                    'name' => __('Overview'),
                    'url' => fn () => route('customer.overview'),
                    'icon' => 'ti ti-home',
                ])
                ->registerItem([
                    'id' => 'cms-customer-orders',
                    'priority' => 30,
                    'name' => __('Orders'),
                    'url' => fn () => route('customer.orders'),
                    'icon' => 'ti ti-shopping-cart',
                ])
                ->when(EcommerceHelper::isReviewEnabled(), function (DashboardMenuSupport $dashboardMenu) {
                    $dashboardMenu->registerItem([
                        'id' => 'cms-customer-product-reviews',
                        'priority' => 40,
                        'name' => __('Reviews'),
                        'url' => fn () => route('customer.product-reviews'),
                        'icon' => 'ti ti-star',
                    ]);
                })
                ->when(EcommerceHelper::isEnabledSupportDigitalProducts(), function (DashboardMenuSupport $dashboardMenu) {
                    $dashboardMenu->registerItem([
                        'id' => 'cms-customer-downloads',
                        'priority' => 50,
                        'name' => __('Downloads'),
                        'url' => fn () => route('customer.downloads'),
                        'icon' => 'ti ti-download',
                    ]);
                })
                ->when(EcommerceHelper::isOrderReturnEnabled(), function (DashboardMenuSupport $dashboardMenu) {
                    $dashboardMenu->registerItem([
                        'id' => 'cms-customer-order-returns',
                        'priority' => 50,
                        'name' => __('Order Return Requests'),
                        'url' => fn () => route('customer.order_returns'),
                        'icon' => 'ti ti-shopping-cart-cancel',
                    ]);
                })
                ->registerItem([
                    'id' => 'cms-customer-address',
                    'priority' => 60,
                    'name' => __('Addresses'),
                    'url' => fn () => route('customer.address'),
                    'icon' => 'ti ti-book',
                ])
                ->registerItem([
                    'id' => 'cms-customer-edit-account',
                    'priority' => 70,
                    'name' => __('Account Settings'),
                    'url' => fn () => route('customer.edit-account'),
                    'icon' => 'ti ti-settings',
                ])
                ->registerItem([
                    'id' => 'cms-customer-logout',
                    'priority' => 999,
                    'name' => __('Logout'),
                    'url' => fn () => route('customer.logout'),
                    'icon' => 'ti ti-logout',
                ]);
        });

        DashboardMenu::default();

        PanelSectionManager::beforeRendering(function () {
            PanelSectionManager::default()
                ->register(SettingEcommercePanelSection::class);
        });

        PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function () {
            PanelSectionManager::default()
                ->registerItem(
                    ExportPanelSection::class,
                    fn () => PanelSectionItem::make('products')
                        ->setTitle(trans('plugins/ecommerce::products.name'))
                        ->withDescription(trans('plugins/ecommerce::products.export.description'))
                        ->withPriority(110)
                        ->withRoute('tools.data-synchronize.export.products.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('products')
                        ->setTitle(trans('plugins/ecommerce::products.name'))
                        ->withDescription(trans('plugins/ecommerce::products.import.description'))
                        ->withPriority(90)
                        ->withRoute('tools.data-synchronize.import.products.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('product-prices')
                        ->setTitle(trans('plugins/ecommerce::product-prices.name'))
                        ->withDescription(trans('plugins/ecommerce::product-prices.import.description'))
                        ->withPriority(100)
                        ->withRoute('ecommerce.product-prices.import.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('product-inventory')
                        ->setTitle(trans('plugins/ecommerce::product-inventory.name'))
                        ->withDescription(trans('plugins/ecommerce::product-inventory.import.description'))
                        ->withPriority(110)
                        ->withRoute('ecommerce.product-inventory.import.index')
                );
        });

        $this->app->booted(function () {
            SeoHelper::registerModule([
                Product::class,
                Brand::class,
                ProductCategory::class,
                ProductTag::class,
            ]);

            if (is_plugin_active('payment')) {
                Payment::resolveRelationUsing('order', function ($model) {
                    return $model->belongsTo(Order::class, 'order_id')->withDefault();
                });
            }

            if (
                defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') &&
                Route::has('customer.login') &&
                Route::has('public.index')
            ) {
                SocialService::registerModule([
                    'guard' => 'customer',
                    'model' => Customer::class,
                    'login_url' => route('customer.login'),
                    'redirect_url' => route('public.index'),
                ]);
            }

            FormFrontManager::register(LoginForm::class, LoginRequest::class);
            FormFrontManager::register(RegisterForm::class, RegisterRequest::class);
            FormFrontManager::register(ForgotPasswordForm::class, ForgotPasswordRequest::class);
            FormFrontManager::register(ResetPasswordForm::class, ResetPasswordRequest::class);
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
