<?

define('INC_PATH','pages/tag/');

$sidebar = 'ad,social-media,recentblogs';
$head_arr[] = '<link type="text/css" rel="stylesheet" href="/pages/_market.slug_/_blog.slug_/_blog.slug_.css" />';
$title = "";

//query can come from a form post or url, check which here and redirect if necesary
if($_POST['q']){
   $current_tag = $_POST['q'];
   $current_tag = preg_replace('#\s#','+',$current_tag);
   redirect("/tag/$current_tag".($_GET['qty']?"&qty=".$_GET['qty']:""));
}else{
   $current_tag = $_POST['sky_qs'][0];
}

$current_tag_pre = $current_tag; //stores unparsed tag value, for url construction later
$current_tag_nice = preg_replace('#\+#',' ',$current_tag); //stores 'nice' representation of the query, for output later
$current_tag_nice = urldecode($current_tag_nice);

$title = strtoupper($current_tag_nice);
if ( $website_name ) $title .= " on $website_name";

if(!$current_tag || strtolower($current_tag)=='invalid+tag'){redirect('/');}

template::inc('website','top');

$default_limit = 10;
$limit = $_GET['qty']?$_GET['qty']:$default_limit;
$num_per_page = $limit;
$page_number = $_GET['page']?$_GET['page']-1:0;
$offset = $limit*($page_number);
$limit++;

?>

<h1 class='jb-h1'>
<?=strtoupper($current_tag_nice)?>
</h1>

<?

/*
//search bar
echo "<div id='tag-classes'>";


$form_action_uri = "/tag".($limit!=$default_limit?"?qty=$limit":"");
?>
   <form name='tag' method='post' action='<?=$form_action_uri?>'>
      <div id='tag-form'>
         <input id='tag-form-text' type='text' name='q' value='<?=$current_tag_nice?$current_tag_nice:"Enter Tag"?>' onfocus='textbox_focus(event);' onblur='textbox_blur(event);' />
         <input id='tag-form-submit' type='submit' value='Go' />
      </div>
   </form>
<?

  //if

echo "</div> <div class='clear'></div>";

*/

if(!$current_tag){
   
}else{

   $current_tag = strtolower($current_tag_nice); //nice tag retains capitalization
   $current_tag = preg_replace('#[^a-zA-Z0-9_ ]#','%',$current_tag); 

   include(INC_PATH.'media_item_mini.php');
   include(INC_PATH.'blog_article.php');

}
?>

<script type="text/javascript">
    //<![CDATA[
    (function() {
        var links = document.getElementsByTagName('a');
        var query = '?';
        for(var i = 0; i < links.length; i++) {
        if(links[i].href.indexOf('#disqus_thread') >= 0) {
            query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
        }
        }
        document.write('<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/joonbug/get_num_replies.js' + query + '"></' + 'script>');
    })();
    //]]>
    </script>
 <?

 $pagination = ($page_number!=0?($new_pag=("<a href='?page=".($page_number)."'>&laquo; Newer Posts</a>"))." | ":"").(!($last_page)?($old_pag=("<a href='?page=".($page_number+2)."'>Older Posts &raquo;</a>")):"");
   ?>
      <div class='blog-foot-pagination new'>
         <?=$new_pag ?>
      </div>
      <div class='blog-foot-pagination old'>
         <?=$old_pag ?>
      </div>
      <div class='clear'></div>
   <?



template::inc('website','bottom');

//below for disqus comment counts
?>
