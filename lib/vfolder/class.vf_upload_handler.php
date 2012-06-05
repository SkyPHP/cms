<?php

class vf_upload_handler {

    /**
     *  @var array
     */
    public $POST = array();

    /**
     *  @var array
     */
    public $FILES = array();

    /**
     *  @var array
     */
    public $file = array();

    /**
     *  @var string
     */
    public $filename = '';

    /**
     *  @var string
     */
    public $folders_path = '';

    /**
     *  @var string
     */
    public $uploaded_file;

    /**
     *  @var string
     */
    public $upload_dir = '';

     /**
     *  @var array
     */
    public $params = array();

     /**
     *  @var array
     */
    public $errors = array();

    /**
     *  @param  array   $post
     *  @param  array   $file
     */
    public function __construct(array $post = array(), array $file = array()) {
        $this->POST = ($post) ?: $_POST;
        $this->FILES = ($file) ?: $_FILES;
    }

    /**
     *  @param  array   $p
     */
    public function setPOST(array $p) {
        $this->POST = $p;
    }

    /**
     *  @param  array   $f
     */
    public function setFILES(array $f) {
        $this->FILES = $f;
    }

    /**
     *  @return array
     */
    public function getParams() {
        $this->params = mem('vf_uploader:' . $this->POST['_token']);
        if (!$this->params) {
            $this->errors[] = 'Invalid upload token passed.';
        }
        return $this->params;
    }

    /**
     *  checks if we have a folders_path to continue with the upload
     *  and sets it
     */
    public function checkIfFoldersPathSet() {
        $folder = (is_string($this->params['folder']))
            ? $this->params['folder']
            : $this->params['folder']->folders_path;

        if ($folder == '/' || !$folder) {
            $this->errors[] = 'Folders Path was not set.';
        }

        $this->folders_path = $folder;
    }

    /**
     *  @param  int $code
     *  @return string
     */
    public static function errorCodeHandler($code) {
        $codes = array(
            1 => 'The uploaded file is greater than ' . ini_get('upload_max_filesize'),
            2 => 'The upload size is greater than the size specified.',
            3 => 'The file was only parially uploaded. Please try again.',
            6 => 'Internal Error: Missing Temporary Folder.',
            7 => 'Internal Error: Cannot write to disk.'
        );
        $def = 'Could not upload file. Error unknown. Invalid Upload.';
        return ($codes[$code]) ?: $def;
    }

    /**
     *  checks if we are actually handling an upload, with FILES
     *  sets errors based on error code
     */
    public function checkIfUpload() {

        if (!isset($this->FILES['file'])
            || !is_uploaded_file($this->FILES['file']['tmp_name'])
            || $this->FILES['file']['error'] != 0)
        {
            $this->errors[] = static::errorCodeHandler($this->FILES['file']['error']);
            return;
        }

        $this->file = $this->FILES['file'];
        if (!$this->file['tmp_name']) {
            $this->errors[] = 'No file uploaded.';
            return;
        }

        $this->checkUploadDirectory();

        if ($this->errors) return;

        $tmp_dir = ini_get('upload_tmp_dir');

        if (!$tmp_dir) {
            $this->errors[] = 'Internal Error: ' .
                'The temporary upload directory was not defined. '.
                'Please contact the system administrator.';
            return;
        }

        $this->filename = $this->file['name'];
        $this->sanitizeFilename();
        $this->uploaded_file = $this->upload_dir .'/'. $this->filename;
        if (!move_uploaded_file($this->file['tmp_name'], $this->uploaded_file)) {
            $this->errors[] = 'Error moving file to upload directory. ' .
                'Please try again. ' .
                'If the problem persists, contact the system administrator.';
        }
    }

