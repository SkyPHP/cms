<?

# hook some extra js and css into the html5 template
if ($template_area == 'top') {
	
	$this->css[] = '/templates/html5/cms-html5.css';	
	$this->js[] = '/templates/html5/cms-html5.js';
	$this->js[] = '/lib/js/aqlForm.js';
	
}

$skyphp_codebase_path = end($GLOBALS['codebase_path_arr']);

include $skyphp_codebase_path . 'templates/html5/html5.php';