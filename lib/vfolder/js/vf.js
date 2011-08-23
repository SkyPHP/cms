$(document).ready(function() {
	
	$('.vf-slideshow').vfSlideshow();

});

$.fn.vfSlideshow = function(params) {
	return this.each(function() {
		var slide = vf.slideshow($(this));
		slide.init();
	});
};

var vf = {
	slideshow: function($div, params) {
		if (!$div) return;
		var that = {
			container : $div,
			mainContainer : $('.vf-slideshow-main', $div),
			mainContainerWidth: 0,
			mainImageContainer : $('.vf-slideshow-image', $div),
			mainImageContainerWidth : 0,
			numImages : 0,
			thumbContainer : $('.vf-slideshow-thumbs', $div),
			autostart : $div.attr('autostart') ? true : false,
			transition : $div.attr('transition'),
			activeClass : 'selected',
			delay : $div.attr('delay')
		};
		return {
			setWidth : function() {
				var w = 0, num = 0;
				$('img', that.mainContainer).each(function(i) {
					w += $(this).width();
					if (i == 0) {
						that.mainContainer.width(w);
						that.mainContainerWidth = w;
						that.mainContainer.height($(this).height());
					}
					num++;
				});
				that.mainImageContainerWidth = w;
				that.mainImageContainer.width(w);
				that.numImages = num;
				return this;
			},
			init : function() {
				this.setWidth().bindClicks();
				if (that.autostart) this.start();
			},
			bindClicks : function() {
				var ob = this;
				$('.vf-slideshow-thumb', that.thumbContainer).live('click', function() {
					var $this = $(this),
						index = $('.vf-slideshow-thumb', that.ThumbContainer).index($this);
					ob.stop().goTo(index);
				});
			},
			start: function() {
				var ob  = this;
				that.interval = setInterval(function() {
					ob.goTo(ob.getNextSelectedPosition());
				}, that.delay);
			},
			stop: function() {
				clearInterval(that.interval);
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
			goTo : function(position) {
				switch (that.transition) {
					case 'fade' : 	this.fadeTo(position); 	break;
					default : 		this.slideTo(position);	break;
				}
				return this;
			},
			slideTo : function(position) {
				$('.vf-slideshow-thumb', that.thumbContainer).each(function(i) {
					if (i == position) $(this).addClass('selected');
					else $(this).removeClass('selected');
				});
				var margin = position * that.mainContainerWidth;
				that.mainImageContainer.stop().animate({ marginLeft:  - margin + 'px'}, 450);
				return this;
			},
			fadeTo : function(position) {
				
			}
		};		
	},
	gallery: function() {
		
	}
};