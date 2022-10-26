<div class="text-white m-7">
    @if (!$mod)
    <h1 class="text-3xl font-bold mb-4">Not Found</h1>
    <p>Mod not found. Please check the URL.</p>
    @else
        <h1 class="text-3xl font-bold mb-4">{{ $mod->name }}</h1>
        <p>{{ $mod->description }}</p>
    @endif
</div>