<?php

namespace Sky\VF;

/**
 * @package SkyPHP
 */
class Uploader extends Gallery\Inc
{

    /**
     * default values
     * @var array
     */
    public static $defaults = array(
        'buttonText' => 'Upload File(s)'
    );

    /**
     * id for the button
     * @var string
     */
    public $id;

    /**
     * button html
     * @var string
     */
    public $button;

    /**
     * text that will be the button label
     * @var string
     */
    public $buttonText;

    /**
     * user added button class
     * @var string
     */
    public $class;

    /**
     * db field to update ex: table.field_id
     * @var string
     */
    public $db_field;

    /**
     * row identifier for db_field
     * @var int
     */
    public $db_row_id;

    /**
     * allowable to be uploaded
     * @var array
     */
    public $fileTypes = array();

    /**
     * @var vf_gallery | null
     */
    public $gallery;

    /**
     * @var string
     */
    public $maxFileSize;

    /**
     * @var stdClass
     */
    public $folder;

    /**
     * @var string
     */
    public $folders_path;

    /**
     * @var string
     */
    public $_token = null;

    public function __construct(array $args = array())
    {
        $this->setByArray(static::$defaults, $args);

        if ($this->gallery && !$this->folder) {
            $this->folder = $this->gallery->folder;
        }

        $this->setMemToken();

        if (!$this->folder) {
            throw new \Exception('Uploader requires a folder.');
        }

        if (!is_object($this->folder)) {
            $this->folder = Client::getFolder($this->folder);
        }

        if (!$this->folder->path) {
            throw new \Exception('Could not get folder object from server.');
        }

        $this->makeHTML();
    }

    public function getHTML()
    {
        $pars = array(
            'refresh_gallery' => $this->gallery ? $this->gallery->identifier : null,
            'folders_path' => $this->folder->path,
            'id' => $this->id,
            'button_text' => $this->buttonText,
            'uploader_token' => $this->_token,
            'class' => $this->class
        );

        return $this->button = static::getPage()->mustache(
            'lib/Sky/VF/mustache/button.m',
            $pars
        );
    }

    public function makeHTML()
    {
        $p = static::getPage();
        $p->js[] = '/lib/plupload/js/plupload.full.js';
        $p->css[] = '/lib/vfolder/css/vf.css';
        $p->js[] = '/lib/vfolder/js/vf.js';

        return $this->getHTML();
    }

    public function setMemToken()
    {
        $vars = get_object_vars($this);
        $this->_token = md5(serialize($vars));

        $_SESSION['VF']['uploader'][$this->_token] = $vars;
    }

}
