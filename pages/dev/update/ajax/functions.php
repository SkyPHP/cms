<?
if($_POST['func']=='update'){
	require_once('pages/dev/ssh/ssh_lib.php');
	$codebase =  $_POST['codebase'];
	$local_path = $_POST['path'];
	$old_version = $_POST['old_version'];
	$password = 'bluetulip205';
	$username = 'namik';
	$folder = basename($local_path);

	$index = $local_path.'index.php';
	$aql = "sky_codebase	{
								latest_version,
								repo
								where name = '$codebase'
							}";
	$rs = aql::select($aql);
	if($rs){
		$version = $rs[0]['latest_version'];
		$repo = $rs[0]['repo'];
	}else{
		exit('Error: codebase "'.$codebase.'" could not be found. Exiting');
	}
	$command = 'hostname';
	$ssh_host = trim(shell_exec($command));
	$ssh_user_name = $_POST['username'];
	$ssh_user_pass = $_POST['password'];	
	$output_stop = ($ssh_user_name=='root')?'#':'\$';
	try { 
		$ssh = new SSH_in_PHP($ssh_host,22); 
		$ssh->connect($ssh_user_name,$ssh_user_pass); 
		$cycle = true; 
		while ($cycle) { 
			$data .= $ssh->read(); 
			if (ereg($output_stop,$data)) { 
				$cycle = false; 
			} 
		} 
		
		#$command = "chmod 755 $folder";
		#exec_ssh($command,'cmmodding to 755.');
		
		$command = "/usr/local/bin/svn switch https://svn.skydev.net/$repo/tags/$version $local_path --username=$username --password=$password --non-interactive 2>&1";
		exec_ssh($command,'Switching to new tag/repository.');
		$ssh->disconnect(); 

	} catch (SSHException $e) { 
		echo "An Exception Occured: {$e->getMessage()} ({$e->getCode()})\n"; 
		echo "Trace: \n"; 
		echo print_r($e->getTrace()); 
		echo "\n"; 
	} 
	//execute sql if there is some
	$sql_dir = $local_path.'sql';
	if(is_dir($sql_dir)){
		$sql_files = scandir($sql_dir);
		//execute all new sql
		sort($sql_files);
		$sql_executed = false;
		foreach($sql_files as $file){
			if(strpos($file,'.sql') && $old_version<basename($file, ".sql")){
				$sql_file_loc = $sql_dir.'/'.$file;
				$file_r = fopen($sql_file_loc, "r") or exit("Unable to open SQL file!");
				$sql_command = '';
				while(!feof($file_r)){
				  $sql_command .= fgets($file_r);
				}
				fclose($file_r);
				$dbw->Execute($sql_command) or die("$sql<br>" . $db->ErrorMsg());
				$sql_executed = true;
				echo "<br>$file executed</br>";
			}
		}
	}
	if($sql_executed){
		echo "<br>New SQL executed<br>";
	}else{
		echo "<br>No new SQL executed<br>";
	}
	/*
	
	if(file_exists($index)){
		$fileownerarray=posix_getpwuid(fileowner($index));
		$fileowner=$fileownerarray['name'];
		if($fileowner!='nobody'){
			exit('Error: owner seems to be "'.$fileowner.'". Need to be "nobody".');
		}
	}
	#exec_c('whoami','whoami');
	#exec_c('ls -la '.$local_path.'version.txt','ls -la '.$local_path.'version.txt');
	//Delete contents of live site

	$conn_id = ftp_connect($ftp_server);

	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	// try to chmod folder to 0777 to be able to perform ensuing commands
	$folder = 'public_html';
	if (ftp_chmod($conn_id, 0777, $folder) !== false) {
	 echo "<br>->FTP: $folder chmoded successfully to 0777";
	} else {
	 exit( "<br>->FTP: Error: could not chmod $folder.Exiting.");
	}
	/*
	$command = " chmod 0777 $local_path*  2>&1";
	exec_c($command,'Changing mod of '.$folder.' content to 0777');
	$command = 'rm -r -f '.$local_path.'* 2>&1';
	exec_c($command,'Deleting contents of live site');
	//export repository
	$command = "/usr/local/bin/svn export https://svn.skydev.net/$repo/tags/$version $local_path --force --username=$username --password=$password --non-interactive 2>&1";
	exec_c($command,'Exporting files from repository to server.');
*/

	/*
	$command = "chmod -R 0755 $local_path*  2>&1";
	exec_c($command,'Changing mod of '.$folder.' folders back to 0755');
	
	$command = "chmod 0644 $(find . ! -type d) $local_path*  2>&1";
	exec_c($command,'Changing mod of '.$folder.' files back to 0644');
	
	$command = "/usr/local/bin/svn switch https://svn.skydev.net/$repo/tags/$version $local_path --username=$username --password=$password --non-interactive 2>&1";
	exec_c($command,'Switching to new tag/repository.');
	
	//update
	#$command = "/usr/local/bin/svn update $local_path --username=$username --password=$password --non-interactive 2>&1";
	#exec_c($command,'Updating.');
	if (ftp_chmod($conn_id, 0755, $folder) !== false) {
	 echo "<br>->FTP: $folder chmoded successfully to 0755";
	} else {
	 exit( "<br>->FTP: Error: could not chmod $folder.Exiting.");
	}
	
	*/
	
	exit("<br>Finish.<br>");

}else{
	exit("invalid function");
}

function exec_c($command,$comment){
	$resp = trim(exec($command));
	if($resp){
		echo '<br>->'.$comment.':<br>';
		echo $resp;
	}else{
		echo '<br>->'.$comment;
	}		
}
function exec_ssh($command, $comment){
	$ssh = $GLOBALS['ssh'];
	$output_stop = $GLOBALS['output_stop'];
	$ssh->write($command." \n"); 
	$data = '';
    $cycle = true; 
    while ($cycle) { 
        $data .= $ssh->read(); 
        if (ereg($output_stop,$data)) { 
            $cycle = false; 
        } 
    } 
	echo '<br>'.$comment.'<br>';
	if($data){
		echo '<br><pre>'.$data.'</pre><br>';
	}
	return $data;
}
?>