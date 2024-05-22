<style>
    :root {
        --primary-color: {{ $primaryColor = theme_option('primary_color', '#821F40') }};
        --primary-color-rgb: {{ implode(',', BaseHelper::hexToRgb($primaryColor)) }};
    }
</style>
