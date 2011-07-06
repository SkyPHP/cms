<?
$seo_field_array=array(
	'meta'=>array(
		'meta_title',
		'meta_description',
		'meta_keywords',
	),
	'html'=>array(
		'title',
		'h1',
		'paragraph'
	),
	'og'=>array(
		'og:title',
		'og:type',
		'og:url',
		'og:image',
		'og:description',
		'og:site_name'
	)
	
);
$sky_media_src_path = '/media';

$includes[] = 'lib/core/class.media.php';
$includes[] = 'lib/core/class.snippet.php';
$includes[] = 'lib/core/class.pagination.php';
$includes[] = 'lib/core/class.validation.php';
$includes[] = 'lib/krumo/class.krumo.php';

$quick_serve['media'] = 'lib/core/quick-serve/media.php';
$quick_serve['media-zip'] = 'lib/core/quick-serve/media-zip.php';