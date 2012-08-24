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
     * return string
     */
    public function makeHTML()
    {
        $p = static::getPage();
        $p->css[] = '/lib/vfolder/css/vf.css';
        $p->js[] = '/lib/js/jquery.hoverIntent.js';
        $p->js[] = '/lib/vfolder/js/vf.js';

        return $this->getHTML();
    }

    public function getHTML()
    {
        $items = $this->getItemIDs();

        list($large_conf, $small_conf) = $this->getItemsConfs();

        $large = Client::getItem($items, $large_conf);
        $small = Client::getItem($items, $small_conf);

        $small[0]->class = $large[0]->class = "first selected";

        $data = array(
            'folders_path' => $this->folder->path,
            'transition' => $this->transition,
            'delay' => $this->delay,
            'autohide' => $this->auto_hide_toolbar ? 'yes' : 'no',
            'autostart' => $this->autostart ? 'autostart="true"' : '',
            'enlarge' => $this->enlarge,
            'controls' => $this->controls,
            'captions' => $this->captions,
            'main' => $large,
            'thumbs' => $small
        );

        return $this->html = static::getPage()->mustache(
            'lib/Sky/VF/mustache/slideshow.m',
            $data
        );
    }

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
