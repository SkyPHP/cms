<?
$backup_directory = '/backup';
$backup_folder_path = '';
$files = @scandir($backup_directory);
if($files){
	foreach($files as $file){
		$file_path = $backup_directory.'/'.$file;
		if(is_dir($file_path) && strpos($file,'.')!==0){
			$backup_folder_path = $file_path;
		}
	}
}
$presets = array();
$ddl_file = $backup_folder_path.'/'.$table.'.schema.sql';
if(file_exists($ddl_file)){
	$ddl = '';
	$lines = file($ddl_file);
	foreach ( $lines as $line ){
		$ddl .= $line;
	}
	$presets['DDL'] =  $ddl;
}
?>