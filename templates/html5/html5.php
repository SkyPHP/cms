<?

if ( $template_area == 'top' ) {
    $this->template_js[] = '/templates/html5/cms-html5.js';
}
$skyphp_codebase_path = end($GLOBALS['codebase_path_arr']);
include( $skyphp_codebase_path . 'templates/html5/html5.php' );