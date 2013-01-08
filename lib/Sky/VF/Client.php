<?php

namespace Sky\VF;

/**
 * @package SkyPHP
 */
class Client
{

    /**
     * @var string
     */
    public static $version = '2';

    /**
     * @var \VF\Client
     */
    protected static $client = null;

    /**
     * Sets the client with the config array
     * @param   array   $conf
     */
    public static function config($conf = array())
    {
        static::$client = new \VF\Client($conf);
    }

    /**
     * @return  \VF\Client
     */
    public static function getClient()
    {
        return static::$client;
    }

    /**
     * @return  Boolean
     */
    public static function hasClient()
    {
        return (bool) static::getClient();
    }

    /**
     * @throws \Exception   if no client
     */
    public static function checkForClient()
    {
        if (!static::hasClient()) {
            throw new \Exception('Cannot make a request without a client initialized');
        }
    }

    /**
     * Cannot override, cannot instantiate
     * @throws  \LogicException
     */
    private function __construct()
    {
        throw new \LogicException('Cannot instantiate this class. It is a singleton.');
    }


    /**
     * Gets the given item and transforms it as necessary
     * Usage:
     *      vf::getItem($id, 300, 300)
     * @param   mixed   $id     id | ide | myid
     * @param   mixed   $width  int | array
     * @param   string  $height
     * @param   string  $crop
     */
    public static function getItem($id, $width = null, $height = null, $crop = null)
    {
        if (!$id) return false;

        static::checkForClient();

        $params = static::prepOperations($width, $height, $crop);

        // use cached getItem request if it exists
        $memkey = "vf2:getItem:" . serialize(array($id, $params));
        if (!$_GET['vf_refresh']) $cached_response = mem($memkey);
        if ($_GET['elapsed']) krumo($cached_response);
        if ($cached_response) return $cached_response;

        $re = static::getClient()->getItem($id, $params);

        if ($re->errors) {
            return $re;
        }

        // cache getItem request if no error
        $response = $re->item;
        mem($memkey, $response);
        return $response;
    }


    /**
     * Gets the given item and transforms it as necessary
     * Usage:
     *      vf::getItems($id, 300, 300)
     * @param   mixed   $id     array | csv
     * @param   mixed   $width  || array
     * @param   string  $height
     * @param   string  $crop
     */
    public static function getItems($items, $width = null, $height = null, $crop = null)
    {
        static::checkForClient();

        $params = static::prepOperations($width, $height, $crop);

        if (!is_array($items)) {
            $items = array_filter(explode(',', $items));
        }

        $params = array_merge(
            $params,
            array(
                'items' => implode(',', $items)
            )
        );

        // use cached getItems request if it exists
        $memkey = "vf2:getItems:" . serialize($params);
        $cached_response = mem($memkey);
        if ($cached_response) return $cached_response;


        $re = static::getClient()->getItems($params);

        if ($re->errors) {
            return $re;
        }

        // cache getItems request if no error
        $response = $re->items;
        mem($memkey, $response);
        return $response;
    }

    /**
     * @param   string  $id
     * @param   array   $params
     * @return  \stdClass
     */
    public static function getFolder($id, array $params = array())
    {
        global $cache_vf2_folders;

        static::checkForClient();

        $params = static::prepOperations($params['width'], $params['height'], $params['crop']);

        $memkey = "vf2:getFolder:" . serialize(array($id,$params));

        if ($cache_vf2_folders) {

            $re = mem($memkey);

            // if there has been an upload to this folder since being cached, refresh it
            $last_upload_memkey = "vf2:getFolder:lastUpload:" . $id;
            $last_upload = mem($last_upload_memkey);

            if (!$re->cache_time || $re->cache_time < $last_upload) {
                elapsed($re->cache_time . ' < ' . $last_upload);
                $re = null;
            } else {
                elapsed($re->cache_time . ' !< ' . $last_upload);
            }


        }

        if (!$re) {
            $re = !static::isPath($id)
                ? static::getClient()->getFolder($id, $params)
                : static::getClient()->getFolderByPath(array_merge(
                    $params,
                    array(
                        'path' => $id
                    )
                ));

            $save_to_mem = true;
        }

        if ($re->folder) {
            if ($save_to_mem) {
                $re->cache_time = date('U');
                mem($memkey, $re);
            }
            return $re->folder;
        }
        return $re;
    }

