<?
	if($_POST['sql']){
		if($_POST['codebase']){
			$codebase = $_POST['codebase'];
			$sql = $_POST['sql'];
			#$dbw->Execute($sql) or die("$sql<br>" . $dbw->ErrorMsg());
			$mod__person_id = $_SESSION['login']['member_id'];
			$fields = array (
							'codebase'=>$codebase,
							'mod__person_id'=>$mod__person_id,
							'version'=>'trunk',
							'sql'=>$sql
							);
			aql::insert('sky_sql_log',$fields);
			exit('success');
		}else
			exit('Enter codebase');
	}

?>