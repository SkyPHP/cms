<?

$tablename = $_POST['sky_qs'][0];
$action = $_POST['sky_qs'][1];

if($tablename == '-multiple-'){
   $tablename = $_POST['tables'];
}

slony::$silent = NULL;

$tables = explode(',',$tablename);

switch($action){
   case('enable'):
      slony::add_table($tables);
      break;
   case('disable'):
      foreach($tables as $table){
         slony::drop_table($table);
      }
      break;
   default:
}

/*$i = 0;
$SQL = "alter table $tablename $action replication on slave $i";
$dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());

$SQL = "alter table $tablename $action replication";
$dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());

if ($action=='enable') echo 'on';
else echo 'off';
*/
?>
