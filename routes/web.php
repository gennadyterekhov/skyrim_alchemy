<?php

use App\Service\EffectService;
use App\Service\IngredientService;
use App\Service\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/export-csv', function () {
    return view('export-csv');
});

Route::get('/api-doc', function (Request $request) {
    return view('api-doc');
});

/*
|--------------------------------------------------------------------------
| Effects
|--------------------------------------------------------------------------
|
*/

Route::get('/effects/{id}', function (string $id) {
    $effect = EffectService::findByIdOrFail($id);
    $ingredients = IngredientService::listByEffect($id);

    return view('effects/effect', ['effect' => $effect->toArray(), 'ingredients' => $ingredients]);
});

Route::get('/effects', function () {
    $effects = EffectService::list();

    return view('effects/effects', ['effects' => $effects]);
});


/*
|--------------------------------------------------------------------------
| Ingredients
|--------------------------------------------------------------------------
|
*/

Route::get('/ingredients/{id}', function (string $id) {
    $ingredient = IngredientService::findByIdOrFail($id);
    $commonIngredients1 = IngredientService::listByEffect($ingredient->effect_1_id);
    $commonIngredients2 = IngredientService::listByEffect($ingredient->effect_2_id);
    $commonIngredients3 = IngredientService::listByEffect($ingredient->effect_3_id);
    $commonIngredients4 = IngredientService::listByEffect($ingredient->effect_4_id);

    return view('ingredients/ingredient', [
        'ingredient' => $ingredient->toArray(),
        'commonIngredients1' => $commonIngredients1,
        'commonIngredients2' => $commonIngredients2,
        'commonIngredients3' => $commonIngredients3,
        'commonIngredients4' => $commonIngredients4,

    ]);
});

Route::get('/ingredients', function () {
    $ingredients = IngredientService::list();

    return view('ingredients/ingredients', ['ingredients' => $ingredients]);
});


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
|
*/

Route::get('/search', function (Request $request) {
    $data = SearchService::search($request);

    return view('search/search', ['effects' => $data['effects'], 'ingredients' => $data['ingredients']]);
});
