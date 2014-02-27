<?php
namespace Crave\Model;
/**
 * Deprecated.
 * For backwards compatibility.
 */
class aql extends \Sky\AQL
{

    /**
     * Gets one or more values from the database
     * @param   string  $param1
     * @param   string  $param2
     * @param   mixed   $options
     * @return  mixed
     */
    public static function value($param1, $param2, $options = array())
    {
        if (!$param2) {
            return null;
        }

        // third param can be a db connection resource
        if (is_object($options) && get_class($options) == 'PDO') {
            $db_conn = $options;
            $options = array();
        }

        // get connection
        $db_conn = $db_conn ?: $options['db'];
        $db_conn = $db_conn ?: self::getDB();

        $is_aql = aql::is_aql($param1);

        // normalize primary table and aql
        if ($is_aql) {
            $aql = $param1;
            $primary_table = aql::get_primary_table($aql);
        } else {
            list($primary_table, $field) = explode('.',$param1);
            $aql = "$primary_table { $field }";
        }

        // get where
        $multiple = false;
        $where = call_user_func(function() use($primary_table, $param2, &$multiple) {

            $spr = '%s.%s = \'%s\'';

            $decrypt = function($r) use($primary_table)  {
                return (is_numeric($r)) ? $r : decrypt($r, $primary_table);
            };

            if (is_numeric($param2)) {
                return sprintf($spr, $primary_table, 'id', $param2);
            }

            if (!is_array($param2)) {

                // check for ide
                $id = $decrypt($param2);
                if (is_numeric($id)) {
                    return sprintf($spr, $primary_table, 'id', $id);
                }

                // otherwise check for slug field on table
                if (!aql::table_field_exists($primary_table, 'slug')) {
                    return;
                }

                return sprintf($spr, $primary_table, 'slug', $param2);
            }

            // this is an array
            $multiple = true;

            $param2 = array_filter(array_map($decrypt, $param2));
            $param2[] = -1;

            $ids = implode(',', $param2);
            return "{$primary_table}.id in ({$ids})";
        });

        // return if we dont find a where clause
        if (!$where) {
            return false;
        }

        $params = [
            'where' => [$where],
            'order by' => 'id asc',
            'db' => $db_conn
        ];

        $rs = aql::select($aql, $params);

        if ($multiple) {
            return $rs;
        }

        if ($is_aql) {
            return $rs[0];
        }

        return $rs[0]->$field;
    }


    /**
     * Checks if a string is AQL
     * @param   string  $aql
     * @return  Boolean
     */
    public static function is_aql($aql)
    {
        return strpos($aql, '{') !== false;
    }


    /**
     * @global  $db
     * @return  PDO connection | null
     */
    public static function getDB()
    {
        global $db;
        return $db;
    }


    /**
     * @param   string  $aql
     * @return  string
     */
    public static function get_primary_table($aql)
    {
        $aql = (self::is_aql($aql)) ? $aql : self::get_aql($aql);
        $t = new self($aql);
        return $t->primaryTable;
    }


    /**
     * @param   string  $field_name
     * @return  string
     */
    public static function get_decrypt_key($field_name)
    {
        $count = -4;
        $table_name = substr($field_name, $count);
        if ($table_name != '_ide') {

            $count = -3;
            $table_name = substr($field_name, $count);
            if ($table_name != '_id') {
                return null;
            }
        }

        $temp = substr($field_name, 0, $count);
        $start = strpos($temp, '__');

        if ($start) {
            $start += 2;
        }

        return substr($temp, $start);
    }


    /**
     * @param   string  $table
     * @param   string  $field
     * @return  Boolean
     */
    public static function table_field_exists($table, $field)
    {
        $fields = self::getColumns($table);

        return ($fields && in_array($field, $fields));
    }


    /**
     * @param string $model
     * @return string aql statement
     */
    public static function get_aql($model)
    {
        if (class_exists($model)) {
            return $model::AQL;
        }

    }


    /**
     * Makes a minimal AQL statement from the given AQL object
     * @param   array   $arr
     * @return  string
     */
    public static function minAQLFromArr($AQL)
    {
        $i = 0;
        $aql = '';
        foreach ($AQL->blocks as $t) {
            $aql .= "{$t->table} as {$t->alias}";

            if ($t->joinOn) {
                $aql .= " on {$t->joinOn}";
            }

            $aql .= (($i === 0) ? ' { id } ' : ' { } ') . "\n";
            $i++;
        }

        return $aql;
    }



    /**
     * Gets an array of standard objects from the database for the given AQL statement
     * @param mixed $aql AQL string or Sky\AQL object
     * @param array $params
     *      where       string|array
     *      order by    string
     *      limit       int
     *      dbw         bool
     *
     */
    public static function select($aql, $params = [])
    {
        $rs = \Sky\AQL::select($aql, $params);

        // for backwards compatibility, encrypt any fields ending with _id
        foreach ($rs as $i => $r) {
            foreach ($r as $k => $v) {
                if (substr($k, -3) == '_id') {
                    $start = strpos($k, '__');
                    if ($start) {
                        $start += strlen('__');
                    }
                    $table = substr($k, $start, -3);
                    $property = $k . 'e';
                    $rs[$i]->$property = encrypt($v, $table);
                }
            }
        }
        return $rs;
    }

    /**
     *
     */
    public static function profile($aql, $id)
    {
        $primary_table = self::get_primary_table($aql);
        $rs = self::select($aql, array(
            'where' => "$primary_table.id = $id"
        ));
        return $rs[0];
    }

}
