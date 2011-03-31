<table class="listing">
	<tr>
		<th>Codebase</th><th>Installed<br>Version</th><th>Current<br>Version</th><th>Path</th><th>User Name</th><th>Password</th><th>Update</th>
	</tr>
<?
//current websites codebase info
$codebase_l = dirname($_SERVER['SCRIPT_FILENAME']).'/';
$info = getVersion($codebase_l);
$aql = "sky_codebase	{
							latest_version
							where name = '{$info['codebase']}'
						}";
$rs = aql::select($aql);
if($rs){
	$info['current'] = $rs[0]['latest_version'];
}
?>
	<tr>
		<td class = "codebase"><?=$info['codebase'] ?></td>
		<td class = "version"><?=$info['version'] ?></td>
		<td class = "current"><?=$info['current'] ?></td>
		<td class = "location"><?=$info['location'] ?></td>
		<td><input type="text" name="username" class="username"/></td>
		<td><input type="password" name="password" class = "password" /></td>
		<td><input onclick="update('<?=$info['codebase'] ?>',this)" type="button" value="Update" /></td>
	</tr>
<?
//skyphp info
$codebase_l = $sky_install_path;
$info = getVersion($codebase_l);	
$aql = "sky_codebase	{
							latest_version
							where name = '{$info['codebase']}'
						}";
$rs = aql::select($aql);
if($rs){
	$info['current'] = $rs[0]['latest_version'];
}
?>
	<tr>
		<td class = "codebase"><?=$info['codebase'] ?></td>
		<td class = "version"><?=$info['version'] ?></td>
		<td class = "current"><?=$info['current'] ?></td>
		<td class = "location"><?=$info['location'] ?></td>
		<td><input type="text" name="username" class="username"/></td>
		<td><input type="password" name="password" class = "password" /></td>
		<td><input oncleck="update('<?=$info['codebase'] ?>',this)" type="button" value="Update" /></td>
	</tr>
<?
//all other if any
#var_dump($sky_domain);
#var_dump($sky_conf[$sky_domain]['codebase_path_arr']);
if(is_array($sky_conf[$sky_domain]['codebase_path_arr'])){
	$codebases = $sky_conf[$sky_domain]['codebase_path_arr'];
}else{
	$codebases = $codebase_path_arr;
}
foreach($codebases as $codebase_l){
	$info = getVersion($codebase_l);
	$aql = "sky_codebase	{
								latest_version
								where name = '{$info['codebase']}'
							}";
	$rs = aql::select($aql);
	if($rs){
		$info['current'] = $rs[0]['latest_version'];
	}
?>
	<tr>
		<td class = "codebase"><?=$info['codebase'] ?></td>
		<td class = "version"><?=$info['version'] ?></td>
		<td class = "current"><?=$info['current'] ?></td>
		<td class = "location"><?=$info['location'] ?></td>
		<td><input type="text" name="username" class="username"/></td>
		<td><input type="password" name="password" class = "password" /></td>
		<td><input onclick="update('<?=$info['codebase'] ?>',this)" type="button" value="Update" /></td>
	</tr>
<?
}

?>
</table>

<?
	
function getVersion($codebase_l){
	$version_f = $codebase_l.'version.txt';
	$info = array();
	$info['location'] = $codebase_l;
	if(file_exists($version_f)){
		$lines = file($version_f );
		foreach($lines as $line){
			if(strpos($line,';')){
				//If ';' then it's a comment. Do nothing.
			}else{
				list($name,$value) = split('=',$line);
				$info[trim($name)] = trim($value);
			}
		}
	}
	return $info;
}
?>