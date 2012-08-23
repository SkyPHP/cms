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
     *
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

        return $this->getHTML();
    }

    public function getHTML()
    {
        $items = $this->getItemIDs();

        $params = array(
            'width' => $this->width,
            'height' => $this->height,
            'crop' => $this->crop
        );

        $pars = array(
            'id' => $this->identifier,
            'empty' => !$items,
            'list' => $items ? Client::getItem($items, $params) : null,
            'context_menu' => $this->contextMenu ? 'context_menu="true"' : null,
            'folders_path' => $this->folder->path,
            'token' => $this->_token
        );

        return $this->html = static::getPage()->mustache(
            'lib/Sky/VF/mustache/gallery.m', $pars
        );
    }

    /**
     * sets the token in session for this uploader
     */
    public function setMemToken()
    {
        $vars = get_object_vars($this);
        $this->_token = md5(serialize($vars));

        $_SESSION['VF']['gallery'][$this->_token] = $vars;
    }

}
