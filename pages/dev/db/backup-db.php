<?
$backup_directory = '/backup';

//delete old backups
//figure out date 10 days ago
#$time_ten_days = strtotime("tomorrow");
$time_ten_days = strtotime("10 days ago");
$today_year = date('Y');
$today_month = date('m');
$today_day = date('d');
$today_oo_time = mktime(0, 0, 0, $today_month, $today_day, $today_year);
$todays_backup_files = array();
$files = scandir($backup_directory);
foreach($files as $file){
	$file_path = $backup_directory.'/'.$file;
	//get only folders
	if(is_dir($file_path) && strpos($file,'.')!==0){
		//understand what date it is that the folder represents
		$year = substr($file,0,4);
		$month = substr($file,4,2);
		$day = substr($file,6,8);
		$dash = substr($file,8,1);
		$hour = substr($file,9,2);
		$file_date = mktime($hour, 0, 0, $month, $day, $year);
		//delete folders 10 days older
		if($file_date<$time_ten_days){
			$command = "rm -r $file_path 2>&1 1> /dev/null";
			echo shell_exec($command);
		}elseif($file_date>$today_oo_time){
			$todays_backup_files[] = $file;
		}
	}
}

//make new backups
$today_folder = date("Ymd-His");
$backup_subdirectory = $backup_directory.'/'.$today_folder;
$count = 0;
//make directory recursively
mkdir($backup_subdirectory,0700,true);
$SQL = "select pg_tables.tablename as table_name
		from pg_tables
		left join repl_relations on repl_relations.namespace = pg_tables.schemaname and repl_relations.relation = pg_tables.tablename
		where pg_tables.schemaname = 'public'
		order by pg_tables.tablename asc";
$r = $dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());
if (!$r->EOF){
	$r = $r->GetArray();
	foreach($r as $table){
		$table_name = $table['table_name'];
		$count++;
		#if($count>5)
		#	break;
		$command = "/usr/bin/pg_dump -a -b -O -t $table_name --disable-triggers -U $db_username $db_name > $backup_subdirectory/$table_name.data.sql";
		echo shell_exec($command);
		$command = "/usr/bin/pg_dump -s -x -t $table_name --disable-triggers -U $db_username $db_name > $backup_subdirectory/$table_name.schema.sql";
		echo shell_exec($command);
	}
}
//delete any other backups that have been made today since 00:00 oclock
if($todays_backup_files){
	foreach($todays_backup_files as $file){
		$file_path = $backup_directory.'/'.$file;
		$command = "rm -r $file_path 2>&1 1> /dev/null";
		echo shell_exec($command);
	}
}
#print_a($todays_backup_files);
?>