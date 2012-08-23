<?php

namespace VF;

/**
 * @package VF
 */
abstract class GalleryInc
{

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
        $this->setByArray(GalleryInc::$defaults, static::$defaults, $args);

        if ($args['width'] && !$args['height']) {
            $this->height = null;
        }

        if ($args['height'] && !$args['width']) {
            $this->width = null;
        }

        if (!$this->folder && !$this->items) {
            throw new Exception(
                'Gallery requires a folder or items to be set.'
            );
        }

        if (!is_object($this->folder && !$this->items)) {
            $this->initFolder();
        }

        $this->validate();
        $this->makeHTML();
    }

    /**
     * initializes the volder object by path
     */
    public function initFolder()
    {
        $path = (is_object($this->folder)) ? $this->folder->path : $this->folder;

        return $this->folder = vf::getFolder($path, array(
            'limit' => $this->limit
        ));
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
     * @return string
     */
    public function makeHTML()
    {
        if (!$this->html_include) {
            return;
        }

        $gallery = $this;

        ob_start();
        include $this->html_include;

        $this->html = ob_get_contents();
        ob_end_clean();

        return $this->html;
    }

    /**
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
