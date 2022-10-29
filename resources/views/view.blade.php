<div class="text-white m-7">
    @if (!$mod)
    <h1 class="text-3xl font-bold mb-4">Not Found</h1>
    <p>Mod not found. Please check the URL.</p>
    @else
        <div id="mod">
            <div id="modHeader">
                <div id="modImage" class="flex justify-center">
                    <img class="rounded-t" src="/images/mods/{{ $mod->mimage ? $mod->mimage : 'default.png' }}" />
                </div>
                <div id="modName" class="flex justify-center">
                    <h1 class="text-4xl font-bold mb-4">{{ $mod->name }}</h1>
                </div>
            </div>

            <div id="modButtons">
                <div class="flex justify-center">
                <button data-view-btn data-show-id="overview" class="viewBtn text-white font-bold rounded-t p-3 mr-1 bg-gray-900{{ $view == 'overview' ? ' viewSel' : '' }}">
                    Overview
                </button>
                <button data-view-btn data-show-id="install" class="viewBtn text-white font-bold rounded-t p-3 mr-1 bg-gray-900{{ $view == 'install' ? ' viewSel' : '' }}">
                    Installation
                </button>
                <button data-view-btn data-show-id="downloads" class="viewBtn text-white font-bold rounded-t p-3 mr-1 bg-gray-900{{ $view == 'downloads' ? ' viewSel' : '' }}">
                    Downloads
                </button>
                </div>
                <div id="modContent" class="boxView p-5">
                    <div id="viewContent"></div>
                    <div data-view data-id="overview" class="hidden">
                        {{ $mod->description }}
                    </div>
                    <div data-view data-id="install" class="hidden">
                        <p>{{ $mod->install_help }}</p>
                    </div>
                    <div data-view data-id="downloads" class="hidden">
                        <p>Coming soon!</p>
                    </div>
                </id>
            </div>
        </div>
    @endif
</div>