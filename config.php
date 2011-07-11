<?
$seo_field_array=array(
	'html'=>array(
		'title'=>69,
		'h1'=>100,
		'h1_blurb'=>300
	),
	'meta'=>array(
		'meta_title'=>100,
		'meta_description'=>300,
		'meta_keywords'=>100,
		'ICBM'=>100,
		'geo-position'=>100,
		'geo-placename'=>100,
		'geo-region'=>100,
		'geography'=>100,
		'zipcode'=>10,
		'city'=>70,
		'state'=>2,
		'country'=>100,
		'subject'=>100,
		'author'=>100,
		'copyright'=>100
	)
	//'Open Graph'=>array(
	//	'og:title'=>100,
	//	'og:type'=>50,
	//	'og:description'=>300,
	//	'og:site_name'=>100
	//)
	
	
);

$sky_media_src_path = '/media';

$includes[] = 'lib/core/class.media.php';
$includes[] = 'lib/core/class.snippet.php';
$includes[] = 'lib/core/class.pagination.php';
$includes[] = 'lib/core/class.validation.php';
$includes[] = 'lib/krumo/class.krumo.php';

$quick_serve['media'] = 'lib/core/quick-serve/media.php';
$quick_serve['media-zip'] = 'lib/core/quick-serve/media-zip.php';