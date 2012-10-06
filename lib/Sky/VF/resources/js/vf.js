
window.vf = window.vf || {};

(function() {
    vf = {
        slideshow: function($div, params) {
            if (!$div) return null;
            var that = {
                container : $div,
                mainContainer : $('.vf-slideshow-main', $div),
                mainContainerWidth: 0,
                mainImageContainer : $('.vf-slideshow-image', $div),
                captionContainer : $('.vf-slideshow-caption', $div),
                controls : $('.vf-slideshow-controls', $div),
                mainImageContainerWidth : 0,
                numImages : 0,
                thumbContainer : $('.vf-slideshow-thumbs', $div),
                autostart : $div.attr('autostart') ? true : false,
                autohide : ($div.attr('autohide') == 'yes') ? true : false,
                transition : $div.attr('transition'),
                activeClass : 'selected',
                delay : $div.attr('delay')
            };
            return {
                setWidth : function() {
                    var w = 0, num = 0;
                    $('img', that.mainContainer).each(function(i) {
                        w += $(this).width();
                        if (i === 0) {
                            that.container.width(w);
                            that.mainContainerWidth = w;
                            that.mainContainer.height($(this).height());
                        }
                        if (that.transition == 'fade') {
                            $(this).css('position', 'absolute');
                        }
                        num++;
                    });
                    that.mainImageContainerWidth = w;
                    that.mainImageContainer.width(w);
                    that.numImages = num;
                    return this;
                },
                init : function() {
                    this.setWidth().binders();
                    if (that.autostart) this.start();
                },
                binders : function() {
                    var ob = this;
                    $('.vf-slideshow-thumb', that.thumbContainer).live('click', function() {
                        var $this = $(this),
                            index = $('.vf-slideshow-thumb', that.thumbContainer).index($this);
                        ob.goTo(index);
                    });
                    $('a', that.controls).click(function(e) {
                        e.preventDefault();
                        var $this = $(this),
                            action = $this.attr('href');
                        action = action.split('#')[1];
                        switch (action) {
                            case 'playpause' :
                                if (that.interval) {
                                    ob.stop();
                                    $this.removeClass('vf-slideshow-pause').addClass('vf-slideshow-play');
                                } else {
                                    ob.start();
                                    $this.removeClass('vf-slideshow-play').addClass('vf-slideshow-pause');
                                }
                                break;
                            case 'prev' :
                                ob.goTo(ob.getPrevSelectedPosition());
                                break;
                            case 'next' :
                                ob.goTo(ob.getNextSelectedPosition());
                                break;
                            case 'enlarge' :
                                var curr = ob.getCurrentSelectedPosition(),
                                    ide = $($('.vf-slideshow-thumb', that.thumbContainer)[curr]).attr('ide');
                                $.skybox('/ajax/vf2/enlarge/' + ide);
                                break;
                            default:
                                break;
                        }
                    });
                    that.mainImageContainer.css('cursor', 'pointer').click(function(e) {
                        e.preventDefault();
                        ob.goTo(ob.getNextSelectedPosition());
                    });
                    if (that.autohide) {
                        $.data(that.controls[0], 'realHeight', that.controls.height());
                        that.controls.stop().animate({ height: 0, paddingTop: 0, paddingBottom: 0 });
                        that.mainContainer.hoverIntent({
                            timeout: 0,
                            over: function() {
                                that.controls.stop().animate({ height : that.controls.data('realHeight'), padding:10 }, 100);
                            },
                            out: function() {
                                that.controls.stop().animate({ height: 0, paddingTop: 0, paddingBottom: 0 }, 100);
                            }
                        });
                    }
                },
                start: function() {
                    var ob  = this;
                    that.interval = setInterval(function() {
                        ob.goTo(ob.getNextSelectedPosition());
                    }, that.delay);
                },
                stop: function() {
                    clearInterval(that.interval);
                    that.interval = null;
                    return this;
                },
                getCurrentSelectedPosition : function() {
                    var pos = 0;
                    $('.vf-slideshow-thumb', that.thumbContainer).each(function(i) {
                        if ($(this).hasClass('selected')) pos = i;
                    });
                    return pos;
                },
                getNextSelectedPosition : function(currentPosition) {
                    if (!currentPosition) currentPosition = this.getCurrentSelectedPosition();
                    if (currentPosition == that.numImages - 1) return 0;
                    return currentPosition + 1;
                },
                getPrevSelectedPosition : function(currentPosition) {
                    if (!currentPosition && currentPosition !== 0) currentPosition = this.getCurrentSelectedPosition();
                    if (currentPosition === 0) return that.numImages - 1;
                    return currentPosition - 1;
                },
                goTo : function(position) {
                    var $current;
                    $('.vf-slideshow-thumb', that.thumbContainer).each(function(i) {
                        if (i == position) {
                            $current = $(this);
                            $(this).addClass('selected');
                        } else {
                            $(this).removeClass('selected');
                        }
                    });

                    var m = that.transition == 'fade' ? 'fadeTo' : 'slideTo';
                    this[m](position);

                    that.captionContainer.text($current.attr('caption') || '');

                    return this;
                },
                slideTo : function(position) {
                    var margin = position * that.mainContainerWidth;
                    that.mainImageContainer.stop().animate({ marginLeft:  - margin + 'px'}, 450);
                    return this;
                },
                fadeTo : function(position) {
                    $('img', that.mainImageContainer).each(function(i) {
                        if (i == position) {
                            $(this).fadeIn('slow');
                        } else {
                            $(this).fadeOut('slow');
                        }
                    });
                }
            };
        },
        gallery: function($div) {
            if (!$div) return null;
            var settings = {
                    gallery : $div,
                    token : $div.attr('token'),
                    identifier : $div.attr('id'),
                    folders_path : $div.attr('folders_path'),
                    hasContextMenu : $div.attr('context_menu') ? true : false,
                    contextMenu : {
                        id : 'vf-gallery-context-menu',
                        props : [
                            { action: 'view', name: 'View Full Image' },
                            { action: 'edit', name: 'Properties' },
                            { action: 'remove', name: 'Delete Image' }
                        ],
                        methods: {
                            view: function(el) {
                                window.open('/ajax/vf2/view/' + this.getID(el));
                            },
                            edit: function(el) {
                                alert('Feature not available yet.');
                            },
                            remove: function(el) {
                                if (!confirm('Are you sure you want to remove this image?')) return;
                                $.post('/ajax/vf2/remove', { items_id : this.getID(el) }, function(json) {
                                    aql.json.handle(json, null, {
                                        success: function() {
                                            vf.gallery(settings.gallery).reload();
                                        },
                                        error: function() {
                                            skybox_alert(this.errorHTML);
                                        }
                                    });
                                });
                            },
                            getID : function(el) {
                                return el.attr('ide');
                            },
                            prPath : function() {
                                window.prompt('Copy to clipboard: Control / Command + C, Enter', settings.folders_path);
                            }
                        }
                    }
                };
            return {
                init : function() {
                    if (!settings.hasContextMenu) return;
                    this.setContextMenu().bindContextMenu();
                },
                reload: function() {
                    var that = this;
                    $.post('/ajax/vf2/gallery', { _token : settings.token }, function(data) {
                        settings.gallery.after(data);
                        settings.gallery.remove();
                        that.init();
                    });
                },
                bindContextMenu: function() {
                    $('.vf-gallery-item', settings.gallery).contextMenu(
                        { menu: settings.contextMenu.id },
                        function(action, el, pos) {
                            if ($('html').hasClass('ie7')) action = action.split('#')[1];
                            if (!!settings.contextMenu.methods[action]) {
                                settings.contextMenu.methods[action](el);
                            }
                        }
                    );
                },
                setContextMenu: function() {
                    if (settings.folders_path) {
                        settings.contextMenu.props.push({
                            action: 'prPath',
                            name: 'Folders Path'
                        });
                    }
                    if ($('#' + settings.contextMenu.id).length) return this;
                    var ul = '<ul id="' + settings.contextMenu.id + '" class="contextMenu">';
                    $.each(settings.contextMenu.props, function(i, item) {
                        ul += '<li class="' + item.action + '"><a href="#' + item.action + '">' + item.name + '</a></li>';
                    });
                    $('body').append(ul);
                    return this;
                }
            };
        },
        uploader : function($div, params) {
            if (!$div) return null;
            var settings = {
                    button : $div,
                    buttonElement : $div.get(0),
                    token : $div.attr('uploader_token'),
                    gallery: $div.attr('refresh_gallery'),
                    container: $div.closest('.vf-uploader-button-container')
                };
            return {
                init : function() {
                    if (this.uploaderSet()) { return; }

                    settings.button_id = (settings.button.attr('id')) ? settings.button.attr('id') : 'vf_uploader_' + settings.token;
                    settings.button.attr('id', settings.button_id);

                    settings.container_id = (settings.container.attr('id')) ? settings.container.attr('id') : 'vf_uploader_container_' + settings.token;
                    settings.container.attr('id', settings.container_id);

                    settings.uploader = new plupload.Uploader({
                        runtimes: 'html5,flash,html4',
                        browse_button: settings.button_id,
                        url : '/ajax/vf2/upload',
                        flash_swf_url : '/lib/plupload/js/plupload.flash.swf',
                        canOpenDialogue : true,
                        container: settings.container_id
                    });
                    this.bindToUploader();
                },
                uploaderSet : function() {
                    for (var i in settings.buttonElement) {
                        if (i.match(/Plupload/)) return true;
                    }
                    return false;
                },
                refreshGallery : function() {
                    var $gallery = $('#' + settings.gallery);
                    if ($gallery.length) {
                        vf.gallery($gallery).reload();
                    }

                },
                isSkybox : function() {
                    var uri = window.location.search.substring(1).split('&');
                    for (var i = 0; i < uri.length; i++) {
                        if (uri[i].split('=')[0] == 'skybox') return true;
                    }
                    return false;
                },
                closeSkybox : function() {
                    var d = this,
                        fn = null;
                    if (d.isSkybox()) {
                        fn = function() {
                            history.back();
                            setTimeout(function() { history.forward(); } , 100);
                        };
                    }
                    $.skyboxHide(fn);
                },
                handleComplete: function() {
                    var that = this,
                        events = settings.button.data('events');
                    if (events && events['upload_complete']) {
                        $.each(events.upload_complete, function(i, e) {
                            sky.call(e.handler, settings.button, that, settings);
                        });
                        settings.button.unbind('upload_complete');
                    }

                },
                bindToUploader : function() {
                    var d = this;

                    settings.uploader.bind('FilesRemoved', function(up, files) {
                        $.each(files, function(i, file) {
                            $('#' + file.id).remove();
                        });
                    });

                    settings.uploader.bind('FileUploaded', function(up, file, info) {
                        var $file = $('#' + file.id + ' .vf-uploader-control');
                        aql.json.handle($.parseJSON(info.response), settings.statusDiv, {
                            success: function() {
                                $file.removeClass('vf-uploader-loading').addClass('vf-uploader-done').parent().animateChange();
                            },
                            error2: function() {
                                d.refreshGallery();
                                up.stop();
                                $('.ui_dialog .close', settings.statusDiv).click(function() { d.closeSkybox(); });
                            }
                        });
                    });

                    settings.uploader.bind('UploadFile', function(up, files) {
                        up.settings.multipart_params = { _token : settings.token };
                    });

                    settings.uploader.bind('UploadProgress', function(up, file) {
                        $('#' + file.id + ' .vf-uploader-control')
                            .removeClass('vf-uploader-cancel')
                            .addClass('vf-uploader-loading');
                    });

                    settings.uploader.bind('UploadComplete', function(up) {
                        aql.success(settings.statusDiv, 'Files Uploaded');
                        d.handleComplete();
                        d.refreshGallery();
                        setTimeout(function() {
                            d.closeSkybox();

                        }, 1000);
                    });

                    settings.uploader.init();

                    settings.uploader.bind('FilesAdded', function(up, files) {
                        var id = 'status_' + settings.button_id,
                            do_upload = false,
                            display = '<div class="vf-uploader-status-skybox">',
                            no_filename = false;
                        display += '<div id="' + id + '" class="vf-uploader-status">Uploading...</div>';
                        $.each(files, function(i, file) {
                            if (!file.name) { no_filename = true; return; }
                            display += '<div id="' + file.id + '" class="vf-uploader-upload-file has-floats"><a href="#" class="vf-uploader-control vf-uploader-remove"></a>' + file.name + '</div>';
                            do_upload = true;
                        });
                        display += '</div>';
                        if (no_filename) {
                            skybox_alert('<p>The Uploader is having difficulties with your browser.</p><p>If you can try a different browser, please do so.</p>');
                        } else {
                            $.skyboxShow(display);
                            settings.statusDiv = $('#' + id);
                            if (do_upload) settings.uploader.start();
                        }

                    });

                    $('.vf-uploader-remove').die().live('click', function() {
                        var id = $(this).closest('.vf-uploader-upload-file').attr('id');
                        settings.uploader.removeFile(settings.uploader.getFile(id));
                        return false;
                    });
                }
            };
        }
    };
})();

(function($) {

    $.fn.vfSlideshow = function(params) {
        return this.each(function() {
            var slide = vf.slideshow($(this), params);
            slide.init();
        });
    };

    $.fn.vfUploader = function(params) {
        return this.each(function() {
            var up = vf.uploader($(this), params);
            up.init();
        });
    };

    $.fn.vfGallery = function(params) {
        this.each(function() {
            var gallery = vf.gallery($(this), params);
            gallery.init();
        });
    };

}) (jQuery);

$(function() {

    $('.vf-slideshow').livequery(function() {
        $(this).vfSlideshow();
    });

    $('.vf-uploader').livequery(function() {
        $(this).vfUploader();
    });

    $('.vf-gallery').livequery(function() {
        $(this).vfGallery();
    });

});
