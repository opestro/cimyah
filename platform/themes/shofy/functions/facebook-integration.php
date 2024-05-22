<?php

use Botble\Theme\Facades\Theme;

app()->booted(fn () => Theme::registerFacebookIntegration());
