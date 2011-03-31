<?

$SQL = NULL;
$fn = $_POST['sky_qs'][0];
$table = $_POST['sky_qs'][1];

$SQL = "select pg_tables.tablename, repl_relations.enable as replication, obj_description(pg_class.oid, 'pg_class') as description
		from pg_tables
		left join repl_relations on repl_relations.namespace = pg_tables.schemaname and repl_relations.relation = pg_tables.tablename
		left join pg_class on pg_class.relname = pg_tables.tablename
		where pg_tables.tablename = '$table'
        and pg_tables.schemaname = 'public'";
$SQL = "select pg_tables.tablename, slony_table.replicated as replication, obj_description(pg_class.oid, 'pg_class') as description
                from pg_tables
                left join pg_class on pg_class.relname = pg_tables.tablename
                left join slony_cluster on slony_cluster.name = '$slony_cluster_name' and slony_cluster.active=1
                left join slony_set on slony_cluster.slony_set_id = slony_set.id and slony_set.active=1
                left join slony_table on pg_class.relname = slony_table.name and slony_set.id = slony_table.slony_set_id and slony_table.active=1
                where pg_tables.tablename = '$table'
        and pg_tables.schemaname = 'public'";

$r = $dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());
if ( $r->Fields('replication') ) $rep_on = true;
else $rep_on = false;
$jarr = json_decode( $r->Fields('description'), true );
$codebase_name = $jarr['codebase'];


$title = 'SQL Editor';
template::inc('skybox','top');

?>

<div style="margin-bottom: 5px; font-size: 12px;">
<?
    $SQL = NULL;
	@include('presets/' . $fn . '.php');
	if ( is_array($presets) )
	foreach ( $presets as $preset => $sql ):
		if ( $not_first ) echo ' | ';
		else $SQL = $sql;
		$not_first = true;
?>
		<a href="javascript:void(null);" onclick="$('#sql_editor').html('<?=$sql?>');"><?=$preset?></a>
<?
	endforeach;
?>
</div>

<textarea id="sql_editor"><?=str_replace('\\n',"\n",$SQL)?></textarea>
<input type = "hidden" value = "<?=$fn ?>" name = "fn" id = "fn" />
<div style="margin: 15px 0 15px 0;">
<?
if($fn=='create_table'){
$list = get_codebases();
$codebase_arr = array();
foreach($list as $codebase){
	if($codebase['official_db']){
		$codebase_arr[$codebase['codebase']] = $codebase['codebase'];
	}
}
$param = array	(
					'id'=>'codebase_name',
					'name'=>'name',
					'onchange'=>'enter_codebase();',
					'selected_value'=>$codebase
				);
snippet::dropdown($codebase_arr,$param);
}
?>
	<input type="button" value="Execute" onclick="if (confirm('Are you sure?')) execute_sql('<?=$table?>');" />
	<div id="execute"></div>
</div>

<?
template::inc('skybox','bottom');
?>
