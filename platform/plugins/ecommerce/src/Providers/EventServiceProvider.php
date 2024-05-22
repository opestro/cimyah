<?php

namespace Botble\Ecommerce\Providers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Botble\Ecommerce\Events\OrderCompletedEvent;
use Botble\Ecommerce\Events\OrderCreated;
use Botble\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Events\OrderReturnedEvent;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Events\ProductViewed;
use Botble\Ecommerce\Events\ShippingStatusChanged;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Listeners\AddLanguageForVariantsListener;
use Botble\Ecommerce\Listeners\ClearShippingRuleCache;
use Botble\Ecommerce\Listeners\GenerateInvoiceListener;
use Botble\Ecommerce\Listeners\GenerateLicenseCodeAfterOrderCompleted;
use Botble\Ecommerce\Listeners\OrderCancelledNotification;
use Botble\Ecommerce\Listeners\OrderCreatedNotification;
use Botble\Ecommerce\Listeners\OrderPaymentConfirmedNotification;
use Botble\Ecommerce\Listeners\OrderReturnedNotification;
use Botble\Ecommerce\Listeners\RegisterCodPaymentMethod;
use Botble\Ecommerce\Listeners\RegisterEcommerceWidget;
use Botble\Ecommerce\Listeners\RenderingSiteMapListener;
use Botble\Ecommerce\Listeners\SaveProductFaqListener;
use Botble\Ecommerce\Listeners\SendMailsAfterCustomerRegistered;
use Botble\Ecommerce\Listeners\SendProductReviewsMailAfterOrderCompleted;
use Botble\Ecommerce\Listeners\SendShippingStatusChangedNotification;
use Botble\Ecommerce\Listeners\SendWebhookWhenOrderPlaced;
use Botble\Ecommerce\Listeners\UpdateInvoiceAndShippingWhenOrderCancelled;
use Botble\Ecommerce\Listeners\UpdateInvoiceWhenOrderCompleted;
use Botble\Ecommerce\Listeners\UpdateProductStockStatus;
use Botble\Ecommerce\Listeners\UpdateProductView;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleApplyProductCrossSaleService;
use Botble\Ecommerce\Services\HandleRemoveCouponService;
use Botble\Payment\Events\RenderingPaymentMethods;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Session\SessionManager;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        CreatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
            ClearShippingRuleCache::class,
            SaveProductFaqListener::class,
        ],
        UpdatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
            ClearShippingRuleCache::class,
            SaveProductFaqListener::class,
        ],
        DeletedContentEvent::class => [
            ClearShippingRuleCache::class,
        ],
        Registered::class => [
            SendMailsAfterCustomerRegistered::class,
        ],
        OrderPlacedEvent::class => [
            SendWebhookWhenOrderPlaced::class,
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        OrderCreated::class => [
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        ProductQuantityUpdatedEvent::class => [
            UpdateProductStockStatus::class,
        ],
        OrderCompletedEvent::class => [
            SendProductReviewsMailAfterOrderCompleted::class,
            GenerateLicenseCodeAfterOrderCompleted::class,
            UpdateInvoiceWhenOrderCompleted::class,
        ],
        ProductViewed::class => [
            UpdateProductView::class,
        ],
        ShippingStatusChanged::class => [
            SendShippingStatusChangedNotification::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterEcommerceWidget::class,
        ],
        OrderPaymentConfirmedEvent::class => [
            OrderPaymentConfirmedNotification::class,
        ],
        OrderCancelledEvent::class => [
            OrderCancelledNotification::class,
            UpdateInvoiceAndShippingWhenOrderCancelled::class,
        ],
        OrderReturnedEvent::class => [
            OrderReturnedNotification::class,
        ],
        RenderingPaymentMethods::class => [
            RegisterCodPaymentMethod::class,
        ],
    ];

    public function boot(): void
    {
        $events = $this->app['events'];

        // Something wrong here, need to remove cart.removed event for now.
        $events->listen(
            ['cart.added', 'cart.updated'],
            fn () => $this->app->make(HandleApplyProductCrossSaleService::class)->handle()
        );

        $events->listen(
            ['cart.removed', 'cart.stored', 'cart.restored', 'cart.updated'],
            function ($cart) {
                $coupon = session('applied_coupon_code');
                if ($coupon) {
                    $this->app->make(HandleRemoveCouponService::class)->execute();
                    if (Cart::count() || ($cart instanceof \Botble\Ecommerce\Cart\Cart && $cart->count())) {
                        $this->app->make(HandleApplyCouponService::class)->execute($coupon);
                    }
                }
            }
        );

        $this->app['events']->listen(Logout::class, function () {
            if (get_ecommerce_setting('cart_destroy_on_logout', false)) {
                $this->app->make(SessionManager::class)->forget('cart');
            }
        });
    }
}
