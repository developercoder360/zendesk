<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Widget\ConfigController;
use App\Http\Controllers\Api\Widget\TicketController;

Route::get("/user", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");
Route::middleware([\App\Http\Middleware\InitializeTenancyByHeader::class])
    ->prefix("v1/widget")
    ->name("api.widget.")
    ->group(function () {
        Route::get("/config", [ConfigController::class, "show"])->name("config");
        Route::post("/tickets", [TicketController::class, "store"])->name("tickets.store");
    });
