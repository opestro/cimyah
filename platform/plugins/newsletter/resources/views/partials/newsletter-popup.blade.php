<div
    class="modal fade newsletter-popup"
    id="newsletter-popup"
    tabindex="-1"
    aria-labelledby="newsletterPopupModalLabel"
    aria-hidden="true"
    data-delay="{{ theme_option('newsletter_popup_delay', 5) }}"
>
    @php
        $title = theme_option('newsletter_popup_title');
        $image = ($image = theme_option('newsletter_popup_image')) ? RvMedia::getImageUrl($image) : null;
    @endphp

    <div @class(['modal-dialog', 'modal-lg' => $image])>
        <div @class(['modal-content border-0', 'd-flex flex-md-col flex-lg-row' => $image])>
            @if ($image)
                <div class="d-none d-md-block col-6 newsletter-popup-bg" @style(["background: url('$image') no-repeat; background-position: center; background-size: cover"])></div>
            @endif

            <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="newsletter-popup-content">
                <div class="modal-header flex-column align-items-start border-0 p-0">
                    @if($subtitle = theme_option('newsletter_popup_subtitle'))
                        <span class="modal-subtitle">{!! BaseHelper::clean($subtitle) !!}</span>
                    @endif
                    @if($title)
                        <h5 class="modal-title fs-2" id="newsletterPopupModalLabel">{!! BaseHelper::clean($title) !!}</h5>
                    @endif
                    @if($description = theme_option('newsletter_popup_description'))
                        <p class="modal-text text-muted">{!! BaseHelper::clean($description) !!}</p>
                    @endif
                </div>
                <div class="modal-body p-0">
                    {!! $newsletterForm->renderForm() !!}
                </div>
            </div>
        </div>
    </div>
</div>
