<pre>

output format is JSON


/*
|--------------------------------------------------------------------------
| Effects
|--------------------------------------------------------------------------
|
*/


GET '/api/effects/{id}' get effect by form id

GET '/api/effects' get all effects without pagination - array of length 55 by default


/*
|--------------------------------------------------------------------------
| Ingredients
|--------------------------------------------------------------------------
|
*/

GET '/api/ingredients/{id}' get ingredient by form id

GET '/api/ingredients/by-effect/{id}' get all ingredients that have given effect by effect form id

GET '/api/ingredients' get all ingredients without pagination - array of length 91 by default


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
|
*/

GET '/api/search' search by effect and ingredient. search fields are id, name, text (effect); id, name (ingredient)

    example:

    request:
    GET localhost/api/search?search=cure

    response:

{
  "effects": [
    {
      "id": "000ae723",
      "name": "Cure Disease",
      "text": "Cures all diseases",
      "magnitude": 5,
      "value": 21
    }
  ],
  "ingredients": []
}

</pre>
