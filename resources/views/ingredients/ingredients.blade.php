<x-layout>

<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        @foreach ($ingredients as $ingredient)
            <div><b>Ingredient <a href="ingredients/{{ $ingredient['id'] }}">{{ $ingredient['id'] }}</a>:</b></div>
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <span>name: {{ $ingredient['name'] }}</span><br>
                <span>weight: {{ $ingredient['weight'] }}</span><br>
                <span>value: {{ $ingredient['value'] }}</span><br>
                <span>Effects:</span><br>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 1
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                        <span>
                            id: <a href="effects/{{ $ingredient['effect_1']['id'] }}">
                                {{ $ingredient['effect_1']['id'] }}
                            </a>
                        </span><br>
                        <span>name: {{ $ingredient['effect_1']['name'] }}</span><br>
                    </div>
                </div>
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 2
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                        <span>
                            id: <a href="effects/{{ $ingredient['effect_2']['id'] }}">
                                {{ $ingredient['effect_2']['id'] }}
                            </a>
                        </span><br>
                        <span>name: {{ $ingredient['effect_2']['name'] }}</span><br>
                    </div>
                </div>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 3
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                        <span>
                            id: <a href="effects/{{ $ingredient['effect_3']['id'] }}">
                                {{ $ingredient['effect_3']['id'] }}
                            </a>
                        </span><br>
                        <span>name: {{ $ingredient['effect_3']['name'] }}</span><br>
                    </div>
                </div>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    Effect 4
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                        <span>
                            id: <a href="effects/{{ $ingredient['effect_4']['id'] }}">
                                {{ $ingredient['effect_4']['id'] }}
                            </a>
                        </span><br>
                        <span>name: {{ $ingredient['effect_4']['name'] }}</span><br>
                    </div>
                </div>

            </div>
            <br />
        @endforeach

    </div>
</x-layout>

