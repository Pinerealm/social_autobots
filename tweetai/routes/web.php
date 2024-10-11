<?php

use App\Jobs\CreateAutobotsJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// For testing the job
Route::get('/test-job', function () {
    dispatch(new CreateAutobotsJob());
    return 'Job dispatched';
});