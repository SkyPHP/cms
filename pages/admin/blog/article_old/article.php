<?php

$no_sidebar = true;
$template_area = 'top';
include('templates/intranet/intranet.php');

// identify the fields that this profile page is allowed to insert/update
// add_field('formElementId','database field name','control type');
// add_field('article_title','blog_article.title','text');
// automatic to_char
// add-another compatible
// multi-table
// validation tool saves validation settings to database

$x['aql'] = "blog_article:	*,
							title as article_title 			/* text */,
							introduction as introduction	/* text */,
							content as article_content 		/* tinymce */,
							status as article_status		/* text */,
							blog_id							/* text */,
							author__person_id				/* text */ ";
$x['fields']['article_tags'] = 'text';
$x['fields']['category'] = 'checkbox';
// $x['debug'] = true;
$r = profile_page($x);

//include_once('modules/g2/g2.php');    

?>
<script>
	add_style("/pages/admin/blog/article/article.css");
</script>

<h2>Write A New Article</h2>
<a href="/admin/blog/manage">Back to Blog Manager</a>
<br><br>

<div id="blog_left">
	
	<input type="hidden" id="blog_id" value="<?=$blog_id?>">
<?
	if (!$_POST['author__person_id']) $_POST['author__person_id'] = $_SESSION['login']['person_id'];
?>
	<input type="hidden" id="author__person_id" value="<?=$_POST['author__person_id']?>">
	
	
	<div>Title</div>
	<input type="text" id="article_title" value="<?=$_POST['article_title']?>">
	
	<div>Introduction (250 characters or less)</div>
	<textarea rows="2" id="introduction"><?=$_POST['introduction']?></textarea>

	<div>Article body</div>
<?
// include html editor
	$html_editor = NULL;
	$html_editor['id'] = 'article_content';
	$html_editor['css'] = '/pages/admin/blog/article/html_editor.css';
	$html_editor['width'] = 620;
	$html_editor['height'] = 350;
	$html_editor['innerHTML'] =  $_POST['article_content'];
	include('modules/html_editor/html_editor.php');
// end include html editor
?>



	<div>Tags (separate tags with commas: nyc, weekly party, lounge)</div>
<? 
	$tags = NULL;
	if (is_numeric($_POST['blog_article_id'])) {
		$SQL = "select blog_tag.name
				from blog_article_tag
				left join blog_tag on blog_tag.id = blog_article_tag.blog_tag_id and blog_tag.active = 1
				where blog_article_tag.active = 1 
				and blog_article_tag.blog_article_id = {$_POST['blog_article_id']} 
				order by blog_tag.name asc";
		$r_tag = $db->Execute($SQL) or die("$SQL<br>" . $db->ErrorMsg());
		while (!$r_tag->EOF) {
			if ($tags) $tags .= ', ';
			$tags .= $r_tag->Fields('name');
			$r_tag->MoveNext();
		}//while
	}//if
?>
	<input type="text" id="article_tags" value="<?=$tags?>">
	
	
	
	<div>
		<input type="button" value=" Save " onclick="<?=$r['save_onclick']?>" />
		
		Status: 
		<select id="article_status">
			<option value="D" <? if ($_POST['article_status']=='D') echo 'selected'; ?>>Draft</option>
			<option value="A" <? if ($_POST['article_status']=='A') echo 'selected'; ?>>Approved</option>
		</select>
		
	</div>
	
	
	<div id="insert_image">
	
<?
// BEGIN IMAGE PICKER MODULE
?>
	<script>	
		function image_picker(g2_id) {
			src = '/photo-gallery.php?g2_view=core.DownloadItem&amp;g2_itemId=' + g2_id;
			tinyMCE.execCommand( 'mceInsertContent', false, '<img src="' + src + '" class="article_image">' );
		}//function
	</script>
