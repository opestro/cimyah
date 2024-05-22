@php
    use Botble\Shortcode\Facades\Shortcode;

    Theme::asset()->remove('contact-css');
    Theme::asset()->container('footer')->remove('contact-public-js');

    $contactInfo = Shortcode::fields()->getTabsData(['icon', 'content'], $shortcode);
@endphp

<section class="tp-contact-area pb-100">
    <div class="container">
        <div class="tp-contact-inner">
            <div class="row">
                @if ($shortcode->show_contact_form)
                    <div class="col-xl-9 col-lg-8">
                        <div class="tp-contact-wrapper">
                            @if ($title = $shortcode->title)
                                <h3 class="tp-contact-title">{{ $title }}</h3>
                            @endif

                            <div class="tp-contact-form">
                                {!! $form->renderForm() !!}
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-xl-3 col-lg-4">
                    <div class="tp-contact-info-wrapper">
                        @foreach ($contactInfo as $info)
                            @continue(empty($info['icon']) || empty($info['content']))

                            <div class="tp-contact-info-item">
                                <div class="tp-contact-info-icon">
                                    <span>
                                        {{ RvMedia::image($info['icon'], $info['content']) }}
                                    </span>
                                </div>
                                <div class="tp-contact-info-content">
                                    <p>{!! BaseHelper::clean($info['content']) !!}</p>
                                </div>
                            </div>
                        @endforeach

                        @if ($shortcode->show_social_info)
                            @php
                                $socialInfoLabel = $shortcode->social_info_label;
                            @endphp

                            <div class="tp-contact-info-item">
                                <div class="tp-contact-info-icon">
                                    <span>
                                        {{ RvMedia::image($shortcode->social_info_icon, $socialInfoLabel ?: __('Social Media')) }}
                                    </span>
                                </div>
                                <div class="tp-contact-info-content">
                                    <div class="tp-contact-social-wrapper mt-5">
                                        @if ($socialInfoLabel)
                                            <h4 class="tp-contact-social-title">{{ $socialInfoLabel }}</h4>
                                        @endif

                                        @if ($socialLinks = Theme::getSocialLinks())
                                            <div class="tp-contact-social-icon">
                                                @foreach($socialLinks as $socialLink)
                                                    <a href="{{ $socialLink->getUrl() }}">{{ $socialLink->getIconHtml() }}</a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
