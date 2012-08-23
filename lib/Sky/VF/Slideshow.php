<?php

namespace Sky\VF;

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

        return parent::makeHTML();
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
