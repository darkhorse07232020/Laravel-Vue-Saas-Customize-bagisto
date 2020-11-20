<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {

    Route::get('/saasadmin', 'EDES\SaasAdmin\Http\Controllers\Shop\SaasAdminController@index')->defaults('_config', [
        'view' => 'saasadmin::shop.index',
    ])->name('saasadmin.shop.index');

});