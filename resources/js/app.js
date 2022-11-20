import jQuery from 'jquery';

import '../css/datatables.min.css';
import '../css/app.css';

import dt from 'datatables.net-scroller-dt';

import './prettyprint';

import './bootstrap';

dt(window, jQuery);

jQuery(function($)
{
    // Dirty and inconsistent height variable.
    var rowH = (684 + 10) / 3;

    var modsTable = $('#mods').DataTable(
    {
        processing: true,
        serverSide: true,
        ajax: 
        {
            "url": "/retrieve",
            "contentType": "application/json",
            "type": "GET"
        },
        dom: 'rt',
        stateSave: false,
        scroller: 
        {
            rowHeight: rowH,
            displayBuffer: 36,
            boundaryScale: 0.5,
            loadingIndicator: true
        },
        deferRender: true,
        scrollY: 1000,
        paging: true,
        pageLength: 6,
        createdRow: function (row, data, dataIndex) 
        {
            if (data[17])
            {
                var classes = data[17].split(" ");

                for (var i = 0; i < classes.length; i++)
                {
                    $(row).addClass(classes[i]);
                }
            }
            else if (data[16])
            {
                var classes = data[16].split(" ");

                for (var i = 0; i < classes.length; i++)
                {
                    $(row).addClass(classes[i]);
                }
            }
            else
            {
                $(row).addClass('card-style-default');
            }
        },
        columnDefs: [
            {
                targets: [0, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17],
                visible: false
            },
            {
                targets: 0,
                name: 'DT_RowIndex',
            },
            {
                targets: 1,
                "className": "card-image-td",
                "render": function ( data, type, row, meta ) {
                    var image = '/images/default_mod.png';

                    if (row[1].length)
                    {
                        image = row[1];
                    }

                    return '<img class="card-image" src="' + image + '" alt="Mod Image"></img>';
                    }
            },
            {
                targets: 2,
                "render": function ( data, type, row, meta ) {
                    var link = row[0];

                    if (row[11].length)
                    {
                        link = row[11];
                    }

                    return '<h1 class="text-3xl font-bold text-center"><a href="/view/' + link + '" class="hover:underline">' + data + '</a></h1>';
                    }
            },
            {
                targets: 3,
                "className": "card-desc-td"
            },
            {
                targets: 4,
                "render": function ( data, type, row, meta ) {
                    return '<div class="card-seed"><img class="card-icon" src="' + row[13] + '" alt="Icon" /> ' + data + '</div>';
                    }
            },
            {
                targets: 5,
                "render": function ( data, type, row, meta ) {
                    var link = row[10];

                    if (!link.includes(row[15]))
                    {
                        link = row[15] + "://" +  link;
                    }

                    return '<div class="card-seed"><img class="card-icon" src="' + row[14] + '" alt="Icon" /> <a href="' + link + '" class="hover:underline" target="_blank">' + data + '</a></div>';
                    }
            },
            {
                targets: 6,
                "render": function ( data, type, row, meta ) {
                    return '<div class="card-icons"><div class="card-icon-div text-center"><svg class="card-icon" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M12 21c-5 0-11-5-11-9s6-9 11-9s11 5 11 9s-6 9-11 9zm0-14a5 5 0 1 0 0 10a5 5 0 0 0 0-10h0z"></path></svg> <span class="card-icon-text">' + row[8] + '</span></div> <div class="card-icon-div text-center"><svg class="card-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"></path></svg> <span class="card-icon-text">' + row[6] + '</span></div> <div class="card-icon-div text-center"><svg class="card-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="none" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg> <span class="card-icon-text">' + row[7] + '</span></div></div>';
                    }
            },
            {
                targets: 12,
                "render": function ( data, type, row, meta ) {
                    var link = row[0];

                    if (row[11].length)
                    {
                        link = row[11];
                    }

                    var orig_link = row[10];

                    if (!orig_link.includes(row[15]))
                    {
                        orig_link = row[15] + "://" + orig_link;
                    }

                    return '<div class="flex flex-col text-center"><a href="/view/' + link + '" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mt-2">View</a> <a href="' + orig_link + '/' + row[9] + '" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 mt-2" target="_blank">Original</a></div>';
                    }
            }
        ]
    });

    // For when we need to calculate the amount of mods per line with CSS grid layout (when we're auto-filling grid).
    // We'll need to dynamically change the row height by (Max Row Height / Mods Per Line) in order for Scroller to work properly.
    /*
    setTimeout(function()
    {
        var grid = $('#mods > tbody');
    
        // Get number of columns (mods per line).
        var columns = grid.css("grid-template-columns").split(" ").length;
    
        // Now get height of first row.
        var rowHeight = $('#mods > tbody > tr').first().height();

        console.log("Columns => " + columns + ". Row Height => " + rowHeight);
    }, 2000);
    */

    $('#default-search').on( 'keyup click', function () 
    {
        if ($('#mods').length )
        {
            modsTable.search($('#default-search').val()).draw();
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