<?php

use Botble\Table\Http\Controllers\TableBulkActionController;
use Botble\Table\Http\Controllers\TableBulkChangeController;
use Botble\Table\Http\Controllers\TableFilterController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tables', 'permission' => false, 'as' => 'table.'], function () {
    Route::group(['prefix' => 'bulk-changes', 'as' => 'bulk-change.'], function () {
        Route::get('data', [TableBulkChangeController::class, 'index'])->name('data');
        Route::post('save', [TableBulkChangeController::class, 'update'])->name('save');
    });

    Route::group(['prefix' => 'bulk-actions', 'as' => 'bulk-action.'], function () {
        Route::post('/', [TableBulkActionController::class, '__invoke'])->name('dispatch');
    });

    Route::group(['prefix' => 'filters', 'as' => 'filter.'], function () {
        Route::get('/', [TableFilterController::class, '__invoke'])->name('input');
    });
});
