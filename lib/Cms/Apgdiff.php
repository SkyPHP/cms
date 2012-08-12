<?php

namespace Cms;

class Apgdiff
{

    public static $java_version_required = '1.6.0';

    public static $jar_path = '';

    /**
     * Gets the necessary SQL commands to upgrade the original
     * @param $original_sql sql dump of original db
     * @param $modified_sql sql dump of modified db
     * @return string upgrade.sql
     */
    public static function getUpgradeScript($original_sql, $modified_sql)
    {

        if (!static::javaInstalled()) {
            throw new \Exception(
                'Try installing Java ' . static::$java_version_required . '+.'
            );
        }

        if (!static::validJarPath()) {
            throw new \Exception('Cannot find apgdiff .jar file.');
        }

        global $skyphp_storage_path;
        $temp_dir = $skyphp_storage_path . 'apgdiff/';
        @mkdir($temp_dir);

        $time = time();
        $file_a = $temp_dir . 'a-' . $time . '.sql';
        $file_b = $temp_dir . 'b-' . $time . '.sql';

        file_put_contents($file_a, $original_sql);
        file_put_contents($file_b, $modified_sql);

        $jar_path = static::$jar_path;
        $command = "java -jar $jar_path --ignore-start-with $file_a $file_b 2>&1;";
        exec($command, $output);

        return implode("\n", $output);
    }

    /**
     * Gets the dump of the current database
     * @param $db adodb database object
     */
    public static function getDump($db=null)
    {
        global $db;
        $db_name = $db->database;
        $db_host = $db->host;
        $db_user = $db->user;
        $db_password = $db->password;
        #print_r($db);

        // get the schema of the database
        $command = "export PGPASSWORD=$db_password; pg_dump -s -h $db_host -U $db_user $db_name 2>&1;";
        exec($command, $output);

        return implode("\n", $output);
    }

    public static function stripDrops($sql)
    {
        // keep replacing matches until no more matches remain
        $continue = 1;
        while ($continue) {
            // remove DROP statements
            $sql = preg_replace('#\;\s*DROP.*?;\s*#', ';', $sql, 1, $continue);
        }
        // remove DROP statement that happens to be the first statement
        $sql = preg_replace('#^DROP.*?\;\s*#', '', $sql);

        // remove DROP columns
        // remove ALTER TABLE if it no longer has any alterations
        #$sql = preg_replace('#ALTER TABLE[^\;]*?ALTER#s', 'ALTER', $sql);
        #$sql = preg_replace('#ALTER TABLE[^\;]*?CREATE#s', 'CREATE', $sql);
        #$sql = preg_replace('#ALTER TABLE[^\;]*?GRANT#s', 'GRANT', $sql);
        return $sql;
    }

    public static function validJarPath()
    {
        // TODO: check if jar exists
        return true;
    }

    public static function javaInstalled()
    {
        // TODO: check if minimum version of java is installed
        return true;
    }

    public static function getDatabaseName()
    {
        global $db;
        return $db->database;
    }

}
