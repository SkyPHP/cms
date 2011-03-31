<?
$codebase = $_POST['sky_qs'][1];
$table = $_POST['sky_qs'][0];

if($table){
	$SQL = "COMMENT ON TABLE $table IS '{\"codebase\":\"$codebase\"}'";
	$dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());
	exit('success');
}else{
	exit('ERROR: no table name');
}


?>