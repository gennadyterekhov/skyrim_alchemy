<x-layout>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        @foreach ($effects as $effect)
            <x-effect.card-small :effect="$effect"></x-effect.card-small>
            <br />
        @endforeach

    </div>
</x-layout>


