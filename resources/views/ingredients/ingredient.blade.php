<x-layout>
    <div class="">
        <x-ingredient.card-large :ingredient="$ingredient"></x-ingredient.card-large>
    </div>


    <hr>
    <div class="container">
        <h3>Ingredients with common effects:</h3>
        <br>

        <x-ingredient.common-ingredients
            :ingredient="$ingredient"
            :commonIngredients1="$commonIngredients1"
            :commonIngredients2="$commonIngredients2"
            :commonIngredients3="$commonIngredients3"
            :commonIngredients4="$commonIngredients4"
        >
        </x-ingredient.common-ingredients>



    </div>
</x-layout>

