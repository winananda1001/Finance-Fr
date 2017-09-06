<?php

Route::get('/login', 'LoginController@index')->name('login.index');
Route::post('/login', 'LoginController@store')->name('login.store');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

    Route::resource('/earnings', 'EarningsController', ['only' => ['create', 'store']]);

    Route::resource('/spendings', 'SpendingsController', ['only' => ['create', 'store']]);

    Route::resource('/budgets', 'BudgetsController', ['only' => ['index']]);

    Route::resource('/tags', 'TagsController', ['only' => ['index']]);

    Route::get('/reports', 'ReportsController@index')->name('reports.index');
    Route::get('/reports/{year}/{month}', 'ReportsController@show')->name('reports.show');

    Route::get('/settings', 'SettingsController@index')->name('settings.index');
    Route::post('/settings', 'SettingsController@store')->name('settings.store');
});

Route::get('/logout', 'LogoutController@index')->name('logout.index');
