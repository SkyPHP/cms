<?
$r = sql("SELECT pg_catalog.shobj_description(d.oid, 'pg_database') as description
            FROM pg_catalog.pg_database d
            WHERE d.datname = '{$db_name}';");
$databases = json_decode( $r->Fields('description'), true );
$codebases = get_codebases();


$codebase_name = $_POST['codebase'];
$codebase = $codebases[$codebase_name];
$database = $databases[$codebase_name];
$latest_version = get_latest_version($codebase_name);

echo "Begin updating '{$codebase_name}' codebase to ";
if ( $_POST['type'] == 'stable' ) echo "stable version $latest_version\n";
else echo "developer version {$latest_version}+\n";

// upgrade codebase
$path = $codebase['path'];
if ( $codebase['release']=='stable' && $_POST['type']=='trunk' ) {
// stable --> trunk
    echo "Switching to trunk $latest_version ...\n";
    $command = "(sudo /usr/bin/svn switch {$svn_conf[$codebase_name]['url']}/trunk $path > /dev/null) 3>&1 1>&2 2>&3";
    echo shell_exec("$command") . "\n";

} else if ( $_POST['type']=='stable' ) {
// ? --> stable
    echo "Switching to stable $latest_version ...\n";
    $command = "(sudo /usr/bin/svn switch {$svn_conf[$codebase_name]['url']}/tags/{$latest_version} $path > /dev/null) 3>&1 1>&2 2>&3";
    echo shell_exec("$command") . "\n";

} else {
    // already a trunk check out -- just do a simple update
    echo "svn update ...\n";
    $command = "(sudo /usr/bin/svn update $path --username {$svn_conf[$codebase_name]['username']} --password {$svn_conf[$codebase_name]['password']} > /dev/null) 3>&1 1>&2 2>&3";
    echo shell_exec("$command") . "\n";
}


// refresh our codebase info since we just updated it
$codebases = get_codebases();
$codebase = $codebases[$codebase_name];

echo "Database version: " . $database['version'] . "\n";
echo "Codebase version: " . $codebase['version'] . "\n";
echo "\n";


// now, apply the new schema changes to the database

if ( version_value($database['version']) < version_value($codebase['version']) ) { 
    // database is at least one version behind.

    $page_path = "pages/dev/sql/{$db_platform}/";
    $scandir_path = $path . $page_path;
    $scandir = scandir( $scandir_path );
    print_a($scandir);
    foreach ( $scandir as $filename ) {
        if ( substr($filename,-10)=='.delta.sql' ) {
            $delta_version = substr($filename,0,-10);
            //echo $filename;
            if ( version_value($delta_version) > version_value($database['version']) ) $deltas[ version_value($delta_version) ] = $filename;
        }
    }

    // put the delta files into sequential order
    ksort($deltas);

    // apply the delta files

    $c = 0;
    foreach ( $deltas as $delta ) {
        $c++;

        $sql = file_get_contents("http://".$_SERVER['HTTP_HOST'].'/'.$page_path.$delta);
        echo $delta . "\n";

        if ( $database['trunk'] && $c==1 ) {
            // make sure we skip parts of the first file that are already applied since our dev database may already have some of these revisions
            // find date in sql file that is > $database['trunk'] and execute everything after that line
            $lines = explode("\n",$sql);
            foreach ($lines as $line_number => $line) {
                $line = trim($line);
                preg_match('#^\/\* (.*) by .* \*\/$#', $line, $matches);
                $db_commit_time = $matches[1];
                echo strtotime($db_commit_time) . " --- " . strtotime($database['trunk']) . "\n";
            }
        } else {
            // just execute the entire delta file
            schema_change($sql);
        }

    }//foreach


    echo "Database schema is a stable release, so just apply delta.sql files of all previous\n";
    echo "versions to upgrade database to last stable version.\n";


    echo "Apply changelog.sql to upgrade database to bleeding edge.\n";

} else if ( version_value($database['version']) > version_value($codebase['version']) ) { // database newer than codebase

    echo "***Database schema version is newer than codebase version***\n";
    echo "This should never happen.  Please upgrade your codebase to version {$database['version']}!\n";

}


// database should now be in sync with the last stable codebase

// if this is a trunk codebase, apply new modifications in the changelog
if ( $codebase['release']!='stable' ) {

    if ( !$database['trunk'] ) {
        echo "Database schema is the stable release of this version, but there may be new edge modifications to this version available.\n";
        echo "Apply the entire changelog.sql and set release=edge in db description (if changelog is not empty).\n";

    } else {
        echo "Database is correct version, but new edge modifications to this version may be available.\n";
        echo "Now checking changelog.sql for any schema modifications that are not yet applied...\n";

    }
}


?>