<section class="tp-feature-area tp-feature-border-3 tp-feature-style-2 pb-40 pt-45">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="tp-feature-inner-2 d-flex flex-wrap align-items-center justify-content-between">
                    @foreach($tabs as $tab)
                        <div class="tp-feature-item-2 d-flex align-items-start mb-40">
                            <div class="tp-feature-icon-2 mr-10">
                            <span>
                                @if (($icon = $tab['icon']) && BaseHelper::hasIcon($tab['icon']))
                                    <div class="tp-feature-icon mr-15">
                                       <span @if($shortcode->icon_color) style="color: {{ $shortcode->icon_color }}" @endif>
                                           {!! BaseHelper::renderIcon($icon) !!}
                                       </span>
                                    </div>
                                @endif
                            </span>
                            </div>
                            <div class="tp-feature-content-2">
                                <h3 class="tp-feature-title-2">{!! BaseHelper::clean($tab['title']) !!}</h3>
                                <p>{!! BaseHelper::clean($tab['description']) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
