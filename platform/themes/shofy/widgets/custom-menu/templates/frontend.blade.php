<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
    <div class="tp-footer-widget footer-col-2">
        <h4 class="tp-footer-widget-title">{{ $config['name'] }}</h4>
        <div class="tp-footer-widget-content">
            {!! Menu::generateMenu(['slug' => $config['menu_id'], 'view' => 'footer.menu']) !!}
        </div>
    </div>
</div>
