<?
$blog_article_ide = IDE;

$r = aql::profile('blog_article',$blog_article_ide);
$title = $r['title'];
template::inc('website','top');
include('components/blog_article/id/article.php');
template::inc('website','bottom');
?>