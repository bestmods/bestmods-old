<div class="text-white m-7">
    @if (!$mod)
    <h1 class="text-3xl font-bold mb-4">Not Found</h1>
    <p>Mod not found. Please check the URL.</p>
    @else
        <div id="mod">
            @if (isset($view) && $view == 'edit')
                @include('create')
            @else
            <div id="modHeader">
                <div id="modImage" class="flex justify-center">
                    <img class="rounded-t max-w-md md:max-w-3xl" src="{{ isset($headinfo['image']) ? $headinfo['image'] : Illuminate\Support\Facades\URL::to('/images/mods/default.png') }}" />
                </div>
                <div id="modName" class="flex justify-center">
                    <h1 class="text-4xl font-bold mb-4">{{ $mod->name }}</h1>
                </div>
            </div>

            <div id="modButtons">
                <div class="flex justify-center">
                    <button data-view-btn data-show-id="overview" class="viewBtn text-white font-bold rounded-t p-3 mr-1 bg-opacity-50 {{ $view == 'overview' ? 'bg-gray-500' : 'bg-gray-900' }}">
                        Overview
                    </button>
                    <button data-view-btn data-show-id="install" class="viewBtn text-white font-bold rounded-t p-3 mr-1 bg-opacity-50 {{ $view == 'install' ? 'bg-gray-500' : 'bg-gray-900' }}">Installation</button>
                    <button data-view-btn data-show-id="downloads" class="viewBtn text-white font-bold rounded-t p-3 mr-1 bg-opacity-50 {{ $view == 'downloads' ? ' bg-gray-500' : 'bg-gray-900' }}">Downloads</button>
                </div>
                <div id="modContent" class="boxView p-5">
                    <div id="viewContent"></div>
                    <div data-view data-id="overview" class="hidden">
                        {{ isset($description) ? $description : 'No description found.' }}
                    </div>
                    <div data-view data-id="install" class="hidden">
                        <p>{{ isset($install_help) ? $install_help : 'No installation help found.' }}</p>
                    </div>
                    <div data-view data-id="downloads" class="hidden">
                        <p>
                            @if (isset($downloads) && is_array($downloads) && count($downloads) > 0)
                                @foreach ($downloads as $download)
                                    <a class="modDownload" href="{{ $download->url }}" target="_blank">{{ $download->name }}</a>
                                @endforeach
                            @else
                                No downloads found.
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-between flex-col">
                        <a href="{{ $base_url . '/edit' }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded px-4 py-2 mt-2 max-w-xs">Edit</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif
</div>