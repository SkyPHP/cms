<?

blog::incriment_pageviews(IDE);

$r = aql::profile( 'blog_article', IDE );

$title = $r['title'];

$market = $r['market_slug']?$r['market_slug']:$_SESSION['market_slug'];
if ($r['has_markets']) redirect("/$market/{$r['blog_slug']}/".slugize($title)."/".IDE);
else redirect("/{$r['blog_slug']}/".slugize($title)."/".IDE);

// facebook sharing stuff
$description = $r['introduction'];
if (!$description) {
    $strip_content = strip_tags($r['content']);
    $description = substr( $strip_content, 0, strpos($strip_content, '.') + 1 );
}
$meta['description'] = $description;
$head_arr[]="<link rel='alternate' type='application/rss+xml' title='$blog_name $market_name RSS Feed' href='/$market_slug/$blog_slug/rss.xml' />";
$head_arr[] = '<meta name="title" content="'.$title.'" />';
$head_arr[] = '<meta name="medium" content="blog" />';
$head_js[] = 'http://maps.google.com/maps/api/js?sensor=false';
$head_js[] = '/lib/js/googlemaps.js';
$img = media::get_item($r['media_item_id'],130,110,true);
if ($img) $head_arr[] = '<link rel="image_src" href="'.$img['src'].'" / >';

if(!$sidebar)
	$sidebar = 'ad,editor,nightlife,recentblogs,blogroll';

$nav = 'blogs';
template::inc('website','top');



    $content = $r['content'];
    // add captions to photos
    $pattern = '/<img[^>]+\>/i';
    preg_match($pattern,$content,$matches);
    if ( is_array($matches) )
    foreach ($matches as $img) {
        $pattern = '#/media/([^"]+)/#';
        preg_match($pattern,$img,$matches2);
        $media_instance_ide = $matches2[1];
        if ( !is_numeric( decrypt($media_instance_ide,'media_instance') ) ) continue;
        $aql = "media_item {
                    caption,
                    credits
                }
                media_instance {
                    where media_instance.ide = $media_instance_ide
                }";
        $rs = aql::select($aql);
        $rs = $rs[0];
        if ( $rs['caption'] || $rs['credits'] ) {
            $replace = '<div class="article_image">'.str_replace('article_image','',$img);
            if ($rs['caption']) $replace .= '<div class="article_image_caption">'.$rs['caption'].'</div>';
            if ($rs['credits']) $replace .= '<div class="article_image_credits">'.$rs['credits'].'</div>';
            $replace .= '</div>';
            $content = str_replace( $img, $replace, $content );
        }
    }


include_once('blog-profile-header.php');

// default layout
include('article.php');

//include('article.php');

template::inc('website','bottom');

?>
