<div class="card">

    <div class="card-body">

        <h1 class="card-title">Effect '{{ $effect['name'] }}'</h1>

        <h5 class="card-subtitle mb-2 text-muted">ID: {{ $effect['id'] }}</h5>

        <hr>
        <div class="card-text">
            <div class="row">

                <h3 class="col-8">description: {{ $effect['text'] }}</h3>
                <h3 class="col">magnitude: {{ $effect['magnitude'] }}</h3>
                <h3 class="col">value: {{ $effect['value'] }}</h3>
            </div>
        </div>
    </div>

</div>

