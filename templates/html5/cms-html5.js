$(document).ready(function() {
   
    $('uploader').livequery(function(){
        $(this).uploader();
    });

    $('.choose_file').livequery(function() {
       $(this).button({
          icons: {
              primary: 'ui-icon-folder-open' 
          }
       });
    });

    $('.upload_file').livequery(function() {
       $(this).button({
           icons: {
               primary: 'ui-icon-transferthick-e-w'
           } 
       });
    });

    $('.choose_file').livequery(function() {
        $('.choose_file').each(function() {
            var $input = $(this),
                id = $input.attr('id'),
                $up = $input.closest('uploader'),
                $status = $('.upload_status', $up),
                data = {
                    'vfolder' : $up.attr('vfolder'),
                    'db_field' : $up.attr('db_field'),
                    'db_row_ide' : $up.attr('db_row_ide')
                },
                browse_button = id,
                upload_button = 'upload_' + id.split('_')[1],
                uploader = new plupload.Uploader({
                    runtimes: 'html5,flash,html4',
                    browse_button: browse_button,
                    url: '/media/upload',
                    flash_swf_url: '/lib/plupload/js/plupload.flash.swf'
                });
            uploader.bind('FilesAdded', function(up, files) {
                $.each(files, function(i, file) {
                    $status.append('<div id="' + file.id + '" class="pluploadUploadFile"><a class="ui-icon ui-icon-minus"></a>' + file.name + '</div>');
                });
            });
            uploader.bind('FilesRemoved', function(up, files){
                $.each(files, function(i, file) {
                    $('#'+ file.id).remove();  
                });
            });
            uploader.bind('UploadFile', function(up, files) {
                up.settings.multipart_params = data;
            });
            uploader.bind('UploadProgress', function(up, file) {
                $('#' + file.id + ' .ui-icon').removeClass('ui-icon-minus').addClass('ui-icon-transfer-e-w');
            });
            uploader.bind('FileUploaded', function(up, file, info) {
                var r = $.parseJSON(info.response);
                var $ic = $('#' + file.id + ' .ui-icon');
                if (r.status != 'OK') {
                    up.stop();
                    alert(r.errors);
                    $ic.removeClass('ui-icon-transfer-e-w').addClass('ui-icon-alert');
                } else {
                    $ic.removeClass('ui-icon-transfer-e-w').addClass('ui-icon-check');
                }
                $ic.closest('div').animateChange();
            });
            uploader.bind('UploadComplete', function(up) {
                $up.uploader(); 
            });
            uploader.init();
            $('.upload_file', $up).click(function(e){
                uploader.start();
                e.preventDefault();
            });
            $('.ui-icon').live('click', function() {
                if ($(this).hasClass('ui-icon-minus')) {
                    var id = $(this).closest('.pluploadUploadFile').attr('id');
                    uploader.removeFile(uploader.getFile(id));
                }
                return false; 
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
                $this.html('<ul class="mediaItemGallery has-floats"></ul>');
                $gallery = $('.mediaItemGallery', $this);
                replace_with_load($gallery);
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
                $.post('/media/gallery', settings, function(data) {
                    $gallery.html(data);
                    methods.bindContextMenu($this);
                });
                var id = Math.floor(Math.random()*11);
                $this.append('<button class="choose_file" id="choose_' + id + '">Choose Files</button><button class="upload_file" id="upload_' + id + '">Upload</button>');
                $this.append('<div class="upload_status"></div>');
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
                var $gallery =  $('.mediaItemGallery', $uploader);
                $gallery.sortable({
                    items: 'li.mediaItem',
                    update: function() {
                        var order = $gallery.sortable('serialize');
                        $.post('/media/set-items', order, function(json) {
                            if (json.status != 'OK') {
                                alert(json.errors);
                            }
                        });
                    }
                });
            } else {
                $.error('Sortable in jQuery UI not loaded. Sort disabled.');
            }
        },
        bindContextMenu : function($uploader) {
             $('.mediaItem[ide]:visible', $uploader).each(function() {
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

function replace_with_load($div, w, h) {
    var id = Math.floor(Math.random()*101);
    var id_attr = 'id="'+ id +'"';
    if (w) w = ' width="' + w + '" ';
    if (h) h = ' height="' + h + '" ';
    $div.html('<div class="loading" ' + id_attr + '><img src="/images/loading.gif" ' + w + h + '/></div>');
    return id;
}