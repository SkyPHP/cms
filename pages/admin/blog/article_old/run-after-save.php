<?

$blog_article_id = $id;

$tags = my_array_unique(explode(',',$_POST['article_tags']));
// print_r($_POST);
foreach ($tags as $tag) {
	$tag = trim(strtolower($tag));
	// echo $tag . ',';
	$SQL = "select id
			from blog_tag
			where active = 1
			and blog_id = $blog_id
			and name = '$tag'";
	$r = $db->Execute($SQL) or die("$SQL<br>".$db->ErrorMsg());
	if ($r->EOF) {
		// insert new blog_tag
		// echo 'inserting ' . $tag;
		$f = NULL;
		$f['blog_id'] = $blog_id;
		$f['name'] = $tag;
		$f['mod__person_id'] = $_SESSION['login']['person_id'];
		if ($_POST['debug']) print_r($f);
		$table = 'blog_tag';
		$db->AutoExecute($table,$f,'INSERT') or die($db->ErrorMsg());
		$SQL = "SELECT currval('".$table."_id_seq') as id ";
		$s = $db->Execute($SQL);
		$blog_tag_id = $s->Fields('id');
		if ($_POST['debug']) echo "new '{$tag}' blog_tag_id: " . $blog_tag_id . "\n";
	} else {
		$blog_tag_id = $r->Fields('id');
	}//if

	// insert blog_article_tag if this article does not already have this tag attached
	$SQL = "select id
			from blog_article_tag
			where active = 1
			and blog_article_id = {$blog_article_id}
			and blog_tag_id = {$blog_tag_id}";
	$r = $db->Execute($SQL) or die("$SQL<br>".$db->ErrorMsg());
	// echo $SQL;
	if ($r->EOF) {
		$f = NULL;
		$f['blog_article_id'] = $blog_article_id;
		$f['blog_tag_id'] = $blog_tag_id;
		$f['mod__person_id'] = $_SESSION['login']['person_id'];
		$table = 'blog_article_tag';
		if ($_POST['debug']) print_r($f);
		$db->AutoExecute($table,$f,'INSERT') or die($db->ErrorMsg());		
	}//if
}//foreach
?>