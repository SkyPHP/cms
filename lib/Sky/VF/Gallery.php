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
     * Adds js/css to the page object, and generates a gallery of the items
     * returns html string and sets $this->html
     * @return  string
     */
    public function getHTML()
    {
        if ($this->contextMenu) {
            static::addPageResources(array(
                'css' => '/lib/jquery.contextMenu/jquery.contextMenu.css',
                'js' => '/lib/jquery.contextMenu/jquery.contextMenu.js'
            ));
        }

        if (!$this->identifier) {
            $this->identifier = 'vf-gallery-'.self::$count.'-'.md5(rand());
        }

        self::$count++;
        $this->setMemToken();

        $items = $this->getItemIDs();

        $params = array(
            'width' => $this->width,
            'height' => $this->height,
            'crop' => $this->crop
        );

        $list = $items ? Client::getItems($items, $params) : null;

        $data = array(
            'id' => $this->identifier,
            'empty' => !$list,
            'empty_message' => $this->empty_message,
            'list' => $list,
            'context_menu' => $this->contextMenu ? 'context_menu="true"' : null,
            'folders_path' => $this->folder->path,
            'token' => $this->_token
        );

        return $this->html = static::getMustache('gallery', $data);
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
