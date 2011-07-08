<?
	$websites = aql::select("website_group { website_id } website { name as website_name where website_group.name = '{$_POST['website_group_name']}' order by website.name asc }");
	if (is_array($websites)) {
		foreach ($websites as $website) {
?>
			<div class="website"><?=$website['website_name']?></div>
<?			
			$pages = aql::select("website_page { nickname, page_path where website_id = {$website['website_id']} order by nickname asc } ");
		
			if (is_array($pages)) {
				foreach($pages as $page) {
?>
					<a class="edit_page" title="<?=$page['page_path']?>" wg="<?=$_POST['website_group_name']?>" page_ide="<?=$page['website_page_ide']?>">
    	            	<div class="nickname"><?=$page['nickname']?></div>
						<div class="page_path"><?=$page['page_path']?></div>
                        <div class="clear"></div>
            	    </a>
                    <hr>
<?					
				}
			}
?>
<?
		}
	}
?>