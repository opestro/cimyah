<section class="tp-feature-area tp-feature-border-radius pt-30 pb-30">
    <div class="container">
        <div class="row gx-1 gy-1 gy-xl-0">
            @foreach($tabs as $tab)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="tp-feature-item d-flex align-items-start">
                        @if (($icon = $tab['icon']) && BaseHelper::hasIcon($icon))
                            <div class="tp-feature-icon mr-15">
                               <span @if($shortcode->icon_color) style="color: {{ $shortcode->icon_color }}" @endif>
                                   {!! BaseHelper::renderIcon($icon) !!}
                               </span>
                            </div>
                        @endif
                        <div class="tp-feature-content">
                            <h3 class="tp-feature-title">{!! BaseHelper::clean($tab['title']) !!}</h3>
                            @if($tab['description'])
                                <p>{!! BaseHelper::clean($tab['description']) !!}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
