<?

//  gallery

global $dev, $is_dev;

$show_vf = ($dev || $is_dev);

try {

	if (!$gallery) {

		if (!$_POST['_token']) {
			throw new Exception('AJAX request for a vFolder gallery requires a token');
		}

		$key = sprintf('vf_gallery:%s', $_POST['_token']);
		$get_params = function() use($key) {
			return mem($key);
		};

		// try to fetch mem_key 3 times
		for ($i = 0; $i < 3; $i++) {
			$params = $get_params();
			if ($params) break;
		}
		
		if (!$params) {
			$error = sprintf('Invalid gallery token: <strong>%s</strong>. Could not get params to generate gallery.', $_POST['_token']);
			throw new Exception($error);
		}

		$gallery = vf::gallery($params);
		$folder = $gallery->initFolder(true);
		$items = $folder->items;

	} else {
		$items = $gallery->folder->items;
		if (!$items) $items = $gallery->items;
	}

	if ($gallery->db_field && $gallery->db_row_id) {
		$items = array(
			array('_id' => aql::value($gallery->db_field, $gallery->db_row_id))
		);
		if (!$items[0]['_id']) $items = array();
	}
	$empty = (count($items) == 0);
?>
	<div class="vf-gallery has-floats <?=($empty)?'vf-gallery-empty':''?>" id="<?=$gallery->identifier?>" 
		token="<?=$gallery->_token?>"
		<?=($show_vf) ? 'folders_path="'.$gallery->folder->folders_path.'"' : '' ?>
		<?=($gallery->contextMenu) ? 'context_menu="true"' : ''?>
		>
<?
		if ($empty) {
?>
			<div class="vf-gallery-empty-message">
				<?=$gallery->empty_message?>
			</div>
<?			
		} else {
		
                        #getItem will strip the data we have already if the item is not an image, we need to preserve non-image items' data	
                        $_items = $items;

			$items = vf::getItem(vf_gallery_inc::itemsToFlatArray($items), array(
				'width' => $gallery->width,
				'height' => $gallery->height,
				'crop' => $gallery->crop	
			));

                        global $sky_content_type;
                        foreach($_items as $index => $_i){
                           #if we have items that are not images, we need to restore the data to our items array and generate an appropriate html representation (vfolder server file icons)
                           if($_i['media_type'] != 'image'){
                              $_item_html = '<img width="' . $gallery->width . '" height="' . $gallery->height . 
                                 '" src="http://' . (vf::$client->func_boilerplate['all']['files_domain']?:'static.cravetickets.com') . 
                                 '/images/file-icons/' . (array_search($_i['media_type'] . '/' . $_i['media_subtype'], $sky_content_type)?:'file') . '.jpg' . '" />';
                              $items->items[$index] = (object)array(
                                 '_id' => $_i['_id'],
                                 'items_id' => $_i['_id'],
                                 'html' => $_item_html
                              );
                           }
                        }

			$items = call_user_func(function() use($items) {
				if ($items->items) return $items->items;
				return array($items);
			});

			foreach ($items as $i) {
?>
				<div class="vf-gallery-item" ide="<?=$i->items_id?>">
					<?=$i->html?>
				</div>
<?				
			} 
		} // end if there are items
?>
	</div>
<?
} catch (Exception $e) {

	echo $e->getMessage();

}
