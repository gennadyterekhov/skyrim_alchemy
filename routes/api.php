<?php

use App\Service\EffectService;
use App\Service\ExportService;
use App\Service\IngredientService;
use App\Service\SearchService;
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

Route::get('/ingredients/by-effect/{id}', function (string $id) {
    $ingredients = IngredientService::listByEffect($id);

    return response()->json($ingredients);
});

Route::get('/ingredients', function () {
    $ingredients = IngredientService::list();

    return response()->json($ingredients);
});


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
|
*/

Route::get('/search', function (Request $request) {
    $data = SearchService::search($request);

    return response()->json($data);
});


/*
|--------------------------------------------------------------------------
| Export
|--------------------------------------------------------------------------
|
*/

Route::get('/export/json', function (Request $request) {
    $data = ExportService::exportJson($request);

    return response()->json($data);
});
