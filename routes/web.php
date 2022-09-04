<?php

use App\Service\EffectService;
use App\Service\IngredientService;
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

/*
|--------------------------------------------------------------------------
| Effects
|--------------------------------------------------------------------------
|
*/

Route::get('/effects/{id}', function (string $id) {
    $effect = EffectService::findByIdOrFail($id);

    return view('effects/effect', $effect->toArray());
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

    return view('ingredients/ingredient', $ingredient->toArray());
});

Route::get('/ingredients', function () {
    $ingredients = IngredientService::list();

    return view('ingredients/ingredients', ['ingredients' => $ingredients]);
});

