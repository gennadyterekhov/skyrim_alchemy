<x-layout>

    <div class="row">

        @foreach ($effects as $effect)
            <div class="col-md-4 col-sm-6 col-12 my-3">
                <x-effect.card-small :effect="$effect"></x-effect.card-small>
            </div>
            <br />
        @endforeach
    </div>

</x-layout>


