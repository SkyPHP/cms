<?

class vf_upload_handler {
	
	public $FILES;
	public $file;
	public $POST;
	public $filename;
	public $folders_path;
	public $uploaded_file;
	public $params = array();
	public $errors = array();

	public function __construct($post = null, $file = null) {
		$this->POST = if_not($post, $_POST);
		$this->FILES = if_not($file, $_FILES);
	}

	public function setPOST($p) {
		$this->POST = $p;
	}

	public function setFILES($f) {
		$this->FILES = $f;
	}

	public function getParams() {
		$this->params = mem('vf_uploader:' . $this->POST['_token']);
		if (!$this->params) {
			$this->errors[] = 'Invalid upload token passed.';
		}
	}

	public function checkIfFoldersPathSet() {
		$folder = (is_string($this->params['folder'])) ? $this->params['folder'] : $this->params['folder']->folders_path;
		if ($folder == '/' || !$folder) $this->errors[] = 'Folders Path was not set.';
		$this->folders_path = $folder;
	}

	public function checkIfUpload() {
		if (!isset($this->FILES['file']) || !is_uploaded_file($this->FILES['file']['tmp_name']) || $this->FILES['file']['error'] != 0) {
			switch ($this->FILES['file']['error']) {
				case 1:
					$e = 'The uploaded file is greater than '.ini_get('upload_max_filesize');
					break;
				case 2: 
					$e = 'The upload size is greater than the size specified.';
					break;
				case 3:
					$e = 'The file was only partially uploaded. Please try again.';
					break;
				case 6: 
					$e = 'Internal Error: Missing Temporary Folder. Please contact the system administrator.';
					break;
				case 7:
					$e = 'Internal Error: Cannot write to disk. Please contact the system administrator.';
					break;				
				default:
					$e = 'Invalid upload.';
					break;
			}
			$this->errors[] = $e;
			return;
		}
		$this->file = $this->FILES['file'];
		if (!$this->file['tmp_name']) {
			$this->errors[] = 'No file uploaded.';
			return;
		}
		$this->filename = $this->file['name'];
		$this->sanitizeFilename();
		$this->uploaded_file = ini_get('upload_tmp_dir') .'/'. $this->filename;
		move_uploaded_file($this->file['tmp_name'], $this->uploaded_file);
	}

	public function validate() {
		$this->getParams();
		$this->checkIfFoldersPathSet();
		$this->checkIfUpload();
		$this->checkFileTypes();
	}

	public function doUpload() {
		if ($this->errors) return $this->respond();
		$re = vf::$client->upload_to_server($this->uploaded_file, array(
			'folders_path' => $this->folders_path
		));
		if (!$re['success']) {
			$this->errors[] = 'There was an error uploading the file. If it persists, contact the system administrator.';
			return $this->respond();
		}
		if ($this->params['db_field'] && $this->params['db_row_id']) {
			$this->updateDBRecord($re['items_id']);
		}
		return $this->respond($re);
	}

	public function updateDBRecord($id) {
		$dot = strpos($this->params['db_field'], '.');
		$table = substr($this->params['db_field'], 0, $dot);
		$field = substr($this->params['db_field'], $dot + 1);
		if (!$table || !$field) {
			$this->errors[] = 'There was an error updating the record for this item. Please contact your system administrator.';
			return;
		}
		aql::update(
			$table, 
			array($field => $id),
			$this->params['db_row_id']
		);
	}

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

	public function getMimeByExtension($ext) {
		global $sky_content_type;
		return $sky_content_type[$ext];
	}

	public function getExtByMime($mime) {
		global $sky_content_type;
		return array_search($mime, $sky_content_type);
	}

	public function getExtFromFilename() {
		$f = explode('.', $this->uploaded_file);
		array_filter($f);
		return end($f);
	}

	public function sanitizeFilename() {
		$arr = array('(', ')', '{', '}', ' ');
		$this->filename = str_replace($arr, '', $this->filename);
	}

	public function respond($re = null) {
		if ($this->errors) {
			return array(
				'status' => 'Error',
				'errors' => $this->errors,
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