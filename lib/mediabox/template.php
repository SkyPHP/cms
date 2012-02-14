<?
$large_conf = array(
	'width' => $this->width - $this->thumb_width,
	'height' => $this->height,
	'crop' => 'center'
);

$thumb_height = $this->height-2-(2*$this->num_thumbs);

?>
<script type="text/javascript">

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
	
</script>

<style type="text/css">
html {
	background:pink;
}
	.mediabox {
		background:#000;
		overflow:hidden;
	}
		.big_image {
			float:left;
			position:relative;
		}
			.big_image img {
				float:left;
				position:absolute;
				top:0px;
				left:0px;
				z-index:1;
			}
				.big_image img.selected {
					z-index:2;
				}
			.big_image .caption {
				position:absolute;
				z-index:40;
				bottom:0px;
				left:0px;
				right:0px;
				color:#fff;
				padding:10px 20px;	
				/* Fallback for web browsers that don't support RGBa */
				background-color: rgb(0, 0, 0);
				/* RGBa with 0.6 opacity */
				background-color: rgba(0, 0, 0, 0.6);
				/* For IE 5.5 - 7*/
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
				/* For IE 8*/
				-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";				
			}
				
				.caption .tag {
					position:absolute;
					top:-10px;
					left:20px;
					background-color:#A2252B;
					padding:2px 9px;
					text-transform:uppercase;
					font-weight:bold;
				}
				.caption .blog_title {
					font-size:28px;
					line-height:22px;
					margin-top:5px;
				}
				.caption .blog_subtitle {
					font-size:14px;
				}
					.caption > div a {
						color:#fff;
						text-decoration:none;
					}
					.caption > div a:hover {
						text-decoration:underline;
					}
				
		.thumbnails {
			float:right;
			margin:2px;
			margin-bottom:0px;
		}
			.thumbnails img {
				margin-bottom:2px;
				float:left;
			}
			
</style>

<div class="mediabox" style="height:<?=$this->height?>px;width:<?=$this->width?>px;">
    
    <div class="big_image" style="width:<?=$this->width-$this->thumb_width?>px;height:<?=$this->height?>px;">
    <?
	foreach ($this->data as $key => $img) {
		$big_img = vf::getItem($img['media_item_id'],$large_conf);
		?>
		<img src="<?=$big_img->src?>" class="img_<?=$key?> <?=$key==0?'selected':''?>" num="<?=$key?>"  />
        <?
    }
	?>
    	<div class="caption">
        	<? if($this->data[0]['tag']) { ?><div class="tag"><?=$this->data[0]['tag']?></div><? } ?>
            <div class="blog_title"><a href="<?=$this->data[0]['href']?>"><?=$this->data[0]['title']?></a></div>
            <div class="blog_subtitle"><a href="<?=$this->data[0]['href']?>"><?=$this->data[0]['subtitle']?></a></div>
        </div>
    </div>
    <div class="thumbnails">
    <?
	foreach ($this->data as $key => $img) {
		$temp_height = ceil($thumb_height/($this->num_thumbs-$key));
		$thumb_height = $thumb_height-$temp_height;
		$small_conf = array('height' => $temp_height,
							'width' => $this->thumb_width - 4,
							'crop' =>  'center' );
		$small_img = vf::getItem($img['media_item_id'],$small_conf);
		echo '<div><img src="'.$small_img->src.'" class="thumb thumb_'.$key.'" num="'.$key.'" /></div>';
	}
	?>
    </div>

	<div class="captions" style="display:none">
		<?
		foreach($this->data as $key => $img_data) {
		?>
        <div class="caption_<?=$key?>">
        	<? if($img_data['tag']) { ?><div class="tag"><?=$img_data['tag']?></div><? } ?>
            <div class="blog_title"><a href="<?=$img_data['href']?>"><?=$img_data['title']?></a></div>
            <div class="blog_subtitle"><a href="<?=$img_data['href']?>"><?=$img_data['subtitle']?></a></div>
        </div>
        <?
		}
		?>	
    </div>
</div>
<? krumo($this);