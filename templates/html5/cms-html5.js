jQuery.fn.animateChange = function() {
    var $thing = jQuery(this), 
        orig_color = $thing.css('backgroundColor');
    $thing.animate({
        'backgroundColor' : 'yellow',
        'duration' : 400
    }).animate({
        'backgroundColor' : orig_color,
        'duration' : 400
    }, function() {
        $(this).css('backgroundColor', '');
    });
    return this;
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