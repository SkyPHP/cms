<?php

namespace Sky\VF;

/**
 * @package SkyPHP
 */
class Slideshow extends Gallery\Inc
{
    /**
     * @var array
     */
    public static $defaults = array(
        'crop' => 'center',
        'autostart' => true,
        'delay' => 5000,
        'html_include' => 'pages/ajax/vf/slideshow.php',
        'thumb_type' => 'slide',
        'transition' => 'slide',
        'auto_hide_toolbar' => true,
        'captions' => true,
        'controls' => true
    );

    /**
     * @var array
     */
    public static $thumb_types = array(
        'slide',
        'grid',
        'none'
    );

    /**
     * @var array
     */
    public static $transition_types = array(
        'slide',
        'fade'
    );

    /**
     @var Boolean
     */
    public $autostart;

    /**
     @var Boolean
     */
    public $auto_hide_toolbar;

    /**
     @var Boolean
     */
    public $captions;

    /**
     @var Boolean
     */
    public $controls;

    /**
     @var int
     */
    public $delay;

    /**
     @var string
     */
    public $thumb_type;

    /**
     @var string
     */
    public $transition;

    /**
     * Adds js/css to the page object, and generates the slideshow html
     * returns html string and sets $this->html
     * @return  string
     */
    public function getHTML()
    {
        static::addPageResources(array(
            'js' => '/lib/js/jquery.hoverIntent.js'
        ));

        list($large_conf, $small_conf) = $this->getItemsConfs();

        $items = $this->getItemIDs();

        $large = Client::getItems($items, $large_conf);
        $small = Client::getItems($items, $small_conf);

        if (!is_array($small)) return;

        $small[0]->class = $large[0]->class = "first selected";

        $data = array(
            'folders_path' => $this->folder->path,
            'transition' => $this->transition,
            'delay' => $this->delay,
            'autohide' => $this->auto_hide_toolbar ? 'yes' : 'no',
            'autostart' => $this->autostart ? 'true' : 'false',
            'enlarge' => $this->enlarge,
            'controls' => $this->controls,
            'captions' => $this->captions,
            'main' => $large,
            'thumbs' => $small,
            'width' => $this->width,
            'height' => $this->height
        );

        return $this->html = static::getMustache('slideshow', $data);
    }

    /**
     * Gets the params for getItem
     * @return array
     */
    protected function getItemsConfs()
    {
        $large = array(
            'width' => $this->width,
            'height' => $this->height,
            'crop' => $this->crop
        );

        $small = array_merge($large, array(
            'width' => $this->thumb_width,
            'height' => $this->thumb_height
        ));

        return array($large, $small);
    }

    /**
     * Makes sure we meet requirements
     */
    public function validate()
    {
        if ($this->crop || $this->width || $this->height) {
            return;
        }

        throw new \Exception(
            'Slideshow expects a width or a height to be provided if crop is set to false.'
        );
    }


}
