  <div class="gallery">  
	
    <div class="slides">
<?  
	$vfolder = media::get_vfolder($_POST['vfolder']);
	if ($vfolder['items']) foreach ($vfolder['items'] as $item) {
		$img = media::get_item($item['media_item_id'], $_POST['imageWidth'],$_POST['imageHeight'],true);
?>
		<div class="slide"><?=$img['html']?></div>
<?		
	}
	else exit('No Images');
?>
 
    </div>
    
    <div class="gallery-menu">
    
    <ul>
        <li class="fbar">&nbsp;</li>
<?
	foreach($vfolder['items'] as $item) {
		$img = media::get_item($item['media_item_id'],$_POST['thumbWidth'],$_POST['thumbHeight'],true);
?>	
		<li class="menuItem"><a href=""><?=$img['html']?></a></li>
<?
	}
?>
    </ul>
	
    </div>
    
  </div>
    
</div>