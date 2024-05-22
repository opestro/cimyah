@php
    $selectClass ??= '';
@endphp

{!!
    $form
        ->when(! empty($selectClass), function ($form) use ($selectClass) {
            return $form->setFormSelectInputClass($selectClass);
        })
        ->renderForm()
!!}
