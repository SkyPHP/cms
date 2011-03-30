<?
$title = 'Codebase Editor';
template::inc('skybox','top');
$selected_codebase = $_GET['codebase'];
$table = $_GET['table'];

$codebase_list = get_codebases();
$codebase_arr = array();

$aql = "dev_codebase	{
							name
						}";
$codebase_arr = array();
$rs = aql::select( $aql );
foreach ($rs as $r){
	$codebase_arr[$r['name']]=$r['name'];
}

foreach($codebase_list as $codebase){
	$codebase_arr[$codebase['codebase']] = $codebase['codebase'];
}
$param = array	(
					'id'=>'codebase_name',
					'name'=>'name',
					'selected_value'=>$selected_codebase
				);
snippet::dropdown($codebase_arr,$param);
?>
<br><br>
<div>
	<input type="hidden" id = "table_name" value = "<?=$table ?>" />
	<input type="submit" value="Save" onclick = "save_codebase()" />
</div>
<?
template::inc('skybox','bottom');
?>