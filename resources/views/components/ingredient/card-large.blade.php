<div class="card">


    <div class="card-body">
        <h1 class="card-title">Ingredient '{{ $ingredient['name'] }}'</h1>

        <h5 class="card-subtitle mb-2 text-muted">ID: {{ $ingredient['id'] }}</h5>

        <hr>
        <div class="card-text">

            <div class="row">
                <h2 class="col">weight: {{ $ingredient['weight'] }}</h2>
                <h2 class="col">value: {{ $ingredient['value'] }}</h2>
            </div>

            <x-ingredient.effects
                :effect1="$ingredient['effect_1']"
                :effect2="$ingredient['effect_2']"
                :effect3="$ingredient['effect_3']"
                :effect4="$ingredient['effect_4']"
            >
            </x-ingredient.effects>


        </div>
    </div>

</div>
