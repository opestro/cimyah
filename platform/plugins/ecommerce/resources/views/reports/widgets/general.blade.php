<link type="text/css" rel="stylesheet" href="{{ asset('vendor/core/plugins/ecommerce/css/widget.css') }}">

<ul class="ecommerce-status-list list-unstyled">
    <li class="sales-this-month">
        <x-core::icon name="ti ti-chart-line" />
        <a class="ms-2" href="{{ route('ecommerce.report.index') }}">
            <strong>
                {{ format_price($revenue) }}
            </strong> {{ trans('plugins/ecommerce::reports.revenue_this_month') }}
        </a>
    </li>
    <li class="processing-orders">
        <x-core::icon name="ti ti-truck" />
        <a class="ms-2" href="{{ route('orders.index') }}">
            <strong>{{ $processingOrders }}</strong>
            {{ trans('plugins/ecommerce::reports.order_processing_this_month') }}
        </a>
    </li>
    <li class="completed-orders">
        <x-core::icon name="ti ti-truck" />
        <a class="ms-2" href="{{ route('orders.index') }}">
            <strong>{{ $completedOrders }}</strong> {{ trans('plugins/ecommerce::reports.order_completed_this_month') }}
        </a>
    </li>
    <li class="low-in-stock">
        <x-core::icon name="ti ti-exclamation-circle" />
        <a class="ms-2" href="{{ route('products.index') }}">
            <strong>{{ $lowStockProducts }}</strong>
            {{ trans('plugins/ecommerce::reports.product_will_be_out_of_stock') }}
        </a>
    </li>
    <li class="out-of-stock">
        <x-core::icon name="ti ti-circle-x" />
        <a
            class="ms-2"
            href="{{ route('products.index') }}?filter_table_id=botble-ecommerce-tables-product-table&class=Botble%5CEcommerce%5CTables%5CProductTable&filter_columns%5B%5D=stock_status&filter_operators%5B%5D=%3D&filter_values%5B%5D=out_of_stock">
            <strong>{{ $outOfStockProducts }}</strong> {{ trans('plugins/ecommerce::reports.product_out_of_stock') }}
        </a>
    </li>
</ul>
