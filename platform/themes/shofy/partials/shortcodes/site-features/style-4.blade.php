<section class="tp-feature-area tp-feature-border-5 pb-55">
    <div class="container">
        <div class="tp-feature-inner-5">
            <div class="row">
                @foreach($tabs as $tab)
                    <div class="col-xl-3 col-lg-3 col-sm-6">
                        <div class="tp-feature-item-5 d-flex align-items-center">
                            <div class="tp-feature-icon-5">
                                @if (($icon = $tab['icon']) && BaseHelper::hasIcon($icon))
                                    <span @if($shortcode->icon_color) style="color: {{ $shortcode->icon_color }}" @endif>
                                        {!! BaseHelper::renderIcon($icon) !!}
                                    </span>
                                @endif
                            </div>
                            <div class="tp-feature-content-5">
                                <h3 class="tp-feature-title-5">{!! BaseHelper::clean($tab['title']) !!}</h3>
                                @if($tab['description'])
                                    <p class="mb-0">{!! BaseHelper::clean($tab['description']) !!}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
