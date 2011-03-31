<?

$title = 'Database Schema Version Control';
template::inc('global','top');

//find backup files
$backup_directory = '/backup';
$backup_folder_path = '';
$files = @scandir($backup_directory);
if($files){
	foreach($files as $file){
		$file_path = $backup_directory.'/'.$file;
		if(is_dir($file_path) && strpos($file,'.')!==0){
			$backup_folder_path = $file_path;
			break;
		}
	}
}
// get array of codebases
$codebase_array = get_codebases();

template::breadcrumb();
include(INCPATH.'/../dev-nav.php');

?>

<h1><?=$title?></h1>

<?
   if(slony::sniff_cluster()){
      echo "A Slony cluster is defined but \$slony_cluster_name is not!<br/><br/>";
      echo "Using this interface has the potential to seriously disrupt replication, so we're stopping you now...";
      die();
   }
?>

<table class="listing">
	<tr>
		<th>codebase</th>
		<th>version</th>
		<th>db</th>
		<th>svn</th>
		<th>svn user</th>
		<th></th>
	</tr>
<?
	foreach ( $codebase_array as $codebase ):
		$aql = "dev_codebase {
					svn_url,
					svn_username,
					svn_password
					where name = '{$codebase['codebase']}'
				}";
		$rs = aql::select($aql);
		$codebase_array[$codebase['codebase']]['svn_url'] = $rs[0]['svn_url'];
		$codebase_array[$codebase['codebase']]['svn_username'] = $rs[0]['svn_username'];
		$codebase_array[$codebase['codebase']]['svn_password'] = $rs[0]['svn_password'];
?>	
	<tr class = "<?=$codebase['official_db']?'official_db':'unofficial_db' ?>">
		<td class = "codebase"><?=$codebase['codebase']?></td>
		<td><?=$codebase['version']?></td>
		<td>
<?
			echo $codebase['official_db'] ? '<img src="/images/success.gif" /> ':'<img src="/images/error.gif" /> ';
			echo $codebase['db'];
?>
		</td>
		<td><?=$rs[0]['svn_url']?></td>
		<td><?=$rs[0]['svn_username']?></td>
		<td>
<?
			if ( $codebase['db'] != $db_name ):
?>
			<input type="button" value="Check For Updates" />
<?
			endif;
?>
		</td>
	</tr>
<?
	endforeach;
?>
</table>

<div id="function_bar">
	<input type="button" value="Create Table" onclick="sql_editor('my_table','create_table')" />
	<input type="button" value="SQL Editor" onclick="alert('SQL Editor does not log changes!!! \n\nAfter using this, you must manually add your SQL to \n/pages/dev/sql/changelog/changelog.sql \nin the appropriate codebase. \n\nOr, click edit next to a table in the correct codebase below.\n\n(TODO: ability to select a codebase in SQL Editor)'); sql_editor();" />
	<span class="find">Find: <input id = "find" type="text" value="" onKeyUp="find_table()" /></span>
	<img src="/images/loading2.gif" height="17" id = "find_loading" /><br />
        <? if(slony::cluster_defined()){ ?>
        <input type='button' value='Save Replication Changes' onclick='confirm("Are you sure you want to toggle these replication changes?")?toggle_replication():null;' /><br />
        <a href='/dev/slony'>Go to Slony Cluster Admin</a><br />
        <a href='/dev/slony/daemon_manager'>Go to Slony Daemon Manager</a>
        <br /><br />
        <a href='/dev/db?check_schema=1'>Check for Schema Inconsistencies</a>
        <? } ?>
</div>

<div id='tableadmin_message'></div>

<table class="listing">
	<tr>
		<th>Table</th>
		<th style="width:125px; padding-right:50px;">Codebase</th>
		<th>Column</th>
		<th>DDL</th>
		<th>Index</th>
		<th <?=slony::cluster_defined()?'':'class="hide"' ?> >Replication<br />Status</th>
		<th <?=slony::cluster_defined()?'':'class="hide"' ?> >Replication<br />Switch</th>
		<th style="width: 100px;">Backup</th>
	</tr>
<?
/*$SQL = "select pg_tables.tablename, repl_relations.enable as replication, obj_description(pg_class.oid, 'pg_class') as description
		from pg_tables
		left join repl_relations on repl_relations.namespace = pg_tables.schemaname and repl_relations.relation = pg_tables.tablename
		left join pg_class on pg_class.relname = pg_tables.tablename
		where pg_tables.schemaname = 'public'
		order by pg_tables.tablename asc"; */
