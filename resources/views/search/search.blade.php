<x-layout>

    <div class="container">
        <h1>Results for "{{ request()->query('search') }}":</h1>


        <div class="row">

            <div class="col">
                <h5>Effects:</h5>
                @foreach ($effects as $effect)


                    <div><b>Effect <a href="/effects/{{ $effect['id'] }}">{{ $effect['id'] }}</a>:</b></div>
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                        <span>name: {{ $effect['name'] }}</span><br>
                        <span>description: {{ $effect['text'] }}</span><br>
                        <span>magnitude: {{ $effect['magnitude'] }}</span><br>
                        <span>value: {{ $effect['value'] }}</span><br>
                    </div>
                    <br />


                @endforeach

            </div>


            <div class="col">
                <h5>Ingredients:</h5>
                @foreach ($ingredients as $ingredient)

                    <div><b>Ingredient <a href="/ingredients/{{ $ingredient['id'] }}">{{ $ingredient['id'] }}</a>:</b></div>
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                        <span>name: {{ $ingredient['name'] }}</span><br>
                        <span>weight: {{ $ingredient['weight'] }}</span><br>
                        <span>value: {{ $ingredient['value'] }}</span><br>
                    </div>
                    <br />


                @endforeach
            </div>

        </div>

    </div>
</x-layout>

