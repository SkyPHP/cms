<?

# hook some extra js and css into the html5 template

if ($template_area == 'top') {
	
	$css = array(
		'/templates/html5/cms-html5.css'
	);

	$js = array(
		'/templates/html5/cms-html5.js',
		'/lib/js/aqlForm.js'
	);

	# make sure that these files are the first ones loaded
	$this->template_css = array_merge($this->template_css, $css);
	$this->template_js = array_merge($this->template_js, $js);

}

$skyphp_codebase_path = end($GLOBALS['codebase_path_arr']);

include $skyphp_codebase_path . 'templates/html5/html5.php';