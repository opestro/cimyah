<div class="tp-blog-list-item-wrapper">
    @foreach ($posts as $post)
        @include(Theme::getThemeNamespace("views.partials.post-list-item"), compact('post'))
    @endforeach
</div>
