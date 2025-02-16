<?php

use DionBoerrigter\Hubspot\Http\Controllers\HubspotFormController;
use DionBoerrigter\Hubspot\Http\Controllers\HubspotFieldController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/hubspot')
    ->middleware('statamic.cp')
    ->group(function () {
        Route::get('/forms', [HubspotFormController::class, 'index'])->name('forms.index');
        Route::get('/forms/create', [HubspotFormController::class, 'create'])->name('forms.create');
        Route::post('/forms', [HubspotFormController::class, 'store'])->name('forms.store');
        Route::delete('/forms/{id}', [HubspotFormController::class, 'destroy'])->name('forms.destroy');

        Route::get('/fields', [HubspotFieldController::class, 'index'])->name('fields.index');
        Route::view('/fields/create', 'hubspot::fields.create')->name('fields.create');
        Route::post('/fields', [HubspotFieldController::class, 'store'])->name('fields.store');
        Route::delete('/fields/{id}', [HubspotFieldController::class, 'destroy'])->name('fields.destroy');
    });



