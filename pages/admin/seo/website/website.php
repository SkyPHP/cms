<?
	$title = "SEO BY PAGE - ".$_SERVER['SERVER_NAME'];
	template::inc('intranet','top');
	$my = get_files();
?>
<br />
<div style="margin-top:10x; padding:20px; background:#eeeee2; border:1px solid #ccc; width: 30%; float:left;">
<?	
	foreach($my['dirs'] as $dir) {
		$path = "pages/".$dir;
		$dir = str_replace("/","",$dir);
		if ($dir != 'admin' && $dir != 'cron' && $dir != 'svn') {
			$id = str_replace('.','__',str_replace('/','',str_replace('_','',$path)));
	?>
			<div style="margin-bottom:4px;">
				<span class="directory" status="closed" dirid="<?=$id?>" path="<?=$path?>">
					<span id="images_<?=$id?>">
						<img src="/images/plus.png" width="9" height="9" />...<img src="/images/closed-folder.png" width="16" height="13" />
					</span> 
					<?=$dir?>
				</span>
			</div>
			<div id="<?=$id?>"></div>
<?
      	}
 
	}
	foreach($my['files'] as $file) {
		$path = "pages/".$file;
		$id = str_replace('.','__',str_replace('/','',str_replace('_','',$path)));
?>
	<form id="<?=$id?>_form" action="/admin/seo/website/page" method="post" target="seo_page">
    	<input type="hidden" name="page_path" value="<?=$path?>" />
		<div style="margin: 0 0 4px 18px;">
    		<span class="file" formid="<?=$id?>" file="<?=$file?>"><img src="/images/file.png" width="16" height="16" />  <?=$file?></span>
        </div>
    </form>
<?
	}
	
?>
</div>
<div style="float:left; margin-left:20px; width:60%; height:1200px;">
	<iframe width="100%" height="100%" src="/admin/seo/website/page" name="seo_page"></iframe>
</div>
<div class="clear"></div>
<?
	template::inc('intranet','bottom');
?>