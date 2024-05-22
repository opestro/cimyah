<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Testimonial\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'testimonials', 'as' => 'testimonial.'], function () {
            Route::resource('', 'TestimonialController')->parameters(['' => 'testimonial']);
        });
    });
});