<?
	$image_picker['root_album_path'] = 'website/velvetsky/blog';
	$image_picker['default_album_path'] = 'website/velvetsky/blog/' . date('Y');
	$image_picker['js_function_name'] = 'image_picker'; 
//	include('modules/g2/image_picker/image_picker.php');
// END IMAGE PICKER MODULE
?>

	</div> <!-- insert_image -->
	
</div> <!-- blog_left -->

<div id="blog_right">

	<div id="tips" class="blog_right_box">
		Tips For Posting A New Article:<br>
		<ol>
			<li>Find a good image and resize it to one of the following sizes: 
				<ul>
					<li><b>250px wide</b> x ~340px tall</li>
					<li><b>570px wide</b> x ~275px tall</li>
					<li>hint: <a href="http://gui.picresize.com/picresize2/" target="_blank">www.picresize.com</a></li>
				</ul>
			</li>
			<li>Hyperlink at least 1 or 2 words or phrases that refer to an article on another website</li>
			<li>Writing style should be in the third person</li>
			<li>Use the "Blockquote" button if you are quoting more than one sentence (be sure to link to the source!)</li>
		</ol>
	</div>

	<div id="choose_city" class="blog_right_box">
		Markets:
		<div>
<?
			$a = NULL;
			$a['label'] = 'New York';
			$a['value'] = 'nyc';
			$a['name'] = 'market';
			$a['checked'] = true;
			checkbox_button($a);
?>
		</div>
	</div>

	<div id="choose_cateogory" class="blog_right_box">
		Choose A Category:
<?
		$SQL = "select id, name
				from blog_category
				where active = 1";
		$s = $db->Execute($SQL) or die("$SQL<br>".$db->ErrorMsg());
		while (!$s->EOF) {
?>
		<div>
<?
			$a = NULL;
			$a['label'] = $s->Fields('name');
			$a['value'] = encrypt($s->Fields('id'),'blog_category');
			$a['name'] = 'category[]';
			$a['checked'] = false;
			checkbox_button($a);
?>
		</div>
<?
			$s->MoveNext();
		}//while
?>
	</div>


<?
/*
?>
	<div id="blog_timestamp">
		Post Timestamp:<br />
<?
		// date picker
		$date_picker['id'] = "post_date";
		$date_picker['value'] = $_POST['last_event_date_formatted'];
		include('modules/calendar/popup-date-picker/popup-date-picker.php');
?>
		<br>

		<script language="javascript" src="/lib/time_picker/mootools.v1.11.js"></script>
		<script language="javascript" src="/lib/time_picker/nogray_time_picker_min.js"></script>
		<script type="text/javascript">
			window.addEvent("domready", function (){
				var tp1 = new TimePicker('time1_picker', 'post_time', 'time1_toggler',{
								imagesPath:"/lib/time_picker/time_picker_files/images", 
								offset:{x:-160, y:-55},
		<?
				if (!$_POST['post_time_formatted']) $_POST['start_time_formatted'] = '9:00 pm';
				if (!$_POST['post_time']) {
					//$_POST['post_time_hour'] = '21';
					//$_POST['post_time_minute'] = '0';
				}//if
		?>
								selectedTime:{hour:<?=$_POST['post_time_hour']?>, minute:<?=$_POST['post_time_minute']?>},
								startTime:{hour:<?=$_POST['post_time_hour']?>, minute:<?=$_POST['post_time_minute']?>}
							});
			});
		</script>
		<input type="text" name="time1" id="post_time" value="<?=$_POST['post_time_formatted']?>" /> <a href="#" id="time1_toggler"><img src="/images/clock_icon.gif" border="0" align="absmiddle"></a>
		<div id="time1_picker" class="time_picker_div"></div>
		

	</div> <!-- blog_timestamp -->
<?
*/
?>
	
</div> <!-- blog_right -->

<div class="clear"></div>


<?
$template_area = 'bottom';
include('templates/intranet/intranet.php');
?>