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
		   	'imageWidth' : '', //
			'imageHeight': '', //
			'images' : '', //
			'thumbWidth' : 100, //
			'thumbHeight' : 65, //
			'numThumbs' : 8,
			'autoscroll' : true,
			'changeEvery' : 10
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
			console.log(settings);
			$.ajax({
				type: 'POST', 
				url: '/ajax/media/gallery', 
				data: settings, 
				success:function(data) {
					$this.html(data);
					var current=1;
					var positions = new Array();
					
					if (settings.autoscroll) {
						var autoAdvance = function () {
							if(current==-1) return false;
							var l = $('.gallery-menu ul li a',$this).length;
							$('.gallery-menu ul li a',$this).eq(current%l).trigger('click',true);	// [true] will be passed as the keepScroll parameter of the click function on line 28
							current++;
						}		
						var itvl = setInterval(autoAdvance,settings.changeEvery*1000);
					}
					
					$('.slide',$this).each(function(i) {
						/* Traverse through all the slides and store their accumulative widths in totWidth */
					
						positions[i]= totWidth;
						totWidth += $(this).width();
					
							/* The positions array contains each slide's commulutative offset from the left part of the container */
							
						if(!$(this).width())
						{
							alert("Please, fill in width & height for all your images!");
							return false;
						}
					});
					
					$this.width(totWidth);
				
					/* Change the cotnainer div's width to the exact width of all the slides combined */
				
					$('.gallery-menu ul li a',$this).live('click', function(e,keepScroll){
						var $a = $(this);
						var $li = $a.parent();
						/* Prevent the default action of the link */
						e.preventDefault();
						/* On a thumbnail click */
			
						$('.gallery-menu li.menuItem',$this).removeClass('act').addClass('inact');
						$li.addClass('act');
						
						var pos = $li.prevAll('.menuItem').length;
						
						$this.stop().animate({marginLeft:-positions[pos]+'px'},450);
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