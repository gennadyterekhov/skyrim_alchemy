<div class="card">
    <div class="card-body">

        <h5 class="card-title"><a href="/ingredients/{{ $ingredient['id'] }}">{{ $ingredient['name'] }}</a></h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ $ingredient['id'] }}</h6>

        <div class="card-text">
            <span>weight: {{ $ingredient['weight'] }}</span><br>
            <span>value: {{ $ingredient['value'] }}</span><br>

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
