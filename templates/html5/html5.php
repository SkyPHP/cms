<?

// hook some extra js and css into the html5 template
if ( $template_area == 'top' ) {
    $this->template_js[] = '/templates/html5/cms-html5.js';
    $this->template_css[] = '/templates/html5/cms-html5.css';
    //contextMenu
    $this->template_css[] = '/lib/jquery.contextMenu/jquery.contextMenu.css';
    $this->template_js[] = '/lib/jquery.contextMenu/jquery.contextMenu.js';
    //tinyMCE
    $this->template_js[] = '/lib/tiny_mce/jquery.tinymce.js';
    //saveForm
    $this->template_js[] = '/lib/js/save_form.js';
    //plupload
    $this->template_js[] = '/lib/plupload/js/plupload.full.js';


	// SEO INCLUDE
	include ('pages/seo.php');
}
$skyphp_codebase_path = end($GLOBALS['codebase_path_arr']);
include( $skyphp_codebase_path . 'templates/html5/html5.php' );