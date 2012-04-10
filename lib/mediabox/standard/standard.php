<?

// krumo($this->data);
// return;

$large_conf = array(
	'width' => $this->width - $this->thumb_width,
	'height' => $this->height,
	'crop' => 'center'
);

$sidebar_height = $this->height-2-(2*$this->num_thumbs);

if($this->data){
?>
<div class="mediabox" style="height:<?=$this->height?>px;width:<?=$this->width?>px;">
    <div class="big_image" style="width:<?=$large_conf['width']?>px;height:<?=$large_conf['height']?>px;">
<?
	foreach ($this->data as $key => $img) {
		$big_img = vf::getItem($img['media_item_id'], $large_conf);
?>
		<img src="<?=$big_img->src?>" 
            class="img_<?=$key?> <?=$key==0?'selected':''?>" 
            num="<?=$key?>"  />
<?
    } // end foreach large image?>
    	<div class="caption">
<?
        if ($this->data[0]['tag']) {
?>
            <div class="tag">
                <?=$this->data[0]['tag']?>
            </div>
<?        
        } // end if tab
?>
            <div class="media_title">
                <a href="<?=$this->data[0]['href']?>"><?=$this->data[0]['title']?></a>
            </div>
            <div class="media_subtitle">
                <a href="<?=$this->data[0]['href']?>"><?=$this->data[0]['subtitle']?></a>
            </div>
        </div>
    </div>
    <div class="thumbnails">
<?

	foreach ($this->data as $key => $img) {

        // make image heights fill the full height of the mediabox (so no empty spaces)
		$temp_height = ceil( $sidebar_height / ( $this->num_thumbs - $key ) );
		$sidebar_height = $sidebar_height - $temp_height;
		
        $small_conf = array(
            'height' => $temp_height,
			'width'  => $this->thumb_width - 4,
			'crop'   => 'center' 
        );

        // empty thumbnails need to be there, or images dont cycle properly
        // set to to lookalike object if no thumb width, or things will stop working
        $small_img = (!$this->thumb_width || $this->thumb_width <= 4) 
            ? (object) array('src' => '')
            : vf::getItem($img['media_item_id'], $small_conf);

?>		
		<div>
            <img src="<?=$small_img->src?>" class="thumb thumb_<?=$key?>" num="<?=$key?>" />
        </div>
<?        
	}
?>
    </div>
	<div class="captions" style="display:none">
<?
    foreach($this->data as $key => $img_data) { 
?>
        <div class="caption_<?=$key?>">
<?
        if ($img_data['tag']) {
?>
            <div class="tag">
                <?=$img_data['tag']?>
            </div>
<?            
        } 
?>        
            <div class="media_title">
                <a href="<?=$img_data['href']?>"><?=$img_data['title']?></a>
            </div>
            <div class="media_subtitle">
                <a href="<?=$img_data['href']?>"><?=$img_data['subtitle']?></a>
            </div>
        </div>
<? 
    } 
?>  
    </div>
	<div class="interval"><?=$this->interval?></div>
</div>
<?
}