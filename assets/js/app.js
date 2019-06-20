/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)



require('bootstrap');
require('../css/app.css');
require('../css/global.scss');
require('datatables.net');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('@fortawesome/fontawesome-free');
require('chart.js');
require('datatables.net');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('webpack-jquery-ui');
require('webpack-jquery-ui/interactions');
require('webpack-jquery-ui/widgets');
require('webpack-jquery-ui/effects');
window.Scrambo = require('scrambo');
window.jQuery = require('jquery');
window.$ = window.jQuery;
window.chart = require('chart.js');

require('bootstrap-select');
window.bootstrapSelect = require('./bootstrap-select');
const $ = require('jquery');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    lasttimeseen();
    let _lasttimeseen = setInterval(lasttimeseen, 600000);
    function lasttimeseen() {
        $.ajax({
            url: "/check-online",
            type: "POST",
            data: {

            },
            success: function (msg) {}
        });
    }

});

