<?
	if(!$blog_article)
		$blog_article['blog_ide'] = $_POST['blog_ide'];
	$aql = "blog_category{
				name,
				slug
				where blog_ide = {$blog_article['blog_ide']}
				order by name asc
			}";
	aql::dd($aql,array('onchange'=>'if($(\'option:selected\',this).val())insert_tag($(\'option:selected\',this).text(),true)',
						'select_name'=>'blog_category_ide',
						'selected_value'=>$blog_article['blog_category_ide'],
						'value_field'=>'blog_category_ide',
						'option_field'=>'name',
						'null_option'=>'Select Blog Category'
						));
?>