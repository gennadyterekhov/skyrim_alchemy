<x-layout>

    <div class="container">
        <h1>Results for "{{ request()->query('search') }}":</h1>

        <div class="row">

            <div class="col">
                <h5>Effects:</h5>
                @if (count($effects) === 0)
                    <x-card.nothing-found></x-card.nothing-found>
                @endif
                @foreach ($effects as $effect)

                    <x-effect.card-micro :effect="$effect"></x-effect.card-micro>

                    <br />


                @endforeach

            </div>


            <div class="col">
                <h5>Ingredients:</h5>
                @if (count($ingredients) === 0)
                    <x-card.nothing-found></x-card.nothing-found>
                @endif
                @foreach ($ingredients as $ingredient)

                    <x-ingredient.card-micro :ingredient="$ingredient"></x-ingredient.card-micro>

                    <br />

                @endforeach
            </div>

        </div>

    </div>
</x-layout>

