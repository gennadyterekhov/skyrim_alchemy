<div>
    @foreach ($ingredients as $ingredient)
        @if ($ingredient !== $originalIngredient)
        <div class="col-12 my-3">
            <x-ingredient.card-micro :ingredient="$ingredient"></x-ingredient.card-micro>
        </div>
        @endif
    @endforeach
</div>
