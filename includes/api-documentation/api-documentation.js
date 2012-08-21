
$(function() {

    var binder = function(f) {
        return function(e) {
            f($(this), e || null);
        };
    };

    var inner = '.content',
        key = 'data-state',
        actions = [
            { state: 'closed', method: 'slideUp'},
            { state: 'open', method: 'slideDown'}
        ],
        running = 0;

    var getOpenSections = function() {
        return $('#doc-sidebar').find('[' + key + '="open"]');
    };

    var toggleSection = function(open) {
        var action = actions[!!open+0];
        return function($li) {
            running++;
            return $li.find(inner)[action.method]('fast', function() {
                $li.attr(key, action.state);
                running--;
            });
        };
    };

    var closeSection = function($li) {
        return toggleSection(false)($li);
    };

    var openSection = function open($li) {
        if ($li.attr(key) == 'open' || running > 0) {
            return;
        }
        getOpenSections().each(binder(closeSection));
        toggleSection(true)($li);
    };

    var headingClick = function($b, e) {
        if (e.srcElement.nodeName == 'H4') {
            openSection($b.closest('li'));
        }
    };

    // binding to the body because of ajax refreshing of the page
    $('#page').on('click', '#doc-sidebar h4', binder(headingClick));

    // look for appearing pre.mdown for code styling
    var CM_MODE_PATH = '/lib/codemirror/mode/',
        cm_modes = {
            php: {m: 'application/x-httpd-php', p: 'php/php.js'},
            js: {m: 'text/javascript', p: 'javascript/javascript.js'},
            html: this.php
        };

    var getMode = function(path, cb) {
        $.getScript(CM_MODE_PATH + path, cb);
    };

    // whenever code snippet appears on the page.
    $('pre.mdown').livequery(function() {

        var $t = $(this),
            $c = $t.find('code'),
            lang = $c.data('lang'),
            content = $c.html();

        if (!lang) return;

        var mode = cm_modes[lang];

        content = content.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
        $t.addClass('cm-s-default');

        CodeMirror.runMode(content, mode.m, $t.get(0));
    });

});
