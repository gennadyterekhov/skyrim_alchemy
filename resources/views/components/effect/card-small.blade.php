@if($onlyName)
    <div><a href="/effects/{{ $effect['id'] }}">{{ $effect['name'] }}</a></div>
@else
<div><b>Effect <a href="/effects/{{ $effect['id'] }}">{{ $effect['id'] }}</a>:</b></div>
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <span>name: {{ $effect['name'] }}</span><br>
    <span>description: {{ $effect['text'] }}</span><br>
    <span>magnitude: {{ $effect['magnitude'] }}</span><br>
    <span>value: {{ $effect['value'] }}</span><br>
</div>
@endif
