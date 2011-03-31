<?
$logo = media::get_item($r['inverse__media_item_id'],150);

$header_height = 93;

$primary_table = 'blog_article';

$blog_article_id = $r['blog_article_id'];
$blog_article_ide = encrypt($blog_article_id,'blog_article');

$input = $r['title'];
$output = trim(ereg_replace(' +',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));
$output = str_replace(' ','-',$output);
$permalink = $output;


?>

<div class="blog-profile-header">
    <a href="/<?=$r['blog_slug']?>" class='header-logo'><img src='<?=$logo['src']?>'></a>
  <?
     if($next_blog_ide || $previous_blog_ide){
        $output = "<div class='header-nav'>";
        $output.=($previous_blog_ide?"<a href='/$market_slug/$blog_slug/$previous_blog_ide'>&laquo Previous Post</a>":"");
        $output.=($previous_blog_ide && $next_blog_ide)?" | ":"";
        $output.=($next_blog_ide?"<a href='/$market_slug/$blog_slug/$next_blog_ide'>Next Post &raquo</a>":"");
        $output.="</div>";

        echo $output;
     }
  ?>

</div>


