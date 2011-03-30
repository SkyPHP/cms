<?
 
$blog_id =$_REQUEST['blog_id'];

$blog_article_cat_id =trim($_REQUEST['blog_article_cat_id']);

 
$aql_cat = "blog_category {id, name
				where blog_id=".$blog_id."}";
$rs_cat = aql::select($aql_cat);

if ($rs_cat) {

	if($blog_article_cat_id!=''){
		foreach ($rs_cat as $blog_cat) { ?>
		<label><input name="blog_category_id" id="blog_category_id" type="radio" <?php if($blog_article_cat_id==$blog_cat['id']){ ?> checked="checked" <?php } ?> value="<?=$blog_cat['id']?>" /><?=$blog_cat['name']?></label><br/>
		<?php }
	}else{
		$i =0;
		foreach ($rs_cat as $blog_cat) { ?>
		<label><input name="blog_category_id" id="blog_category_id" type="radio" <?php if($i==0){ ?> checked="checked" <?php } ?> value="<?=$blog_cat['id']?>" /><?=$blog_cat['name']?></label><br/>
		<?php $i++;  }
	}

}//if



?>