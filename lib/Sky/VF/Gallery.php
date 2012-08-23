<?php

namespace Sky\VF;

/**
 * @package VF
 */
class Gallery extends Gallery\Inc
{

    /**
     * stores the number of times that the gallery has been used on a page
     * @var int
     */
    public static $count = 0;

    /**
     * @var array
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
     * @var Boolean
     */
    public $contextMenu;

    /**
     * @var string
     */
    public $empty_message;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string
     */
    public $_token;

    /**
     * @global $p  \Sky\Page
     */
    public function makeHTML()
    {
        $p = static::getPage();

        $p->css[] = '/lib/vfolder/css/vf.css';
        $p->js[] = '/lib/vfolder/js/vf.js';

        if ($this->contextMenu) {
            $p->css[] = '/lib/jquery.contextMenu/jquery.contextMenu.css';
            $p->js[] = '/lib/jquery.contextMenu/jquery.contextMenu.js';
        }

        if (!$this->identifier) {
            $this->identifier = 'vf-gallery-'.self::$count.'-'.md5(rand());
        }

        self::$count++;

        $this->setMemToken();
        return parent::makeHTML();
    }

    /**
     * sets the token in session for this uploader
     */
    public function setMemToken()
    {
        $vars = get_object_vars($this);
        $this->_token = md5(serialize($vars));
        $mem_key = 'vf_gallery:' . $this->_token;

        $_SESSION['vf_gallery'][$mem_key] = $vars;
    }

}