    /**
     * @param   string  $folder
     * @return  \stdClass
     */
    public static function getRandomItemId($folder)
    {
        static::checkForClient();

        $re = static::getRandomItem($folder);

        return $re->errors ? $re : $re->id;
    }

    /**
     * Gets a random item from a folder
     * @see static::prepOperations()
     * @param   string  $folder
     * @param   mixed   $width
     * @param   string  $height
     * @param   string  $crop
     * @return  \stdClass
     */
    public static function getRandomItem($folder, $width = null, $height = null, $crop = null)
    {
        static::checkForClient();

        $re = static::getRandomItems($folder, 1, $width, $height, $crop);

        return $re->errors ? $re : $re[0];
    }

    /**
     * Gets random items from a folder
     * @see static::prepOperations()
     * @param   string  $folder
     * @param   int     $limit
     * @param   mixed   $width
     * @param   string  $height
     * @param   string  $crop
     * @return  array
     */
    public static function getRandomItems($folder, $limit = 0, $width = null, $height = null, $crop = null)
    {
        static::checkForClient();

        //$params = static::prepOperations($width, $height, $crop);
        $params = array(
            'width' => $width,
            'height' => $height,
            'crop' => $crop,
            'limit' => $limit,
            'random' => true
        );
        #$params['limit'] = $limit;
        #$params['random'] = true;

        /*
        if ($_GET['vf_debug']) {
            echo 'getRandomItems $params:';
            krumo($params);
        }
        */

        $re = static::getFolder($folder, $params);

        return is_array($re->items) ? $re->items : $re;
    }

    /**
     * @param   string  $id
     * @return  \stdClass
     */
    public static function removeItem($id)
    {
        static::checkForClient();

        $item = static::getItem($id);
        $path = $item->folder;
        $folder = static::getFolder($path);

        // update the last upload time so we know when to refresh cached folders
        $memkey = "vf2:getFolder:lastUpload:" . $path;
        mem($memkey, date('U'));
        $memkey = "vf2:getFolder:lastUpload:" . $folder->id;
        mem($memkey, date('U'));

        return static::getClient()->deleteItem($id);
    }

    /**
     * Edits the item with the given parameters
     * @param   string  $id
     * @param   array   $params
     * @return  \stdClass
     */
    public static function editItem($id, $params)
    {
        static::checkForClient();

        return static::getClient()->editItem($id, $params);
    }

    /**
     * @param   array   $args
     * @return  Slideshow
     */
    public static function slideshow($args)
    {
        return new Slideshow($args);
    }

    /**
     * @param   array   $args
     * @return  Uploader
     */
    public static function uploader($args)
    {
        return new Uploader($args);
    }

    /**
     * @param   array   $args
     * @return  Gallery
     */
    public static function gallery($args)
    {
        return new Gallery($args);
    }

    /**
     * Takes in width/height/crop, width can ben array, operations, etc
     * and returns a params array with operations json_encoded
     * @param   mixed   $width  can be an array of operations or {width height crop}
     * @param   string  $height
     * @param   string  $crop
     * @return  array {operations}
     */
    protected static function prepOperations($width = null, $height = null, $crop = null)
    {
        $params = is_array($width)
            ? $width
            : array(
                'width' => $width,
                'height' => $height,
                'crop' => $crop
            );

        $operations = $params['operations'] ?: array();
        if ($params['width'] || $params['height']) {

            $op = array(
                'width' => $params['width'],
                'height' => $params['height'],
                'type' => $params['crop']
                    ? ( $params['no_resize'] ? 'crop' : 'smart_crop' )
                    : 'resize'
            );

            if ($op['type'] != 'resize') {
                $op['gravity'] = (!$params['crop'] || is_bool($params['crop']))
                    ? 'Center'
                    : $params['crop'];
            }

            $operations[] = array_filter($op);
        }

        return array_filter(array(
            'operations' => $operations ? json_encode($operations) : null
        ));
    }

    /**
     * Checks to see if a string looks like a path
     * @param   string  $str
     * @return  Boolean
     */
    protected static function isPath($str)
    {
        return strpos($str, '/') !== false;
    }

}
