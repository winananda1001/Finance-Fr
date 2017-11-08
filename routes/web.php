<?php

Route::get('/login', 'LoginController@index')->name('login.index');
Route::post('/login', 'LoginController@store')->name('login.store');

Route::get('/register', 'RegisterController@index');
Route::post('/register', 'RegisterController@store');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard/{year?}/{month?}', 'DashboardController@index')->name('dashboard.index');

    Route::get('/search', 'SearchController@index');

    Route::resource('/earnings', 'EarningsController', ['only' => ['create', 'store']]);

    Route::resource('/spendings', 'SpendingsController', ['only' => ['create', 'store']]);

    Route::resource('/budgets', 'BudgetsController', ['only' => ['create', 'store']]);

    Route::resource('/tags', 'TagsController', ['only' => ['index', 'create', 'store']]);

    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    Route::post('/settings', 'SettingsController@store')->name('settings.store');
});

Route::get('/logout', 'LogoutController@index')->name('logout.index');
