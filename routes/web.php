<?php

Route::get('/', 'IndexController@index')->name('index');

Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login', 'LoginController@store');

Route::get('/verify/{token}', 'VerifyController')->name('verify');

Route::get('/reset_password', 'ResetPasswordController@get')->name('reset_password');
Route::post('/reset_password', 'ResetPasswordController@post');

Route::get('/register', 'RegisterController@index')->name('register');
Route::post('/register', 'RegisterController@store');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController')->name('dashboard');

    Route::resource('/earnings', 'EarningController')->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::name('earnings.')->group(function () {
        Route::post('/earnings/{id}/restore', 'EarningController@restore');
    });

    Route::resource('/spendings', 'SpendingController')->only([
        'index',
        'create',
        'store',
        'destroy'
    ]);

    Route::resource('/recurrings', 'RecurringController')->only([
        'index',
        'create',
        'store',
        'show'
    ]);

    Route::resource('/tags', 'TagController')->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

    Route::name('imports.')->group(function () {
        Route::get('/imports', 'ImportController@index')->name('index');
        Route::get('/imports/create', 'ImportController@create')->name('create');
        Route::post('/imports', 'ImportController@store')->name('store');
        Route::get('/imports/{import}/prepare', 'ImportController@getPrepare')->name('prepare');
        Route::post('/imports/{import}/prepare', 'ImportController@postPrepare');
        Route::get('/imports/{import}/complete', 'ImportController@getComplete')->name('complete');
        Route::post('/imports/{import}/complete', 'ImportController@postComplete');
        Route::delete('/imports/{import}', 'ImportController@destroy');
    });

    Route::get('/settings', 'SettingsController@index')->name('settings');
    Route::post('/settings', 'SettingsController@store');

    Route::get('/spaces/{id}', 'SpaceController');
});

Route::get('/logout', 'LogoutController@index')->name('logout');
