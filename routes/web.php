<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

Route::get('/', function () {
    return view('landing');
});


Route::get('/landing', function () {
    return view('landing');
});

Route::post('/submit', [LeadController::class, 'submit']);
Route::get('/download-pdf', [LeadController::class, 'downloadPdf']);
