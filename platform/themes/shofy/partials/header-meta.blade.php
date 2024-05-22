<style>
    :root {
        --primary-color: {{ $primaryColor = theme_option('primary_color', '#0989ff') }};
        --primary-color-rgb: {{ implode(',', BaseHelper::hexToRgb($primaryColor)) }};
    }
</style>
