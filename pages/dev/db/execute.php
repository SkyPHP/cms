<?

echo '<br />';

$tablename = $_POST['table'];

// apply the changes to the database(s)
if ($slony_cluster_name):  // if database is being replicated
   slony::$silent = true;
   $dump = slony::propagate_schema_change($_POST['sql']);

   echo $_POST['sql'];

   echo "\n<br />\n<br />";

   //var_dump($dump); //comment

   foreach($dump['output'] as $line){
      if(preg_match('#error#i',$line)){ 
         echo '<div style="color:red;">'. $line .'</div>';
         $do_not_log = true;
      }else{
         echo $line;
      }

      echo "\n<br />";
   }

   echo "\n<br />";

   if($do_not_log){
      echo "<div style='color:red;'>There was an error propagating the schema change on one or more node, you will need to apply the change manually</div>";
   }else{

   }

else: // no replication is going on.. just execute on the database

	echo 'no replication on this database.<br />';

	// make schema change
	$SQL = $_POST['sql'];
	echo $SQL . '<br />';
	$dbw->Execute($_POST['sql']);
	if ($dbw->ErrorMsg()):
        echo '<div style="color:red;">' . $dbw->ErrorMsg() . '</div><br />';
        $do_not_log = true;
    endif;

endif;


if (!$do_not_log):
    // we did not have an error...
    // append this SQL to the changelog.sql in the svn repository

    $SQL = "select  obj_description(pg_class.oid, 'pg_class') as description
            from pg_tables
            left join pg_class on pg_class.relname = pg_tables.tablename
            where pg_tables.schemaname = 'public'
            and pg_tables.tablename = '$tablename'";
    $r = $dbw->Execute($SQL) or die("$SQL<br>".$dbw->ErrorMsg());
    $jarr = json_decode( $r->Fields('description'), true );
    $codebase_name = $jarr['codebase'];
    if (!$codebase_name):
        echo "Unknown codebase for $tablename table - " . '<span style="color: red;">SQL has NOT been logged!</span><br />';
    else:
        $aql = "dev_codebase {
                    svn_url,
                    svn_username,
                    svn_password
                    where name = '{$codebase_name}'
                }";
        $rs = aql::select($aql);
        if (!$rs):
            echo "Unknown repository for $codebase_name codebase - " . '<span style="color: red;">SQL has NOT been logged!</span><br />';
        else:

            $local_path = '/tmp/sql-editor/';
            $repo_path = '/pages/dev/sql/' . $db_platform . '/changelog/';
            $filename = 'changelog.sql';

            @mkdir($local_path);

            $repo = $rs[0]['svn_url'];
            $username = $rs[0]['svn_username'];
            $password = $rs[0]['svn_password'];

            echo "Saving to $repo/trunk{$repo_path}$filename:<br />";

            // mkdir for changelog in the repository
            $temp = my_array_unique(explode('/',$repo_path));
            foreach ($temp as $folder):
                $mkdir_path .= '/' . $folder;
                $command = "svn mkdir $repo/trunk{$mkdir_path} -m sql-editor --username=$username --password=$password --non-interactive 2>&1";
                shell_exec("$command") . '<br />';
            endforeach;

            // create temporary checkout
            $command = "svn co $repo/trunk{$repo_path} $local_path --username=$username --password=$password --non-interactive 2>&1";
            //echo $command . '<br />';
            echo shell_exec("$command") . '<br />';

            // append to this file
            $timestamp = date('m/d/Y h:ia');
            $myFile = "{$local_path}{$filename}";
            echo "Appending to $myFile<br />";
            $fh = fopen($myFile, 'a') or die("can't open file");
            fwrite($fh, "\n\n/* ".$timestamp." by ".$_SESSION['login']['fname']." */\n" . $_POST['sql'] . "\n");
            fclose($fh);

            // add to working copy
            $command = "svn add {$local_path}{$filename} 2>&1";
            //echo $command . '<br />';
            shell_exec("$command") . '<br />';

            // commit to repo
            $command = "svn commit {$local_path}{$filename} -m sql-editor --username=$username --password=$password --non-interactive 2>&1";
    //		echo $command . '<br />';
            echo shell_exec("$command") . '<br />';
            //echo '<br />';

            // delete the temporary working copy
            $command = "rm -Rf {$local_path}";
    //		echo $command . '<br />';
    //        echo shell_exec("$command") . '<br />';
    //		echo '<br />';

            // log edge time to database description
            $r = sql("SELECT pg_catalog.shobj_description(d.oid, 'pg_database') as description
                        FROM pg_catalog.pg_database d
                        WHERE d.datname = '{$db_name}';");
            $db_descr_arr = json_decode( $r->Fields('description'), true );
            $db_descr_arr[$codebase_name]['trunk'] = $timestamp;
            $db_comment = json_encode($db_descr_arr);
            $SQL = "COMMENT ON DATABASE $db_name IS '$db_comment'";
            echo $SQL;
            sql($SQL);

        endif;
    endif;
endif;
?>
<br />
<div style="font-size: 16px; font-weight:bold;">
<? if ($do_not_log) { ?>
    An error occurred. Nothing was logged.
<? } else { ?>
    Success.
<? } ?>
</div>

