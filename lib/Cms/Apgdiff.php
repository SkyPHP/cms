<?php

namespace Cms;

/**
 * SkyPHP wrapper for apgdiff
 */
class Apgdiff
{

    /**
     * Path to apgdiff jar file (apgdiff-2.3.jar)
     * http://apgdiff.startnet.biz/
     * @var string
     */
    public static $jar_path = '';

    /**
     * @var string
     */
    public static $java_version_required = '1.6.0';

    /**
     * Gets the necessary SQL commands to upgrade the original
     * @param $original_sql sql dump of original db
     * @param $modified_sql sql dump of modified db
     * @return string upgrade.sql
     * @global $skyphp_storage_path;
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
     * Gets the dump sql of the current database
     * @param $db adodb database object
     * @return string
     * @global $db
     */
    public static function getDump($db=null)
    {
        global $db_name, $db_host, $db_username, $db_password;
        #print_r($db);

        // get the schema of the database
        $command = "export PGPASSWORD=$db_password; pg_dump -s -h $db_host -U $db_username $db_name 2>&1;";
        exec($command, $output);

        return implode("\n", $output);
    }

    /**
     * Removes DROP statements from the given sql
     * @param string $sql
     * @return string
     */
    public static function stripDrops($sql)
    {
        // temporarily add leading semi-colon
        $sql = ';' . $sql;

        // keep replacing DROP statements until no more remain
        $continue = 1;
        $pattern = '#\;\s*DROP.*?\;#';
        while ($continue) {
            $sql = preg_replace($pattern, ';', $sql, 1, $continue);
        }

        // remove DROP COLUMN lines ending with comma
        $continue = 1;
        $pattern = '#(ALTER TABLE.*?)DROP COLUMN[^\;]*?\,\s*#s';
        while ($continue) {
            $sql = preg_replace($pattern, '$1', $sql, 1, $continue);
        }

        // remove DROP COLUMN lines ending with semi-colon
        $continue = 1;
        $pattern = '#(ALTER TABLE.*?)\s*DROP COLUMN[^\,]*?\;#s';
        while ($continue) {
            $sql = preg_replace($pattern, '$1;', $sql, 1, $continue);
        }

        // remove ALTER TABLE if it no longer has any alterations
        $pattern = '#ALTER TABLE \w*?;\s*#';
        $sql = preg_replace($pattern, '', $sql);

        // remove temporary leading semi-colon and whitespace
        $pattern = '#^\;\s*#s';
        $sql = preg_replace($pattern, '', $sql);

        return $sql;
    }

    /**
     * Determines if we have a valid apgdiff jar file
     * @return bool
     * @todo
     */
    public static function validJarPath()
    {
        // TODO: check if jar exists
        return true;
    }

    /**
     * Determines if we have the required version of java installed
     * @return bool
     * @todo
     */
    public static function javaInstalled()
    {
        // TODO: check if minimum version of java is installed
        // static::$java_version_required
        return true;
    }

    /**
     * Gets the name of the current database
     * @return string
     * @global $db
     */
    public static function getDatabaseName()
    {
        global $db_name;
        return $db_name;
    }

}
