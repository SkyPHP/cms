<div class="gallery" style="width:<?=$_POST['image_width']?>px; overflow:hidden;">	
	<div class="slides" style=" height:<?=$_POST['image_height']?>px; overflow:hidden;">
<?  
		$vfolder = media::get_vfolder($_POST['vfolder']);
	if ($vfolder['items']) foreach ($vfolder['items'] as $item) {
		$img = media::get_item($item['media_item_id'],$_POST['image_width'],$_POST['image_height'],true);
?>
		<div class="slide"><img src="<?=$img['src']?>" width="<?=$_POST['image_width']?>" height="<?=$_POST['image_height']?>" /></div>
<?		
	}
	else exit('No Images');
?>
	   	</div>    
	<div class="gallery-menu">    
			<ul>
    		<li class="fbar">&nbsp;</li>
<?
			$conuter = 0;
			if ($_POST['num_thumbs']) $stop = $_POST['num_thumbs'];
			else $stop = count($vfolder['items']);
			foreach($vfolder['items'] as $item) {
				$counter++;
				$img = media::get_item($item['media_item_id'],$_POST['thumb_width'],$_POST['thumb_height'],true);
?>		
				<li class="menuItem <? if ($counter==1) echo 'first'; else if ($counter = $stop) echo 'last'?>"><a href=""><?=$img['html']?></a></li>
<?
				if ($_POST['num_thumbs'] == $counter) break;
			}
?>
		</ul>	
	</div>    
</div>