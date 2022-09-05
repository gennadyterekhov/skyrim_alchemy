<x-layout>

    <div class="row">
        @foreach ($ingredients as $ingredient)
            <div class="col-md-6 col-sm-6 col-12 my-3">
                <x-ingredient.card-small :ingredient="$ingredient"></x-ingredient.card-small>
            </div>
        @endforeach

    </div>
</x-layout>

