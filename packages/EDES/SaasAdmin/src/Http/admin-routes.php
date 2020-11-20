<?php

Route::group(['middleware' => ['web', 'admin']], function () {

    Route::get('/admin/saasadmin', 'EDES\SaasAdmin\Http\Controllers\Admin\SaasAdminController@index')->defaults('_config', [
        'view' => 'saasadmin::admin.index',
    ])->name('saasadmin.admin.index');

});