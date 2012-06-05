<?php

if (!class_exists('vf_gallery_inc')) {
    include 'lib/vfolder/class.vf_gallery_inc.php';
}

class vf_gallery extends vf_gallery_inc {

    /**
     *  stores the number of times that the gallery has been used on a page
     *  @var int
     */
    public static $count = 0;

    /**
     *  @var array
     */
    public static $defaults = array(
        'html_include' => 'pages/ajax/vf/gallery.php',
        'contextMenu' => true,
        'fileName' => false,
        'width' => 100,
        'height' => 100,
        'empty_message' => 'There are no images.'
    );

    /**
     *  @var Boolean
     */
    public $contextMenu;

    /**
     *  @var string
     */
    public $empty_message;

    /**
     *  @var string
     */
    public $identifier;

    /**
     *  @var string
     */
    public $_token;

    /**
     *  @global $p  \Sky\Page
     */
    public function makeHTML() {

        global $p;

        if (!$this->identifier) {
            $this->identifier = 'vf-gallery-'.self::$count.'-'.md5(rand());
        }

        self::$count++;

        $this->setMemToken();
        parent::makeHTML();

        if (!in_array('/lib/vfolder/css/vf.css', $p->css)) {
            $p->css[] = '/lib/vfolder/css/vf.css';
        }
        if (!in_array('/lib/vfolder/js/vf.js', $p->js)) {
            $p->js[] = '/lib/vfolder/js/vf.js';
        }

        if ($this->contextMenu) {
            $p->css[] = '/lib/jquery.contextMenu/jquery.contextMenu.css';
            $p->js[] = '/lib/jquery.contextMenu/jquery.contextMenu.js';
        }
    }

    /**
     *  sets the token in memcache for this uploader
     */
    public function setMemToken() {
        $vars = get_object_vars($this);
        $this->_token = md5(serialize($vars));
        $mem_key = 'vf_gallery:' . $this->_token;
        mem($mem_key, $vars);
    }

}
