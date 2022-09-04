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
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <span>id: {{ $effect_1['id'] }}</span><br>
                    <span>name: {{ $effect_1['name'] }}</span><br>
                </div>
            </div>
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 2
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <span>id: {{ $effect_2['id'] }}</span><br>
                    <span>name: {{ $effect_2['name'] }}</span><br>
                </div>
            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 3
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <span>id: {{ $effect_3['id'] }}</span><br>
                    <span>name: {{ $effect_3['name'] }}</span><br>
                </div>
            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                Effect 4
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <span>id: {{ $effect_4['id'] }}</span><br>
                    <span>name: {{ $effect_4['name'] }}</span><br>
                </div>
            </div>

        </div>

    </div>
</x-layout>

