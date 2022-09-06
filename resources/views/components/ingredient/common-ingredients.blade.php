<div class="row">
    <div class="col">
        <div class="text-center">{{$ingredient['effect_1']['name']}}</div>
        <div class="row">
            <x-ingredient.list.card-micro-list :originalIngredient="$ingredient" :ingredients="$commonIngredients1"></x-ingredient.list.card-micro-list>
        </div>
    </div>

    <div class="col">
        <div class="text-center">{{$ingredient['effect_2']['name']}}</div>
        <div class="row">
            <x-ingredient.list.card-micro-list :originalIngredient="$ingredient" :ingredients="$commonIngredients2"></x-ingredient.list.card-micro-list>

        </div>
    </div>
    <div class="col">
        <div class="text-center">{{$ingredient['effect_3']['name']}}</div>
        <div class="row">
            <x-ingredient.list.card-micro-list :originalIngredient="$ingredient" :ingredients="$commonIngredients3"></x-ingredient.list.card-micro-list>

        </div>
    </div>
    <div class="col">
        <div class="text-center">{{$ingredient['effect_4']['name']}}</div>
        <div class="row">
            <x-ingredient.list.card-micro-list :originalIngredient="$ingredient" :ingredients="$commonIngredients4"></x-ingredient.list.card-micro-list>
        </div>
    </div>
</div>
