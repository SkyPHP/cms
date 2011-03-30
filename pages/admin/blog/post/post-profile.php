<?
$model = 'blog_article';
$blog_article = aql::profile($model,IDE);
$primary_table = aql::get_primary_table($model);
$blog_article_id = $blog_article['blog_article_id'];
$blog_article_ide = $blog_article['blog_article_ide'];

$title = $blog_article['title']?$blog_article['title']:'Write A New Post';
$head_arr[] = "<script src = '/lib/swfupload/swfupload.js' type = 'text/javascript'></script>";
$head_arr[] = "<script src = '/modules/media/upload/handlers.js' type = 'text/javascript'></script>";
$head_arr[] = "<link type='text/css' rel='stylesheet' href='/modules/media/upload/progress.css' />";
template::inc('intranet','top');
?>
	<div id = "back_to_blogs"><a href = "/admin/blog/post/">&laquo; Back to Blog Posts</a></div>
<?
//$theme_ab1 = "undo,redo,|,bold,italic,underline,strikethrough,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect,styleselect";
//$theme_ab2 = "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,sub,sup,charmap,|,link,unlink,anchor,image,cleanup,help,|,code,|,removeformat,|,fullscreen,|,preview";
$theme_ab1 = "undo,redo,|,bold,italic,underline,strikethrough,forecolor,backcolor,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect";
$theme_ab2 = "cut,copy,paste,pastetext,pasteword,|,outdent,indent,blockquote,|,sub,sup,charmap,|,link,unlink,image,cleanup,|,code,|,removeformat,|,fullscreen,|,hr";

if (!$css_blog_editor) {
	$css_blog_editor = '/css/blog.css';
}
$options_article = 	array(
	'resizing' => false,
    'full_url' => ( $blog_img_absolute ? true : false ),
	'theme' => 'advanced',
	'multi_buttons' => true,
	'theme_ab1' => $theme_ab1,
	'theme_ab2' => $theme_ab2,
	'theme_ab3' => "",
	'theme_ab4' => "",
    'width' => '675',
	'height' => '520',
	'css' => $css_blog_editor //'/pages/admin/blog/post/html_editor.css' //old css
);
$theme_ab1_notes = "bold,italic,underline,strikethrough,forecolor,backcolor,hr";
$options_notes = 	array(
	'resizing' => true,
	'theme' => 'advanced',
	'multi_buttons' => true,
	'theme_ab1' => $theme_ab1_notes,
	'theme_ab2' => '',
	'theme_ab3' => "",
	'theme_ab4' => "",
	'height' => '120',
	'width'=>'257'
);
$settings = array(
	'article-content' => $options_article,
	'note'=>$options_notes
);
helper::tinymce($settings);

aql::form($model);

template::inc('intranet','bottom');
?>
