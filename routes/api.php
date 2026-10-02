<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArchivedProjectController;

Route::post('/store-archive', [ArchivedProjectController::class, 'store']);
