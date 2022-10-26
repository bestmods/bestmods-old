
@isset ($mod)
    <div class="max-w-sm rounded overflow-hidden shadow-lg">
        <img class="w-full" src="/images/mods/{{(isset($mod->mimage) && mb_strlen($mod->mimage) > 0) ? $mod->mimage : 'default.png'}}" alt="{{ $mod->name }}">
        <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ $mod->name }}</div>
            <p class="text-gray-200 text-base">
                {{ $mod->description }}
            </p>
        </div>
    </div>
@endisset