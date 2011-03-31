<?

$aql = "blog {
            has_markets
            where id = $blog_id
        }";
$rs = aql::select($aql);
$blog_has_markets = $rs[0]['has_markets'];
if ( $blog_has_markets ) redirect( '/'.$_SESSION['market_slug'].$_SERVER['REQUEST_URI'] );




$aql = "blog {
			name,
            slug,
            poll_embed_code,
			page_title,
            logo__media_item_id,
            inverse__media_item_id
            where blog.id = $blog_id
		}
        blog_website {
            where website_id = 1
        }";
$b = aql::select($aql);
$b = $b[0];

$logo = media::get_item($b['logo__media_item_id'],250);

$title = $b['page_title'];
$nav = 'blogs';
template::inc('website','top');

if($logo['img']){
?>
<div class="divider"></div>
<div class="blog-header">
    <?=$logo['img']?>
</div>
<div class="divider"></div>
<?
}

$where = array(
    "blog_article.blog_id = $blog_id"
);

?>
<div class='scroll-pane' style='display:none;'>
<?
blog::listing( $where, 10, 0, NULL, NULL);
?>
</div>

<? #to work-around the disqus/jScrollPane conflict -- See skyphp/lib/class/class.blog.php:function listing for more details
include_once('components/disqus/comment_count/comment_count.php');?>

<? /* this prevents the scrollpane from 'jumping' when the page first loads */ ?>
<script type='text/javascript'>
   $(document).ready(function(){$('.scroll-pane').css('display', 'inline');});
</script>

<?

template::inc('website','bottom');
?>
