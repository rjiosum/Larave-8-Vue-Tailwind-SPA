<?php

use Illuminate\Support\Facades\Route;

Route::view('/{parameters?}', 'app')->where('parameters', '.*');
