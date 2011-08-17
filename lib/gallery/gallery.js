$(function() {
	$('gallery').livequery(function() {
		$(this).gallery();
	});
});

(function($) {
// GALLERY
	$.fn.gallery = function (params) {
		
		var settings = {
           	'vfolder' : '',//
		   	'image_width' : '', //
			'image_height': '', //
			'images' : '', //
			'thumb_width' : 90, //
			'thumb_height' : 65, //
			'num_thumbs' : 8,
			'center_thumbs' : false,
			'autoscroll' : false,
			'change_every' : 10
        };
		
		return this.each(function() {
			var $this = $(this),
				totWidth=0,
				opts = {},
            	attrs = $this[0].attributes,
				i = attrs.length - 1;
	
			while (i >= 0) {
				var name = attrs[i].nodeName,  val = attrs[i].nodeValue;
				opts[name] = val;
				i--;
			}
			
			$.extend(settings, opts);
			$.extend(settings, params);

			$.ajax({
				type: 'POST', 
				url: '/ajax/media/gallery', 
				data: settings, 
				success:function(data) {
					$this.html(data);
					var current = 1,
						positions = [],
						autoAdvance = function() {
							if (current == -1) return false;
							var $l = $('.gallery-menu ul li a', $this),	
								num = $l.length;
							$l.eq(current % num).trigger('click', true);
							current++;
						},
						itvl;
					
					if (settings.autoscroll && settings.change_every) {
						itvl = setInterval(autoAdvance, settings.change_every * 1000);
					}
					
					$('.slide',$this).each(function(i) {
						/* Traverse through all the slides and store their accumulative widths in totWidth */
					
						positions[i]= totWidth;
						totWidth += $(this).width();
					
						/* The positions array contains each slide's commulutative offset from the left part of the container */
							
						if(!$(this).width()) {
							alert("Please, fill in width & height for all your images!");
							return false;
						}
					});
					
					$('.slides', $this).width(totWidth);
				
					/* Change the cotnainer div's width to the exact width of all the slides combined */
				
					$('.gallery-menu ul li a',$this).die().live('click', function(e,keepScroll){
						e.preventDefault();
						var $a = $(this),
							$li = $a.parent(),
							pos = $li.prevAll('.menuItem').length;
						/* Prevent the default action of the link */
						
						/* On a thumbnail click */
			
						$('.gallery-menu li.menuItem',$this).removeClass('act').addClass('inact');
						$li.addClass('act');	
						$('.slides', $this).stop().animate({marginLeft:-positions[pos]+'px'},450);
						/* Start the sliding animation */
				
						// Stopping the auto-advance if an icon has been clicked:
						if (settings.autoscroll) if(!keepScroll) clearInterval(itvl);
			
					});
					
					/* On page load, mark the first thumbnail as active */
					$('.gallery-menu ul li.menuItem:first',$this).addClass('act').siblings().addClass('inact');
	
				}
			});
		});
	};
}) (jQuery);