<?

$codebase_name = $_POST['codebase'];

// checkout, modify, then commit version.txt
echo "modifying version.txt...\n";
if (!$svn_conf[$codebase_name]['url']) die("ERROR: \$svn_conf['{$codebase_name}']['url'] is not defined in config.php!!!");
// be careful if changing path!!!!!  there is a rm -Rf below!!!!!
$path = '/tmp/version.'.rand(1000,9999);
$command = "mkdir $path";
echo shell_exec("$command");
//chmod($path,0777);
$command = "(/usr/bin/svn checkout -N {$svn_conf[$codebase_name]['url']}/trunk $path --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} --no-auth-cache --non-interactive > /dev/null) 3>&1 1>&2 2>&3";
$response = shell_exec("$command");
echo $response;
$version_file = $path . '/version.txt';
$lines = file($version_file);
if (is_array($lines))
foreach ( $lines as $line_number => $line ):
    $setting = explode('=',$line);
    if ( trim($setting[0])=='version' ) {
        $version_txt .= 'version=' . $_POST['version'] . "\n";
    } else if ( trim($setting[0]) ) {
        $version_txt .= trim($setting[0]) . '=' . trim($setting[1]) . "\n";
    }
endforeach;
$post_tag_version_txt = $version_txt;
$version_txt .= 'release=stable';
//echo "\n***" . $version_txt . "***\n";
$fp = fopen($version_file,'w');
fwrite($fp, $version_txt);
fclose($fp);
$command = "(/usr/bin/svn commit $path -m 'pre-tag version change' --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} --no-auth-cache --non-interactive > /dev/null) 3>&1 1>&2 2>&3";
echo $command."\n";
echo shell_exec("$command");


//echo "move changelog to delta file...\n";

// move changelog to delta file
$command = "(/usr/bin/svn move {$svn_conf[$codebase_name]['url']}/trunk/pages/dev/sql/$db_platform/changelog/changelog.sql {$svn_conf[$codebase_name]['url']}/trunk/pages/dev/sql/$db_platform/{$_POST['version']}.delta.sql -m x --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} --no-auth-cache --non-interactive > /dev/null) 3>&1 1>&2 2>&3";
echo $command."\n";
echo shell_exec("$command");

// remove changelog folder
$command = "(/usr/bin/svn delete {$svn_conf[$codebase_name]['url']}/trunk/pages/dev/sql/$db_platform/changelog -m x --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} --no-auth-cache --non-interactive > /dev/null) 3>&1 1>&2 2>&3";
echo $command."\n";
echo shell_exec("$command");



// copy trunk to tags
//echo "creating new tag for $codebase_name version " . $_POST['version'] . "\n";
$command = "(/usr/bin/svn copy {$svn_conf[$codebase_name]['url']}/trunk {$svn_conf[$codebase_name]['url']}/tags/{$_POST['version']} -m x --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} --no-auth-cache --non-interactive > /dev/null) 3>&1 1>&2 2>&3";
echo $command."\n";
echo shell_exec("$command");



//echo "post tag clean up...\n";
// remove release=stable from version.txt
$fp = fopen($version_file,'w');
fwrite($fp, $post_tag_version_txt);
fclose($fp);
$command = "(/usr/bin/svn commit $path -m 'post-tag version change' --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} --no-auth-cache --non-interactive > /dev/null) 3>&1 1>&2 2>&3";
echo $command."\n";
echo shell_exec("$command");
//clean up version.txt temp folder
$command = "rm -Rf $path";
echo $command."\n";
echo shell_exec("$command");



// Create empty changelog.sql in trunk
$repo = $svn_conf[$codebase_name]['url'];
$username = $svn_conf[$codebase_name]['username'];
$password = $svn_conf[$codebase_name]['password'];

$local_path = '/tmp/tag'.rand(1000,9999).'/';
$repo_path = '/pages/dev/sql/' . $db_platform . '/changelog/';
$filename = 'changelog.sql';

// mkdir for changelog in the repository
$temp = my_array_unique(explode('/',$repo_path));
foreach ($temp as $folder):
    $mkdir_path .= '/' . $folder;
    $command = "svn mkdir $repo/trunk{$mkdir_path} -m x --username=$username --password=$password --non-interactive 2>&1";
    //echo $command."\n";
    shell_exec("$command");
endforeach;

// create temporary checkout
$command = "svn co $repo/trunk{$repo_path} $local_path --username=$username --password=$password --non-interactive 2>&1";
echo $command."\n";
echo shell_exec("$command");

// create empty file
$command = "touch {$local_path}{$filename}";
echo $command."\n";
echo shell_exec("$command");

// add to working copy
$command = "svn add {$local_path}{$filename} 2>&1";
echo $command."\n";
echo shell_exec("$command");

// commit to repo
$command = "svn commit {$local_path}{$filename} -m 'init changelog' --username=$username --password=$password --non-interactive 2>&1";
echo $command."\n";
echo shell_exec("$command");

// delete the temporary working copy
$command = "rm -Rf {$local_path}";
echo $command."\n";
echo shell_exec("$command");




// Update db comment: new version, erase trunk time
$r = sql("SELECT pg_catalog.shobj_description(d.oid, 'pg_database') as description
            FROM pg_catalog.pg_database d
            WHERE d.datname = '{$db_name}';");
$db_descr_arr = json_decode( $r->Fields('description'), true );
unset($db_descr_arr[$codebase_name]['trunk']);
$db_descr_arr[$codebase_name]['version'] = $_POST['version'];
$db_comment = json_encode($db_descr_arr);
$SQL = "COMMENT ON DATABASE $db_name IS '$db_comment'";
echo $SQL;
sql($SQL);


?>