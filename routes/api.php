<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/user", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");

Route::middleware([\App\Http\Middleware\InitializeTenancyByHeader::class])
    ->prefix("v1/widget")
    ->name("api.widget.")
    ->group(function () {
        Route::get("/config", [\App\Http\Controllers\Api\Widget\ConfigController::class, "show"])->name("config");
        Route::post("/tickets", [\App\Http\Controllers\Api\Widget\TicketController::class, "store"])->name("tickets.store");
    });
