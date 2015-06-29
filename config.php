<?php

$seo_field_array=array(
    'target'=>array(
        'title'=>75,
        'meta_description'=>55,
        'h1'=>100,
        'h1_blurb'=>300,
        'footer_blurb'=>300
    ),
    'extras'=>array(
        'meta_subject'=>200,
        'meta_title'=>75,
        'meta_keywords'=>250,
        //'ICBM'=>100,
        //'geo-position'=>100,
        //'geo-placename'=>100,
        //'geo-region'=>100,
        //'geography'=>100,
        //'zipcode'=>25,
        //'city'=>70,
        //'state'=>2,
        //'country'=>100,
        //'author'=>100,
        //'copyright'=>100
    )
    //'Open Graph'=>array(
    //  'og:title'=>100,
    //  'og:type'=>50,
    //  'og:description'=>300,
    //  'og:site_name'=>100
    //)
);

$seo_textarea = array('h1_blurb','meta_description','meta_keywords','footer_blurb');

$sky_media_src_path = '/media';

$includes[] = 'lib/krumo/class.krumo.php';

$quick_serve['media'] = 'lib/core/quick-serve/media.php';
$quick_serve['media-zip'] = 'lib/core/quick-serve/media-zip.php';

$person_encryption_key = 'set-this-variable-in-your-config-for-security-purposes';

$vfolder_path = 'https://api.vfolder.net'; // http://localdev.vfolder.com

$vfolder_base_url = "https://api.vfolder.net/photos/";