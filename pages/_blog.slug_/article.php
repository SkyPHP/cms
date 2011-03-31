<?
	$blog_editor = auth('blog_author:editor');
	$admin = auth('admin:*');
	$r['slideshow_vfolder'] = '/blog/blog_article/'.$r['blog_article_id'].'/slideshow';
?>
<div class="blog-post">
    <div class="blog-post-header">
        <div class="blog-post-title"><?=$r['title']?></div>
        <div class="blog-post-intro"><?=$r['introduction']?></div>
        <div class="blog-post-info">
            <?=date('l, F jS, Y \a\t g:i a',strtotime($r['post_time']))?>
            | <?=($r['username']?"<a href='/{$r['username']}' >":"").ucwords(strtolower($r['fname']." ".$r['lname'])).($r['username']?"</a>":"")?>
<?
            if ( $blog_editor || auth('blog_author') ) {
?>
                | <?=$r['pageviews']?> Page Views | <a href="/admin/blog/post/<?=$r['blog_article_ide']?>">Edit this article</a>
<?
            }//if
			if( $blog_editor || $admin ){
?>
				| <a href = "/admin/email/newsletter/add-new?blog_article_ide=<?=$blog_article_ide ?>">Use this article in newsletter</a>
<?
			}
?>
        </div><? if(auth('developer')){ ?>
        <div class='blog_post_info'>
           blog_article.id = <?=$r['blog_article_id']?> | blog_author.person_id = <?=$r['author__person_id']?> | blog.id = <?=$r['blog_id']?>
        </div><?} ?>
    </div>
    <div id="blog-post-content" class="blog-post-content">
       <?=$content?>
    </div>
</div>

<?
include('youtube_player.php');
if($r['slideshow_vfolder']) {
	?>
	<style>
	.skybox-close-button{
	  background-color:#000000;
	  font-weight:bold;
	  padding-bottom:3px;
	  padding-right:3px;
	  text-align:right;
	}
	</style>
	<input type="hidden" id="album_skybox" value="true" />
	<div class="blog-post-footer">
		<input type="hidden" id="market_slug" value="<?=$market_slug?>" />
		<?
		$media_vfolder = media::get_vfolder($r['slideshow_vfolder']);
		$album['media_vfolder_ide'] = $r['slideshow_vfolder'];
		if (count($media_vfolder["items"])) {
			define('PHOTO_AJAX','pages/_market.slug_/photos/ajax/');
			define('PHOTO_INCLUDE','pages/_market.slug_/photos/includes/');
			$photostrip_visible = 7;
			$photostrip_item_size = 75;
			$hide_name = true;
			$album_skybox = true;
			include(PHOTO_INCLUDE.'album.php');
		}
		?>
	</div>
	<?
}



