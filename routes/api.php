<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\InventoryController as V1InventoryController;
use App\Http\Controllers\Api\V1\InventoryItemController as V1InventoryItemController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('inventories', V1InventoryController::class);
    Route::post('inventories/{inventory}/transfer', [V1InventoryController::class, 'transferOwnership']);
    Route::get('reports/hs-codes', [V1InventoryController::class, 'aggregateHSCodes']);
    Route::apiResource('inventory_items', V1InventoryItemController::class);
});
