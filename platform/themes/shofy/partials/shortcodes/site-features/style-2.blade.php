<section class="tp-feature-area tp-feature-border-2 pt-30 pb-30">
    <div class="container">
        <div class="tp-feature-inner-2">
            <div class="row align-items-center">
                @foreach($tabs as $tab)
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="tp-feature-item-2 d-flex align-items-start">
                            @if (($icon = $tab['icon']) && BaseHelper::hasIcon($icon))
                                <div class="tp-feature-icon-2 mr-10">
                                    <span @if($shortcode->icon_color) style="color: {{ $shortcode->icon_color }}" @endif>
                                       {!! BaseHelper::renderIcon($icon) !!}
                                    </span>
                                </div>
                            @endif
                            <div class="tp-feature-content-2">
                                <h3 class="tp-feature-title-2">{!! BaseHelper::clean($tab['title']) !!}</h3>
                                <p>{!! BaseHelper::clean($tab['description']) !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
