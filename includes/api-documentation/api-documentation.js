
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
    $('body').on('click', '#doc-sidebar h4', binder(headingClick));

    // look for appearing pre.mdown for code styling
    var CM_MODE_PATH = '/lib/codemirror/mode/',
        cm_modes = {
            php: {m: 'application/x-httpd-php', p: 'php/php.js'},
            js: {m: 'text/javascript', p: 'javascript/javascript.js'}
        };

    var looks_like = {
        js: function(str) {
            return str.match(/\/bvar\b/) ||
                str.match(/\$\(/) ||
                str.match(/\=\s*\{/) ||
                str.match(/^\s*\{/);
        },
        php: function(str) {
            return str.match(/\$[\w]+/) || str.match(/&lt;php/) || str.match(/array\(/);
        }
    };

    // whenever code snippet appears on the page.
    $('pre').livequery(function() {

        var $t = $(this),
            $c = $t.find('code'),
            content = $c.html();

        if (!$c.length) return;

        var type = looks_like.php(content) ? 'php' : null;
        if (!type) {
            type = looks_like.js(content) ? 'js' : null;
        }

        if (!type) {
            return;
        }

        var mode = cm_modes[type];

        content = content.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
        $t.addClass('cm-s-default');

        CodeMirror.runMode(content, mode.m, $t.get(0));
    });

});
