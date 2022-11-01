<div class="text-white m-7">
    @if (isset($item_created) && $item_created)
        <div class="bg-green-900 bg-opacity-70 p-4 rounded text-white text-sm mb-4">
            <p>Successfully created or edited item!</p>
        </div>
    @endif

    @if (isset($id))
    <h1 class="text-3xl font-bold mb-4">Edit Item!</h1>
    @else
    <h1 class="text-3xl font-bold mb-4">Create Item - {{ isset($type) ? ucfirst($type) : 'Mod' }}!</h1>
    @endif
    <div>
        <button data-view-btn data-show-id="mod" class="itemBtn text-white font-bold rounded-t p-3 mr-1 bg-opacity-50 {{ !isset($type) || (isset($type) && $type == 'mod') ? 'bg-gray-500' : 'bg-gray-900' }}">Mod</button>
        <button data-view-btn data-show-id="seed" class="itemBtn text-white font-bold rounded-t p-3 mr-1 bg-opacity-50 {{ (isset($type) && $type == 'seed') ? 'bg-gray-500' : 'bg-gray-900' }}">Seed</button>
        <button data-view-btn data-show-id="game" class="itemBtn text-white font-bold rounded-t p-3 mr-1 bg-opacity-50 {{ (isset($type) && $type == 'game') ? 'bg-gray-500' : 'bg-gray-900' }}">Game</button>
        
        <form method="POST" action="{{ Illuminate\Support\Facades\URL::to('/create', array('type' => 'mod')) }}" class="flex flex-col bg-black bg-opacity-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <input type="hidden" name="type" value="{{ isset($type) ? $type : 'mod' }}" />
            @if (isset($id))
            <input type="hidden" name="id" value="{{ $id }}" />
            @endif

            @if (isset($type) && $type == 'game')
            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="name">Name</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Game Name"{{!! isset($name) ? ' value="' . $name . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="name_short">Name Short</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="name_short" name="name_short" type="text" placeholder="Short Game Name"{{!! isset($name_short) ? ' value="' . $name_short . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="image">Image Name</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="image" name="image" type="text" placeholder="Image Name"{{!! isset($image) ? ' value="' . $image . '"' : '' !!}} />
            </div>
            @elseif (isset($type) && $type == 'seed')
            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="name">Name</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Seed Name"{{ isset($name) ? ' value="' . $name . '"' : '' }} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="protocol">Protocol</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="protocol" name="protocol" type="text" placeholder="https"{{!! isset($protocol) ? ' value="' . $protocol . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="url">URL</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="url" name="url" type="text" placeholder="moddingcommunity.com"{{!! isset($url) ? ' value="' . $url . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="image">Image Name</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="image" name="image" type="text" placeholder="Image Name"{{!! isset($image) ? ' value="' . $image . '"' : '' !!}} />
            </div>
            @else
            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="seed">Seed</label>
                <select class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="seed" name="seed" >
                    @if (isset($seeds))
                        @foreach ($seeds as $seed)
                            <option value="{{ $seed->id }}"{{ (isset($curSeed) && $curSeed == $seed) ? ' selected' : '' }}>{{!! $seed->name !!}}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="game">Game</label>
                <select class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="game" name="game">
                    @if (isset($games))
                        @foreach ($games as $game)
                            <option value="{{ $game->id }}"{{ (isset($curGame) && $curGame == $seed) ? ' selected' : '' }}>{{!! $game->name !!}}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="name">Name</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Mod Name" {{!! isset($name) ? ' value="' . $name . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="url">URL</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="url" name="url" type="text" placeholder="https://moddingcommunity.com/something" {{!! isset($url) ? ' value="' . $url . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="custom_url">Custom URL</label>
                <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="custom_url" name="custom_url" type="text" placeholder="modexample" {{!! isset($custom_url) ? ' value="' . $custom_url . '"' : '' !!}} />
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="description">Description</label>
                <textarea rows="16" cols="32" class="shadow appearance-none border-blue-900 rounded w-full p-6 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="More about this project.">{{ isset($description) ? $description  : '' }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="description_short">Description Short</label>
                <textarea rows="8" cols="16" class="shadow appearance-none border-blue-900 rounded w-full p-6 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="description_short" name="description_short" placeholder="More about this project.">{{ isset($description_short) ? $description_short  : '' }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-200 text-sm font-bold mb-2" for="install_help">Install Help</label>
                <textarea rows="16" cols="32" class="shadow appearance-none border-blue-900 rounded w-full p-6 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="install_help" name="install_help" placeholder="Installation help.">{{ isset($install_help) ? $install_help  : '' }}</textarea>
            </div>

            <div class="mb-4">
                <h2 class="text-xl">Download URLs</h2>

                <div id="downloads">
                    <label class="block text-gray-200 text-sm mt-4 font-bold mb-2" for="download-1-name">Name</label>
                    <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="download-1-name" name="download-1-name" type="text" placeholder="Display name of file." {{!! isset($downloads[0]) ? ' value="' . $downloads[0]['name'] . '"' : '' !!}} />

                    <label class="block text-gray-200 text-sm mt-3 font-bold mb-2" for="download-1-url">URL</label>
                    <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="download-1-url" name="download-1-url" type="text" placeholder="URL of file." {{!! isset($downloads[0]) ? ' value="' . $downloads[0]['url'] . '"' : '' !!}} />

                </div>

                <button type="button" id="downloadsBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mt-2">Add More</button>
            </div>

            <div class="mb-4">
                <h2 class="text-xl">Screenshots</h2>

                <div id="screenshots">
                    <label class="block text-gray-200 text-sm mt-3 font-bold mb-2" for="screenshot-1-url">URL</label>
                    <input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="screenshot-1-url" name="screenshot-1-url" type="text" placeholder="URL to screenshot." {{!! isset($screenshots[0]) ? ' value="' . $screenshots[0] . '"' : '' !!}} />
                </div>

                <button type="button" id="screenshotsBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mt-2">Add More</button>
            </div>
            @endif

            <button type="submit" class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mt-2">Add!</button>
        </form>
    </div>
</div>