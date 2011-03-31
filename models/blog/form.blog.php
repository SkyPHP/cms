<?
if($_POST['sky_ide']!='add-new')
	$blog = aql::profile('blog',IDE);
	
$theme_ab1 = "undo,redo,|,bold,italic,underline,strikethrough,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect,styleselect";
$theme_ab2 = "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,sub,sup,charmap,|,link,unlink,anchor,image,cleanup,help,|,code,|,removeformat,|,fullscreen,|,preview";
$options = 	array(
	'resizing' => true,
	'theme' => 'advanced',
	'multi_buttons' => true,
	'theme_ab1' => $theme_ab1,
	'theme_ab2' => $theme_ab2,
	'theme_ab3' => "",
	'theme_ab4' => "",
	'height' => '320'
);
$settings = array(
	'email_template' => $options
);
helper::tinymce($settings);
?>
<form name = "blog" id='blog_form'>
<div class="has-floats">
	<input type = "hidden" value = "<?=$blog['blog_ide']?>" name = "blog_ide" />
<?
	$field = 'name';
?>
	<div class = "float-left">
	<div class="field">
		<label class="label" for="<?=$field?>"><?=ucfirst($field) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
        <input type='hidden' name='blog_website_ide' value='<?=$blog['blog_website_ide']?>' />
        <div class="field">
                        <? $field = 'status'; ?>
            <label class="label" for="<?=$field?>"><?=ucfirst($field)?></label>
            <select name="status">
                <option value="A" <?=$blog['status']=='A'?'selected="selected"':''?>>Active</option>
                <option value="" <?=$blog['status']!='A'?'selected="selected"':''?>>Inactive</option>
            </select>
        </div>

<?
	$field = 'url';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=ucfirst($field) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'page_titile';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'keywords';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=ucfirst($field) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'href';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=ucfirst($field) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'num_articles';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'slug';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=ucfirst($field) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'description';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=ucfirst($field) ?></label>
		<textarea id = "<?=$field?>" type="text" name="<?=$field?>"><?=$blog[$field]?></textarea>
	</div>
<?
	$field = 'copyright_name';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'full_width';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'partial_width';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>

<?
	$field = 'poll_embed_code';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<textarea id="<?=$field?>" name="<?=$field?>"><?=$blog[$field] ?></textarea>
	</div>

<?
	$field = 'notification_from';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>
<?
	$field = 'notification_subject';
?>
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<input id = "<?=$field?>" type="text" name="<?=$field?>" value="<?=$blog[$field]?>" />
	</div>

	</div>
	<div class = "float-right">
		<fieldset>
			<legend>Blog Categories</legend>
			<div id="cats">
		
<?
                if($blog['blog_id']){
         		$aql = "blog_category{	
								name
								where blog_id = {$blog['blog_id']}
								order by name asc
							}";
		   $rs = aql::select($aql);
		   if($rs){
			foreach($rs as $cat){
				include('pages/admin/blog/blogs/ajax/blog_category_row.php');
			}
		   }
                }else{
                   ?>Save this blog first to add categories.<?
                }
?>
			</div>
		<? if($blog['blog_id']){ ?>	<div>
				<h3>Add New Category</h3>
				<input type="text" id="blog_category_getter" onkeydown="if(event.keyCode == 13) insert_cat()"/>
				<input autocomplete="OFF" type="button" value="Insert" onclick="insert_cat()"/>
			</div>
               <? } ?>
		</fieldset>
	</div>
</div>
<?
	$field = 'notification_template';
?>	
	<div class="field">
		<label class="label" for="<?=$field?>"><?=str_replace('_',' ',ucfirst($field)) ?></label>
		<textarea id = "email_template" name = "email_template"><?=$blog[$field] ?></textarea>
	</div>
</form>