    /**
     *  makes sure that the upload directory is set and is writeable
     */
    public function checkUploadDirectory() {
        $tmp_dir = ini_get('upload_tmp_dir');
        if (!$tmp_dir) {
            $this->errors[] = 'Internal Error: ' .
                'Temporary upload directory not defined. '.
                'Please contact the system administrator.';
            return;
        }
        if (!is_dir($tmp_dir)) {
            $this->errors[] = 'Internal Error: '.
                'Temporary upload directory not a directory. '.
                'Please contact the system administrator.';
            return;
        }
        if (!is_writable($tmp_dir)) {
            $this->errors[] = 'Internal Error: '.
                'Temporary upload directory is not writable. '.
                'Please contact the system administrator.';
            return;
        }
        $this->upload_dir = $tmp_dir;
    }

    /**
     *  runs check* methods
     */
    public function validate() {
        $this->getParams();
        $this->checkIfFoldersPathSet();
        $this->checkIfUpload();
        $this->checkFileTypes();
    }

    /**
     *  does the upload if there are no errors, and uploads the db_field if necessary
     *  @return array   response array
     */
    public function doUpload() {

        if ($this->errors) {
            return $this->respond();
        }

        $upload_opts = array(
            'folders_path' => $this->folders_path
        );

        $re = vf::$client->upload_to_server($this->uploaded_file, $upload_opts);

        unlink($this->uploaded_file); // delete file from tmpdir

        if (!$re['success']) {
            $this->errors[] = 'There was an error uploading the file:';
            $this->errors[] = $re['last_error'];
            return $this->respond($re);
        }

        if ($this->params['db_field'] && $this->params['db_row_id']) {
            $this->updateDBRecord($re['items_id']);
        }

        return $this->respond($re);
    }

    /**
     *  @param  int $id
     */
    public function updateDBRecord($id) {

        // split db_field
        $dot = strpos($this->params['db_field'], '.');
        $table = substr($this->params['db_field'], 0, $dot);
        $field = substr($this->params['db_field'], $dot + 1);

        if (!$table || !$field) {
            $this->errors[] = 'There was an error updating the record for this item.
                Please contact your system administrator.';
            return;
        }

        aql::update(
            $table,
            array($field => $id),
            $this->params['db_row_id']
        );

    }

    /**
     *  makes sure the file type / extension is valid using a couple of methods
     */
    public function checkFileTypes() {
        if ($this->errors) return;
        if (!$this->params['fileTypes']) return;
        $ext = $this->getExtFromFilename();
        if (function_exists('finfo_open') && function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $this->uploaded_file);
        }
        if (!$mime) $mime = $this->getMimeByExtension($ext);
        $in_filetypes = false;
        $this->params['fileTypes'] = array_filter($this->params['fileTypes']);
        if ($mime) foreach ($this->params['fileTypes'] as $v) {
            $v = $this->getMimeByExtension($v);
            if ($v != $mime) continue;
            $in_filetypes = true;
            break;
        }
        if (!$in_filetypes) {
            $this->errors[] = 'You cannot upload type: <strong>'.$ext.'</strong>';
        }
    }

    /**
     *  returns mime type for the extension
     *  @param  string  $ext
     *  @return string
     */
    public function getMimeByExtension($ext) {
        $contents = self::getContentTypes();
        return $contents[$ext];
    }

    /**
     *  @param  string  $mime
     *  @return string
     */
    public function getExtByMime($mime) {
        return array_search($mime, self::getContentTypes());
    }

    /**
     *  @return array
     *  @global $sky_content_type
     */
    public static function getContentTypes() {
        global $sky_content_type;
        return $sky_content_type;
    }

    /**
     *  gets the extension from the file
     *  @return string
     */
    public function getExtFromFilename() {
        $f = explode('.', $this->uploaded_file);
        array_filter($f);
        return end($f);
    }

    /**
     *  removes parentheses, braces and spaces from filename
     */
    public function sanitizeFilename() {
        $arr = array('(', ')', '{', '}', ' ');
        $this->filename = str_replace($arr, '', $this->filename);
    }

    /**
     *  @param  mixed   $re
     *  @return array
     */
    public function respond($re = null) {
        if ($this->errors) {
            return array(
                'status' => 'Error',
                'errors' => $this->errors,
                're' => $re,
                'params' => $this->params
            );
        } else {
            return array(
                'status' => 'OK',
                'res' => $re,
                'params' => $this->params
            );
        }
    }

}
