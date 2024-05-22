<div class="col-md-4">
    <div class="list-group config-item">
        <a
            class="list-group-item"
            href="{{ route('translations.index') }}"
        >
            <i
                class="fa fa-language"
                style="font-size: 20px;"
            ></i>
            <h4 class="list-group-item-heading">{{ trans('plugins/translation::translation.translations') }}</h4>
            <p class="list-group-item-text">{{ trans('plugins/translation::translation.translations_description') }}</p>
        </a>
    </div>
</div>
