import './bootstrap';
import '../css/app.css';

import './components/Mods';
import './components/NavBar';

import jQuery from 'jquery';

import './prettyprint';

jQuery(function($)
{
    $('#default-search').on( 'keyup click', function () 
    {
        if ($('#mods').length )
        {
            
        }
        else
        {
            window.location.href = "/";
        }
    });

    var main_url = curUrl;

    $(document).on('click', '.viewBtn', function(e)
    {
        var tar = $(e.currentTarget);
        var view = tar.attr('data-show-id');

        var new_url = main_url + '/' + view;

        if (view == 'overview')
        {
            new_url = main_url;
        }

        if (history.pushState) 
        {
            window.history.pushState({path:new_url},'',new_url);
        }

        reloadContent(view);
    });

    $(document).on('click', '.itemBtn', function(e)
    {
        var tar = $(e.currentTarget);
        var view = tar.attr('data-show-id');

        var new_url = main_url + '/' + view;

        window.location.href = new_url;
    });

    var dl_idx = 1;

    $(document).on('click', '#downloadsBtn', function(e)
    {
        // Increase index now.
        dl_idx++;

        // Add more inputs using download index.
        var nameHtml = '<label class="block text-gray-200 text-sm font-bold mt-4 mb-2" for="download-' + dl_idx  +'-name">Name</label><input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="download-' + dl_idx  +'-name" name="download-' + dl_idx  +'-name" type="text" placeholder="Display name of file." />';

        var urlHtml = '<label class="block text-gray-200 text-sm mt-3 font-bold mb-2" for="download-' + dl_idx  +'-url">URL</label><input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="download-' + dl_idx  +'-url" name="download-' + dl_idx  +'-url" type="text" placeholder="URL of file." />';

        var rmbtnHtml = '<button type="button" class="dl-rm-btn text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 mt-2">Remove</button>';

        // Append both to existing HTML.
        var elem = $('#downloads');

        elem.append('<div id="download-' + dl_idx + '">' + nameHtml + urlHtml + rmbtnHtml + '</div>');
    });

    var ss_idx = 1;

    $(document).on('click', '#screenshotsBtn', function(e)
    {
        // Increase index now.
        ss_idx++;

        // Add more inputs using download index.
        var urlHtml = '<label class="block text-gray-200 text-sm mt-3 font-bold mb-2" for="screenshot-' + ss_idx  +'-url">URL</label><input class="shadow appearance-none border-blue-900 rounded w-full py-2 px-3 text-gray-200 bg-gray-800 leading-tight focus:outline-none focus:shadow-outline" id="screenshot-' + ss_idx  +'-url" name="screenshot-' + ss_idx  +'-url" type="text" placeholder="URL to screenshot." />';

        var rmbtnHtml = '<button type="button" class="ss-rm-btn text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 mt-2">Remove</button>';

        // Append to existing HTML.
        var elem = $('#screenshots');

        elem.append('<div id="screenshot-' + dl_idx + '">' + urlHtml + rmbtnHtml + '</div>');
    });

    $(document).on('click', '.dl-rm-btn', function(e)
    {
        var tar = $(e.currentTarget);

        tar.parent().remove();
    });

    $(document).on('click', '.ss-rm-btn', function(e)
    {
        var tar = $(e.currentTarget);

        tar.parent().remove();
    });

    function reloadContent(view='overview')
    {
        $('[data-view]').each(function(idx)
        {
            if ($(this).attr('data-id') == view)
            {
                var html = $("[data-id=" + view + "]").html();

                $('#viewContent').html(html);
            }
        });

        $('[data-view-btn]').each(function(idx)
        {

            $(this).removeClass('bg-gray-500');
            $(this).removeClass('bg-gray-900');

            if ($(this).attr('data-show-id') == view)
            {
                $(this).addClass("bg-gray-500");
            }
            else
            {
                $(this).addClass("bg-gray-900");
            }
        });
    }

    if ($('#modContent').length)
    {
        reloadContent(curView);
    }
});