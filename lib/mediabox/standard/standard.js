$.fn.mediabox = function() {
	return this.each(function() {
		var $this = $(this),
			t_out = null;
		
		$(".thumb",$this).click(function() {
			t_out = newInterval(t_out);
			var parent = $this;	
			var tmp_next = parent.find('.img_'+$(this).attr('num'));
			// make sure user didn't click on the current image and the the animation isn't in progress 
			if(!tmp_next.hasClass('selected') && !parent.hasClass('working') ) {
				var num = $(this).attr('num');
				var next = parent.find('.img_'+num);
				var prev = parent.find('.selected');

				// reset some of the variables. problems happen otherwise if the user clicks too fast.
				parent.addClass('working');
				next.css('z-index',20);
				prev.css('z-index',19);
				next.css('top',parent.height()+'px');
				
				// animate the previous image away
				prev.animate({
					top: '-='+parent.height()
				}, 1000, function() {
					prev.css('z-index',1)
				});
				//move the next image to the bottom and animate it up
				next.css('top',parent.height()+'px');
				next.animate({
					top: '-='+parent.height()
				}, 1000, function() {
					// after finished, remove selected from others, add selected to next and let the container know the animation is complete
					parent.find('.big_image img').removeClass('selected');
					next.addClass('selected');
					parent.removeClass('working');
					parent.find('.caption').html(parent.find('.caption_'+num).html());					
				});
			}
		});
		
		function newInterval(itval) {
			if (itval) clearInterval(itval);
			return setInterval(function() {
				var current = $('.selected', $this);
				var current_number = current.attr('num');
				var number = current.siblings().length;
				var next_img = null;
				
				if (current_number == number-1) {
					next_img = 0;
				} else {
					next_img = parseInt(current_number)+1;
				}
				$this.find('.thumb_'+next_img).click();
			}, 5000);
		}		
		t_out = newInterval();	
		});
};

$(document).ready(function() {
	$('.mediabox').livequery(function() {
		$(this).mediabox();
	});
});