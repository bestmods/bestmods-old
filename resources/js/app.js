import $ from 'jquery';

import './bootstrap';
import '../css/app.css';

import setup from 'datatables.net-dt'
import 'datatables.net-scroller-dt';
window.DataTable = setup(window, $)

import '../css/datatables.min.css';

$(document).ready(function ()
{
    var table = $('#mods').DataTable({
        //ajax: "/retrieve",
        "order": [[ 0, 'asc' ], [ 1, 'asc' ]]
    });
    
});