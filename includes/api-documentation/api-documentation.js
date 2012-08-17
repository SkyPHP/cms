
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

});
