<?
if($_POST['func']=='checkout'){
	$username = "namik";
	$password = "bluetulip205";
	$ftp_server = 'mars.skydev.net';
	#$ftp_user_name = 'nynycus';
	$path = $_POST['path'];
	$folder = basename($path);
	$repo = $_POST['repo'];
	$ftp_user_pass = $ftp_user_password; //taken from configuration file
	$ftp_user_name = basename(str_replace(('/'.$folder),'',$path)); //it's the folder right before the target folder
	if(is_dir($path)){
		$files = scandir($path);
		if($files && in_array('.svn',$files)){
			exit('Error: Looks like this directory has already been checked out. Exiting.');			
		}else{
			// try to chmod folder to 0777 to be able to perform ensuing commands
			$conn_id = ftp_connect($ftp_server);
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			if (ftp_chmod($conn_id, 0777, $folder) !== false) {
				echo "<br>->FTP: $folder chmoded successfully to 0777";
			} else {
				exit("<br>->FTP: Error: could not chmod $folder.Exiting.");
			}
			$command = "/usr/local/bin/svn co https://svn.skydev.net/$repo $path --username=$username --password=$password --non-interactive 2>&1";
			exec_c($command,'checking out directory');
			if (ftp_chmod($conn_id, 0755, $folder) !== false) {
				echo "<br>->FTP: $folder chmoded successfully to 0755";
			} else {
				exit( "<br>->FTP: Error: could not chmod $folder.Exiting.");
			}
		}
	}else{
		exit('Error: Specified path is not a directory. Exiting.');
	}

	exit('<br>Finished');
}else
	exit('unknown function');
		
function exec_c($command,$comment){
	$resp = trim(exec($command));
	if($resp){
		echo '<br>->'.$comment.':<br>';
		echo $resp;
	}else{
		echo '<br>->'.$comment;
	}		
}
?>