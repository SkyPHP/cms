$(document).ready(function() {
   
    $('uploader').livequery(function(){
        $(this).uploader();
    });

    $('.upload_file').livequery(function() {
        $('.upload_file').each(function() {
            var $input = $(this),
                id = $input.attr('id'),
                $up = $input.closest('uploader'),
                data = {
                    'vfolder' : $up.attr('vfolder'),
                    'db_field' : $up.attr('db_field'),
                    'db_row_ide' : $up.attr('db_row_ide')
                };
            $input.uploadify({
                'uploader'      : '/lib/jquery.uploadify/uploadify.swf',
                'script'        : '/media/upload',
                'scriptData'    : data,
                'multi'         : true,
                'method'        : 'post',
                'onComplete'    : function(event, ID, fileObj, response, data) {
                    var r = $.parseJSON(response);
                    console.log(r);
                    if (r.status != 'OK') {
                        $input.uploadifyClearQueue();
                        alert(r.errors);
                    }
                },
                'onAllComplete' : function(event, data) {
                    $up.uploader();  
                },
                'auto'          : true
            });
        });
    });
    
});

(function($) {

    var settings = {
        'vfolder' : '',
        'width' : 100,
        'height' : '',
        'limit' : 0,
        'empty' : '',
        'sort' : false
    }

    var methods = {
        init : function(options) {
            return this.each(function() {
                var $this = $(this);
                var opts = [];
                // var attrs = this.attributes;
                $this.html('<ul class="mediaItemGallery has-floats"><img src="/images/loading.gif" /></ul>');
                $gallery = $('.mediaItemGallery', $this);
                opts['vfolder'] = $this.attr('vfolder');
                opts['width'] = $this.attr('width');
                opts['height'] = $this.attr('height');
                opts['limit'] = $this.attr('limit');
                opts['empty'] = $this.attr('empty');
                opts['sort'] = $this.attr('sort');
                $.extend(settings, opts);
                $.extend(settings, options);
                if (settings.width == 'auto') settings.width = $gallery.width() - 8;
                if (!settings.vfolder) {
                    $gallery.html('<p><strong>Uploader Error: No vfolder set.</strong></p>');
                    return;
                };
                methods.setContextMenu();
                $.post('/media-gallery', settings, function(data) {
                    $gallery.html(data);
                    methods.bindContextMenu($this);
                });
                var id = Math.floor(Math.random()*11);
                $this.append('<input type="file" class="button upload_file" id="' + id + '" value="Upload Files" />');
                if (settings.sort) methods.doSort($this);
            });
        },
        setContextMenu : function() {
            if (!$('#mediaItemContextMenu').length) {
                var contextMenu = '<ul id="mediaItemContextMenu" class="contextMenu">';
                contextMenu += '<li class="properties"><a href="#view">View Image</a></li>';
                contextMenu += '<li class="edit"><a href="#properties">Properties</a></li>';
                contextMenu += '<li class="delete"><a href="#delete">Delete Image</a></li>';
                contextMenu += '</ul>';
                $('body').append(contextMenu);
            }
        },
        doSort : function($uploader) {
            if ($.isFunction($.ui.sortable)) {
                $uploader.append('<p class="small"><strong>Sort Enabled:</strong> You can drag the image and re-order their them.</p>');
                $('.mediaItemGallery', $uploader).sortable();
            } else {
                $.error('Sortable in jQuery UI not loaded. Sort disabled.');
            }
        },
        bindContextMenu : function($uploader) {
             $('.mediaItem[ide]', $uploader).each(function() {
                 $(this).contextMenu(
                    { menu: 'mediaItemContextMenu' },
                    function(action, el, pos) {
                       if ($('html').hasClass('ie7')) action = action.split('#')[1]; // otherwise the action is the full URL
                       var contextFunctions = {
                           'properties' : contextMenu_properties,
                           'view' : contextMenu_view,
                           'delete' : contextMenu_delete
                       };
                       if (contextFunctions[action]) {
                           var now = contextFunctions[action];
                           now(el);
                       }
                    }
                );
             });
        }
    }

    $.fn.uploader = function ( method ) {
        if (methods[method]) {
            return methods[method].apply(Array.prototype.slice.call( arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + 'does not exist in UPLOADER');
        }
    }

}) (jQuery);

function contextMenu_properties(el) {
    var ide = $(el).attr('ide');
    $.skybox('/skybox/form/media_item/' + ide);
}

function contextMenu_view(el) {
    var ide = $(el).attr('instance_ide');
    window.location = '/media/' + ide;
}

function contextMenu_delete(el) {
    var ide = $(el).attr('ide');
    var $el = $('.mediaItem[ide=' + ide + ']:visible');
    if (!$el.length) return;
    if (ide && confirm('Are you sure you want to delete this image?')) {
        var $up = $el.closest('uploader');
        $.post('/ajax/delete-media-item/' + ide, function(json) {
           if (json.status == 'OK') {
               if ($up.length) {
                   $up.uploader();
               } else {
                   $el.remove();
               }
            }
            else alert(json.errors);
        });
    }
}