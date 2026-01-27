<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EmailCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('ping', fn () => response()->json(['ok' => true]));

Route::get('/email-campaigns', [EmailCampaignController::class, 'index']);
Route::post('/email-campaigns', [EmailCampaignController::class, 'store']);
Route::get('/email-campaigns/{email_campaign}/graph', [EmailCampaignController::class, 'getGraph']);
Route::put('/email-campaigns/{email_campaign}/graph', [EmailCampaignController::class, 'updateGraph']);

Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::post('/contacts/tags', [ContactController::class, 'storeTags']);

Route::get('/groups', [GroupController::class, 'index']);
Route::post('/groups', [GroupController::class, 'store']);
