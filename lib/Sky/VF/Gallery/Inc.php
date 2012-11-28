<?php

namespace Sky\VF\Gallery;

/**
 * @package SkyPHP
 */
abstract class Inc
{

    /**
     * Location of resources
     * @var string
     */
    public static $resource_path = 'lib/Sky/VF/resources/';

    /**
     * @var array
     */
    public static $defaults = array(
        'crop' => false,
        'caption' => false,
        'enlarge' => true,
        'enlarge_width' => 800,
        'enlarge_height' => 800,
        'thumb_width' => 50,
        'thumb_height' => 50,
        'enlarge' => true
    );

    /**
     * @var string
     */
    public $db_field;

    /**
     * @var int
     */
    public $db_row_id;

    /**
     * @var Boolean
     */
    public $caption;

    /**
     * @var string
     */
    public $class;

    /**
     * @var Boolean
     */
    public $crop;

    /**
     * @var Boolean
     */
    public $enlarge;

    /**
     * @var string
     */
    public $enlarge_width;

    /**
     * @var string
     */
    public $enlarge_height;

    /**
     * @var string
     */
    public $filename;

    /**
     * @var \stdClass
     */
    public $folder;

    /**
     * @var string
     */
    public $height;

    /**
     * @var string
     */
    public $html;

    /**
     * @var string
     */
    public $html_include;

    /**
     * @var array
     */
    public $items;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var string
     */
    public $thumb_width;

    /**
     * @var string
     */
    public $thumb_height;

    /**
     * @var string
     */
    public $width;

    /**
     * @param   array   $args
     */
    public function __construct($args)
    {
        $this->setByArray(self::$defaults, static::$defaults, $args);

        if ($args['width'] && !$args['height']) {
            $this->height = null;
        }

        if ($args['height'] && !$args['width']) {
            $this->width = null;
        }

        if (!$this->folder && !$this->items) {
            // throw new \Exception(
            //     'Gallery requires a folder or items to be set.'
            // );

            // if there are no images to show, just fail silently
            return;
        }

        if ((!is_object($this->folder) || !$this->folder->items) && !$this->items) {
            $this->initFolder();
        }

        $this->validate();
        $this->getHTML();
    }

    /**
     * initializes the vfolder object by path
     */
    public function initFolder()
    {

        if ($_GET['vf_debug']) echo 'initFolder: ' . $this->folder . '<br />';
        if (is_object($this->folder)) return;

        $folder_path = $this->folder;

        $this->folder = \Sky\VF\Client::getFolder($this->folder);
        if ($this->folder->errors) {
            $this->folder->path = $folder_path;
        }

        return $this->folder;
    }

    /**
     * Only there as a place holder
     */
    public function validate()
    {

    }

    /**
     * @global  $p \Sky\Page
     * @return  \Sky\Page
     */
    public static function getPage()
    {
        global $p;
        return $p;
    }

    /**
     * Adds vf css and js to the page and whatever other css/js is passed in
     * @param   array $resources    associative
     * @return  \Sky\Page
     */
    public static function addPageResources(array $resources = array())
    {
        $css = \arrayify($resources['css']) ?: array();
        $js = \arrayify($resources['js']) ?: array();

        $css[] = '/' . static::$resource_path . 'css/vf.css';
        $js[] = '/' . static::$resource_path  . 'js/vf.js';

        return static::getPage()->setConfig(array(
            'css' => $css,
            'js' => $js
        ));
    }

    /**
     * Shortcut for rendering mustache pages
     * @param   string  $name
     * @return  string  rendered mustache
     */
    public static function getMustache($name, $data = array())
    {
        $path = static::$resource_path . 'mustache/' . $name . '.m';

        return static::getPage()->mustache($path, $data);
    }

    /**
     * @return  string
     */
    abstract public function getHTML();

    /**
     * Gets an array of vf_item ids from the current object
     * @return array [id]
     */
    public function getItemIDs()
    {
        $items = $this->items ?: $this->folder->items;

        if ($this->db_field && $this->db_row_id) {
            $items = array(
                (object) array(
                    'id' => \aql::value($this->db_field, $this->db_row_id)
                )
            );
        }

        return array_filter(array_map(function($i) {
            return $i->id;
        }, $items ?: array()));
    }

    /**
     * Sets configurable variables
     * @return  $this
     */
    public function setByArray()
    {
        $args = func_get_args();
        $props = get_object_vars($this);

        foreach ($args as $arr) {
            foreach ($arr as $k => $v) {
                if (array_key_exists($k, $props)) {
                    $this->{$k} = $v;
                }
            }
        }

        return $this;
    }

}
