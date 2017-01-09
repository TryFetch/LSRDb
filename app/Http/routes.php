<?php

// Route::resource('manage', 'ManageController');
Route::resource('search', 'SearchController', ['only' => 'index']);

Route::get('/', function () {
    return view('welcome');
});
