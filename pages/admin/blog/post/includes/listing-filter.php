<?
$blog_editor = auth('blog_author:editor');
$blog_author = auth('blog_author:*');
?>
<form method="get">
	<div class="filter_nav">
		<div class="has-floats">

			<div class="col">
				<input type="text" name="q" class="q" value="<?=$_GET['q']?>" /><input type = "submit" value = "Search" />
			</div>
        
			<div class="col">
<?
				$aql = "blog {
							id,
							name
						}
						blog_website {
							where website_id = $website_id
						}";
				$dropdown = array(
					'select_name' => 'blog_ide',
					'value_field' => 'blog_ide',
					'option_field' => 'name',
					'selected_value' => $_GET['blog_ide'],
					'null_option' => 'All Blogs',
					'onchange' => 'this.form.submit();'
				);
				aql::dd($aql,$dropdown);
?>
			
			</div>
        
        <? if ($blog_editor): ?>
			<div class="col">
<?
				$aql = "blog_author {
							person_id
							where website_id = $website_id
						}
						person {
							fname,
							lname
							order by fname asc,lname asc
						}";
				$rs_author = aql::select($aql);
?>
				<select name="author" onchange="this.form.submit();">
				<option value="">All Contributors</option>
<? 

            if ($rs_author) foreach($rs_author as $author) {
                if ($authors[$author['person_id']] || !$author['person_id']) continue;
                $authors[$author['person_id']] = true;
?>
            <option value="<?=$author['person_ide']?>" 
				<?=($author['person_ide']==$_GET['author'])?'selected="selected"':''?> >
				<?=$author['fname']?> <?=$author['lname']?>
            </option>
<?
            }
?>
            </select>
        </div>
<?
			endif; 

    if ( is_array( $markets ) ) {
?>
		<div class="col">
<?
			$aql = "market {
						name
						where market.name is not null and market.name <>''
						and market.primary = 1  
						order by name asc
					}";
			$dropdown = array(
				'select_name' => 'market_ide',
				'value_field' => 'market_ide',
				'option_field' => 'name',
				'selected_value' => $_GET['market_ide'],
				'null_option' => 'All Markets',
				'onchange' => 'this.form.submit();'
			);
			aql::dd($aql,$dropdown);
?>
        </div>
<?
    }//if
?>
	</div>
	<br/>
	<div class="has-floats">
		<div class="col">
<?	
		$aql = "blog_category{
								name
								order by iorder
							}";
		$dropdown = array(
					'select_name' => 'blog_category_ide',
					'value_field' => 'blog_category_ide',
					'option_field' => 'name',
					'selected_value' => $_GET['blog_category_ide'],
					'null_option' => 'All Categories',
					'onchange' => 'this.form.submit();'
				);
		aql::dd($aql,$dropdown);	
?>
		</div>
		</div>
	</div>
</form>