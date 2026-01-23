<?php

use App\Http\Controllers\EmailCampaignController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('ping', fn () => response()->json(['ok' => true]));

Route::post('/email-campaigns', [EmailCampaignController::class, 'store']);
Route::get('/email-campaigns/{email_campaign}/graph', [EmailCampaignController::class, 'getGraph']);
Route::put('/email-campaigns/{email_campaign}/graph', [EmailCampaignController::class, 'updateGraph']);

Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
