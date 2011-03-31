<?
$blog_id = $_GET['blog_id'];
if (!$blog_id && $_POST['sky_ide']) {
	$aql = "blog_article { blog_id where blog_article.ide = '{$_POST['sky_ide']}' }";
	$rs_blog_article = aql::select($aql);
	$blog_id = $rs_blog_article[0]['blog_id'];
}//if
$criteria = '';
if ($blog_id) $criteria = "and (blog_id = {$blog_id} or blog_id is null)";
if (!auth('blog_editor') && $_SESSION['login']['person_id'] ) $criteria .= " and blog_author.person_id = '{$_SESSION['login']['person_id']}' ";

if (!$rs_article[0]['author__person_id']) $rs_article[0]['author__person_id'] = decrypt($_GET['person_ide'], 'person');
if (!$rs_article[0]['author__person_id']) $rs_article[0]['author__person_id'] = '';

$aql_author = "blog_author {
					person_id
					where blog_author.website_id = $website_id
					{$criteria}
					order by editor
				}
				person {
					fname,
					lname
				}";
$rs_author = aql::select($aql_author);
?>
<select name="author__person_id">
	<? $selected = ($rs_article[0]['author__person_id'] == "") ? "selected" : ""; ?>
	<option value="NULL" <?=$selected?>>Author</option>
<?
	if (is_array($rs_author)) {
		foreach ($rs_author as $author){ 
			$selected = '';
			if ($author['person_id'] == $rs_article[0]['author__person_id']) $selected = 'selected="selected"';
			else if ( !$rs_article[0]['author__person_id'] && $author['person_id'] == $_SESSION['login']['person_id']) $selected = 'selected="selected"';
			
?>
			<option value="<?=$author['person_id']?>" <?=$selected?>><?=$author['fname']?> <?=$author['lname']?></option>
<?
		} //foreach
	}//if
?>
</select>