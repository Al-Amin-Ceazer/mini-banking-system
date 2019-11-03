<?php

Route::post('auth/login', 'AuthController@postLogin')->name('auth.login');
Route::post('register', 'AuthController@register');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', 'AuthController@postLogout')->name('auth.logout');
    Route::post('deposit', 'TransactionController@deposit');
    Route::post('fund-transfer', 'TransactionController@fundTransfer');
    Route::get('account-balance', 'TransactionController@accountBalance');
});
