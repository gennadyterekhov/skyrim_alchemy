<x-layout>

<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        @foreach ($ingredients as $ingredient)
            <div><b>Ingredient <a href="/ingredients/{{ $ingredient['id'] }}">{{ $ingredient['id'] }}</a>:</b></div>
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <span>name: {{ $ingredient['name'] }}</span><br>
                <span>weight: {{ $ingredient['weight'] }}</span><br>
                <span>value: {{ $ingredient['value'] }}</span><br>
                <span>Effects:</span><br>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 1
                    <x-effect.card-small :effect="$ingredient['effect_1']" onlyName="true"></x-effect.card-small>

                </div>
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 2
                    <x-effect.card-small :effect="$ingredient['effect_2']" onlyName="true"></x-effect.card-small>

                </div>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 3
                    <x-effect.card-small :effect="$ingredient['effect_3']" onlyName="true"></x-effect.card-small>

                </div>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 4
                    <x-effect.card-small :effect="$ingredient['effect_4']" onlyName="true"></x-effect.card-small>

                </div>

            </div>
            <br />
        @endforeach

    </div>
</x-layout>

