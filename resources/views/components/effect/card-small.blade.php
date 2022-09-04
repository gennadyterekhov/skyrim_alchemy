<div class="card">
    <div class="card-body">
        <h5 class="card-title"><a href="/effects/{{ $effect['id'] }}">{{ $effect['name'] }}</a></h5>

        <h6 class="card-subtitle mb-2 text-muted">{{ $effect['id'] }}</h6>

        <div class="card-text">
            <span>description: {{ $effect['text'] }}</span><br>
            <span>magnitude: {{ $effect['magnitude'] }}</span><br>
            <span>value: {{ $effect['value'] }}</span><br>
        </div>

    </div>

</div>
