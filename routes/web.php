<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    dd('Swagger docs here: 127.0.0.1:8000/api/docs');
});
