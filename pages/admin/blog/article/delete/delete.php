<?php
	
	 
	$article_ide =$_POST['blog_article_id'];
	$person_id = $_SESSION['login']['person_id'];
	
	$table_name ="blog_article";
	$data_array = array(
    	'active' => 0,
		'mod_time' => 'now()',
		'mod__person_id' => $person_id
	);
	aql::update( $table_name, $data_array, $article_ide);
	
	header("Location:/admin/blog/article/list/");
	exit;



?>