$SQL = "select pg_tables.tablename, _$slony_cluster_name.sl_table.tab_relname as replication, obj_description(pg_class.oid, 'pg_class') as description,  pg_class.relhaspkey as haspkey, seq.relname as sequence
                from pg_tables
                left join pg_class on pg_class.relname = pg_tables.tablename
                left join _$slony_cluster_name.sl_table on pg_class.relname = _$slony_cluster_name.sl_table.tab_relname
                left join pg_class as seq on seq.relname = (pg_tables.tablename||'_id_seq')
 /*
                left join slony_cluster on slony_cluster.name = '$slony_cluster_name' and slony_cluster.active=1
                left join slony_set on slony_cluster.slony_set_id = slony_set.id and slony_set.active=1
                left join slony_table on pg_class.relname = slony_table.name and slony_set.id = slony_table.slony_set_id and slony_table.active=1 */
                where pg_tables.schemaname = 'public'
                order by pg_tables.tablename asc";
if(!$slony_cluster_name){
   $SQL = "select pg_tables.tablename, 'f' as replication, obj_description(pg_class.oid, 'pg_class') as description
                from pg_tables
                left join pg_class on pg_class.relname = pg_tables.tablename
                where pg_tables.schemaname = 'public'
                order by pg_tables.tablename asc";

}

$r = $dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());
while (!$r->EOF):
        unset($conflict);
        if(slony::cluster_defined()){$conflict =  $_GET['check_schema']&&!$_GET['only']?slony::check_table_definition($r->Fields('tablename')):($_GET['only']== $r->Fields('tablename')?slony::check_table_definition($r->Fields('tablename')):false) ;}

	if ( $r->Fields('replication') ) $rep_on = true;
	else $rep_on = false;

	$jarr = json_decode( $r->Fields('description'), true );
	$codebase_name = $jarr['codebase'];
?>
	<tr id = "<?=$r->Fields('tablename')?>_row" name = "<?=$r->Fields('tablename')?>" class = 'table_row'>
		<td class = "tablename"><?=$r->Fields('tablename')?></td>
		<td style="width:125px; padding-right:50px;">
			<b class = "codebase"><?=$codebase_name?></b>
			<div class="float-right" style="font-size:10px;">[<a onclick = "codebase_editor(this)" href="javascript:void(null);">change</a>]</div>
		</td>
		<td>
<?
		if ( (!$codebase_name || $codebase_array[$codebase_name]['db'] == $db_name) && !$conflict ):
?>
			<input type="button" value="Add" onclick="sql_editor('<?=$r->Fields('tablename')?>','add_column')" />
			<input type="button" value="Edit" onclick="sql_editor('<?=$r->Fields('tablename')?>','alter_column')" />
<?
		endif;
?>

		</td>
		<td>
<?
			$ddl_file = $backup_folder_path.'/'.$r->Fields('tablename').'.schema.sql';
			if(file_exists($ddl_file) && !$conflict){
?>
				<input type="button" value="Edit" onclick="sql_editor('<?=$r->Fields('tablename')?>','ddl')" />
<?
			}
?>
		</td>
		<td>
<?
		if ( (!$codebase_name || $codebase_array[$codebase_name]['db'] == $db_name) && !$conflict ):
?>
			<input type="button" value="Index" onclick="sql_editor('<?=$r->Fields('tablename')?>','index')" />
<?
		endif;
?>
		</td>
		<td id="onoff_<?=$r->Fields('tablename')?>" class="<? echo $rep_on?'rep_on':'rep_off'; ?> <?=slony::cluster_defined()?'':'hide' ?>">
			<? 

                    if($conflict){
                       ?><strong class='conflict_warning'>NODES <? foreach($conflict as $bad_node){echo "$bad_node ";} ?> HAVE CONFLICTING DEFINITIONS!  REPLICATION NOT PERMITTED!</strong><?
                    }else{
                       echo $rep_on?'ON':'OFF';
                    }

                    if(!$r->Fields('sequence')){
                       ?><br/> <strong class='sequence_warning'>THIS TABLE HAS NO ID SEQUENCE!</strong><?
                    }

                    if($r->Fields('haspkey')=='f'){
                       ?><br /> <strong class='sequence_warning'>THIS TABLE HAS NO PRIMARY KEY!</strong><?
                    }

?>
		</td>
		<td <?=slony::cluster_defined()?'':'class="hide"' ?> >
  
                         <script type='text/javascript'>var rep_checkbox_orig_state_<?=$r->Fields('tablename');?> = <?=$rep_on?'true':'false'?>;</script>
                     <?if(!$conflict){?><input id='rep_checkbox_<?=$r->Fields('tablename')?>' type='checkbox' class='rep_checkbox' <?=$rep_on?'checked=""':''?> />
			<!--button id="button_<?=$r->Fields('tablename')?>" class="<? echo $rep_on?'disable':'enable'; ?>" tablename="<?=$r->Fields('tablename')?>">  
				<? echo $rep_on?'disable':'enable'; ?>
			</button-->   <? } ?>
		</td>
		<td>
			<input type="button" value="Backup" onclick="backup_table('<?=$r->Fields('tablename')?>')" />
			<span id="backup_<?=$r->Fields('tablename')?>"></span>
		</td>
	</tr>
<?
	$r->MoveNext();
endwhile;
?>
</table>
<?

template::inc('global','bottom');

?>
