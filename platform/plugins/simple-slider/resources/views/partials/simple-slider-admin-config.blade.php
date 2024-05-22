{{-- Deprecated --}}
<div class="mb-3">
    <label class="form-label">{{ trans('plugins/simple-slider::simple-slider.select_slider') }}</label>
    {!! Form::customSelect('key', $sliders, Arr::get($attributes, 'key')) !!}
</div>
