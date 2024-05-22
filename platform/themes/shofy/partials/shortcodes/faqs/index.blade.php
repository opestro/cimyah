<section class="mb-60">
    @if ($shortcode->title || $shortcode->description)
        <div class="tp-section-title-wrapper mb-40">
            @if ($shortcode->title)
                <h3 class="section-title tp-section-title">
                    {!! BaseHelper::clean($shortcode->title) !!}
                </h3>
            @endif

            @if ($shortcode->description)
                <p class="text-muted fs-6 mt-2">{!! BaseHelper::clean($shortcode->description) !!}</p>
            @endif
        </div>
    @endif

    @if ($shortcode->style === 'list')
        <div class="tp-faq-wrapper">
            <div class="accordion" id="accordion-faqs">
                @foreach($faqs as $faq)
                    <div class="accordion-item">
                        <h5 class="accordion-header" id="heading{{ $faq->getKey() }}">
                            <button @class(['accordion-button text-heading-5', 'collapsed' => ! ($loop->first && $shortcode->expand_first_time)]) type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->getKey() }}" aria-expanded="false" aria-controls="collapse{{ $faq->getKey() }}">
                                {!! BaseHelper::clean($faq->question) !!}
                            </button>
                        </h5>
                        <div @class(['accordion-collapse collapse', 'show' => $loop->first && $shortcode->expand_first_time]) id="collapse{{ $faq->getKey() }}" aria-labelledby="heading{{ $faq->getKey() }}" data-bs-parent="#accordion-faqs">
                            <div class="accordion-body">
                                {!! BaseHelper::clean($faq->answer) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="tp-faq-wrapper row gy-4">
            @foreach ($categories as $category)
                <div class="col-md-6">
                    <div class="tp-faq-item">
                        <h4 class="tp-faq-title">{{ $category->name }}</h4>

                        <div class="accordion" id="{{ $category->slug }}-faqs">
                            @foreach($category->faqs as $faq)
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="heading{{ $faq->getKey() }}">
                                        <button @class(['accordion-button text-heading-5', 'collapsed' => ! ($loop->first && $shortcode->expand_first_time)]) type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->getKey() }}" aria-expanded="false" aria-controls="collapse{{ $faq->getKey() }}">
                                            {!! BaseHelper::clean($faq->question) !!}
                                        </button>
                                    </h5>
                                    <div @class(['accordion-collapse collapse', 'show' => $loop->first && $shortcode->expand_first_time]) id="collapse{{ $faq->getKey() }}" aria-labelledby="heading{{ $faq->getKey() }}" data-bs-parent="#accordion-faqs">
                                        <div class="accordion-body">
                                            {!! BaseHelper::clean($faq->answer) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
