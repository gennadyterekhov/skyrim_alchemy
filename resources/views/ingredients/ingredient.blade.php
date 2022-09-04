<x-layout>

<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <h1>Ingredient:</h1>


        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <h2>id: {{ $id }}</h2>
            <h2>name: {{ $name }}</h2>
            <h2>weight: {{ $weight }}</h2>
            <h2>value: {{ $value }}</h2>

            <h2>Effects:</h2>
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 1
                <x-effect.card-small :effect="$effect_1" onlyName="true"></x-effect.card-small>

            </div>
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 2
                <x-effect.card-small :effect="$effect_2" onlyName="true"></x-effect.card-small>


            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 3
                <x-effect.card-small :effect="$effect_3" onlyName="true"></x-effect.card-small>

            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 4
                <x-effect.card-small :effect="$effect_4" onlyName="true"></x-effect.card-small>

            </div>

        </div>

    </div>
</x-layout>