?>
<div class="blog-post-footer">
<?
    $googlemaps_divid = "rightbar_googlemaps";
    $googlemaps = "";
    if ($r['venue_id']) {
        $aql = "
           venue{
              name as venue_name, address1, city, state, zip, name_modifier as venue_name_mod
           }
           venue_type{
              slug as venue_type_slug
              where venue.id={$r['venue_id']}
              limit 1
           }
           market{
              venue_search_nickname
           }
        ";

        $vrs = aql::select($aql);

        if(is_array($vrs)){$vrs=$vrs[0];}

        $venue_type_slug = $vrs['venue_type_slug']?$vrs['venue_type_slug']:'clubs';
        $venue_search_nickname = $vrs['venue_search_nickname']?$vrs['venue_type_slug']:'NYC';

        $address_hyperlink = "<a href='/$market_slug/$venue_type_slug/".slugize($vrs['venue_name']).'-'.$venue_search_nickname."/{$vrs['venue_ide']}' class='blog-post-footer-venue-name' >{$vrs['venue_name']}</a>";

        $address = "{$vrs['address1']} {$vrs['city']} {$vrs['state']} {$vrs['zip']}";
        $address_for_html = "{$address_hyperlink}\n<br>".($vrs['venue_name_mod']?$vrs['venue_name_mod']."\n<br>":"")."{$vrs['address1']}\n<br>{$vrs['city']}, {$vrs['state']} {$vrs['zip']}";
        //     $post_after.=togooglemaps(array('id'=>'main-blog-image','address'=>$address));
        $args = array(
         "address"=>$address,
         "id"=>$googlemaps_divid,
         "class"=>($extra_class?"bottom":""),
         "mapoptions"=>array(
            'mapTypeControl'=>'false',
            'mapTypeId'=>'google.maps.MapTypeId.TERRAIN',
            'navigationControl'=>'true',
            'navigationControlOptions'=>array(
               'style'=>'google.maps.NavigationControlStyle.SMALL'
            ),
            'zoom'=>13
         )
        );
        $address_for_css = preg_replace('#\s+#',' ',$address);
        $address_for_css = preg_replace('#\s#','-',$address_for_css);
        $address_for_css = preg_replace('#,#','--',$address_for_css);

?>
        <div class="blog-post-map">
            <?=googlemaps($args)?>
            <div class='blog-post-map-address'><?=$address_for_html?></div>
        </div>
<?
    }//if map
?>

    <div class="blog-post-footer-items <?=$r['venue_id']?'':'long'?>">
       <? if($r['slideshow_vfolder'] && false){ ?>
             <div class='blog-post-slideshow blog-post-footer-item'>
                <a href='javascript:skybox("/<?=$market_slug?>/<?=$blog_slug?>/slideshow",{vfolder:"<?=$r["slideshow_vfolder"]?>"});'>View Slide Show</a>
             </div>
       <? } ?>


<?
if ($GLOBALS['tiny_domain']) {
?>
        <div class="blog-post-permalink blog-post-footer-item blog-post-footer-item">
            Permalink: <a href="<?=tinyurl('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>"><?=tinyurl('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?></a>
        </div>
<?
}//if
?>

        <div class="blog-post-tags blog-post-footer-item <?=$r['bylines']?"":"last"?>">
    <?
            $aql = "blog_article_tag {
                        name
                        where blog_article_tag.blog_article_id = {$r['blog_article_id']}
                        order by blog_article_tag.iorder asc
                    }";
            $tags = aql::select($aql);
            $first = true;
            if (is_array($tags))
            foreach ($tags as $tag) {
                if ($first) echo 'Tags: ';
                else echo ', ';
                $first = false;
                ?><a href="/tag/<?=strtolower(str_replace(' ','+',$tag['name']))?>"><?=strtoupper($tag['name'])?></a><?
            }//foreach tags
    ?>
        </div>

        <? if($r['bylines']){ ?>
        <div class="blog-post-bylines">
           <?=$r['bylines']?>
        </div>
        <? } ?>
    </div>
</div>


<?
/*
?>
<!-- ad banner -->
<div style="width:620px; background-color:#ddd; padding:10px; text-align:center;">
    <iframe id='a41cb7db' name='a41cb7db' src='http://ads.joonbug.com/www/delivery/afr.php?n=a41cb7db&amp;zoneid=3&amp;cb={random}&amp;ct0={clickurl}' frameborder='0' scrolling='no' width='300' height='250' allowtransparency='true'><a href='http://ads.joonbug.com/www/delivery/ck.php?n=a5d3f2f3&amp;cb={random}' target='_blank'><img src='http://ads.joonbug.com/www/delivery/avw.php?zoneid=3&amp;cb={random}&amp;n=a5d3f2f3&amp;ct0={clickurl}' border='0' alt='' /></a></iframe>
    <script type='text/javascript' src='http://ads.joonbug.com/www/delivery/ag.php'></script>
</div>
<?
*/
?>


<div id='under-blog-post'>

    <? $disqus_identifier = $r['blog_article_ide'];
       include('components/disqus/thread/thread.php');
    ?>

</div>

<?=$post_after ?>
