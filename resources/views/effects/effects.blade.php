<x-layout>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        @foreach ($effects as $effect)
            <div><b>Effect <a href="effects/{{ $effect['id'] }}">{{ $effect['id'] }}</a>:</b></div>
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <span>name: {{ $effect['name'] }}</span><br>
                <span>description: {{ $effect['text'] }}</span><br>
                <span>magnitude: {{ $effect['magnitude'] }}</span><br>
                <span>value: {{ $effect['value'] }}</span><br>
            </div>
            <br />
        @endforeach

    </div>
</x-layout>


