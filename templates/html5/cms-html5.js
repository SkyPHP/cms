$(function(){

    $('.pagination-limit').live('change',function() {
        url1 = location.href.split('?');
		url1 = url1[0];

		url2 = location.href;
        url2 = removeParam($(this).attr('name'),url2); // remove limit
        url2 = removeParam('page'+$(this).attr('i'),url2); // remove page
		url2 = removeParam('limit'+$(this).attr('i'),url2);
        location.href = url2 + (url2==url1?'?':'&') + $(this).attr('name') + '=' + $(this).val();
    });

    $('#skybox_error .ui-icon-circle-close').die().live('click', function() {
        $.skyboxHide();
        return false;
    });

});


jQuery.fn.animateChange = function(fn) {
    var that = this,
        $thing = jQuery(this), 
        orig_color = $thing.css('backgroundColor');
    $thing.animate({
        'backgroundColor' : 'yellow',
        'duration' : 400
    }).animate({
        'backgroundColor' : orig_color,
        'duration' : 400
    }, function() {
        $(this).css('backgroundColor', '');
        if (typeof fn == 'function') {
            aql._callback(fn, that);
        }
    });
    return this;
}

function ui_error_skybox(html) {
    $.skyboxShow('<div id="skybox_error">' + ui_error(html) + '</div>');
}

function replace_with_load($div, w, h) {
    var id = Math.floor(Math.random()*1001);
    var id_attr = 'id="'+ id +'"';
    if (w) w = ' width="' + w + '" ';
    if (h) h = ' height="' + h + '" ';
    $div.html('<div class="loading" ' + id_attr + '><img src="/images/loading.gif" ' + w + h + '/></div>');
    return id;
}

function number_format( number, decimals, dec_point, thousands_sep ) {
    // http://kevin.vanzonneveld.net
    // *     example 1: number_format(1234.5678, 2, '.', '');
    // *     returns 1: 1234.57

    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
    var d = dec_point == undefined ? "." : dec_point;
    var t = thousands_sep == undefined ? "," : thousands_sep, s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}