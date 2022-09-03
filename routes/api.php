<?php

use App\Helper\AssertHelper;
use App\Models\Effect;
use App\Service\EffectService;
use App\Service\IngredientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Effects
|--------------------------------------------------------------------------
|
*/


Route::get('/effects/{id}', function (string $id) {
    $effect = EffectService::findByIdOrFail($id);

    return response()->json($effect);
});

Route::get('/effects', function () {
    $effects = EffectService::list();

    return response()->json($effects);
});


/*
|--------------------------------------------------------------------------
| Ingredients
|--------------------------------------------------------------------------
|
*/

Route::get('/ingredients/{id}', function (string $id) {
    $ingredient = IngredientService::findByIdOrFail($id);

    return response()->json($ingredient);
});

Route::get('/ingredients', function () {
    $ingredients = IngredientService::list();

    return response()->json($ingredients);
});


/*
|--------------------------------------------------------------------------
| temp TODO remove later
|--------------------------------------------------------------------------
|
*/

Route::get('/test', function () {
    return 'test';
});
