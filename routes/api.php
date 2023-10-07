<?php

use App\Models\Fabricator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Providers\FabricatorProvider;
use App\Http\Providers\PurchaseProvider;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {

    Route::prefix('internal')->group(function () {    

        Route::resource('fabricators', FabricatorProvider::class);
        
        Route::get('haspurchase',[ PurchaseProvider::class, 'checkHasPurchase']);
        
        Route::resource('purchases', PurchaseProvider::class);

    });

});
