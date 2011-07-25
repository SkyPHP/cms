<div style="margin-left:20px;">
<?
	$my = array();
	$path = $_POST['path'];
	$my = get_files($path);
	if (is_array($my['dirs'])) {
		foreach($my['dirs'] as $dir) {
		$path = $_POST['path'].$dir;
		$dir = str_replace("/","",$dir);
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
	if (is_array($my['files'])) {
		foreach($my['files'] as $file) {
			$path = $_POST['path'].$file;
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
	}
if (!is_array($my['files']) && !is_array($my['dirs'])) echo "Nothing Here"
?>
</span>