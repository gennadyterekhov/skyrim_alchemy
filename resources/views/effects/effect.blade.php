<x-layout>
    <div class="">
        <x-effect.card-large :effect="$effect"></x-effect.card-large>
    </div>

    <hr>
    <div class="container">
        <h3>Ingredients with this effect:</h3>
        <div class="row">
            @foreach ($ingredients as $ingredient)
                <div class="col-md-6 col-sm-6 col-12 my-3">
                    <x-ingredient.card-small :ingredient="$ingredient"></x-ingredient.card-small>
                </div>
            @endforeach
        </div>

    </div>
</x-layout>
