<?
	$title = 'Category Management System';
	$no_sidebar = true;
	template::inc('intranet','top');
	 
	
	//extracting all the category from the database
	
?>
	<div style="border: 1px solid #CCCCCC; width:300px;" >
		<form method="post">
			<table width="100%">
				<tr>
					<td align="left" colspan="2"><span id="add_process"></span></td>
				</tr>
				<tr>
					<td align="left">Category Name</td>
					<td align="left"><input type="text" value="" id="name" name="name" /></td>
				</tr>
				<tr>
					<td align="left">Blog</td>
					<td align="left"><? include('modules/blogs_select_box/blogs.php'); ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left"><input type="button" value="Add Category" onclick="add_category(this.form)"></td>
				</tr>
			</table>	
			
		</form>
	
	 
	
	
	</div>

<?

	$aql = "blog_category{
						id as save,
						id as delete,
						name,
						blog_id,
						iorder
						order by blog_category.id																 
        				}
			blog {

			}
			blog_website {
				where website_id = $website_id
			}
						";
						
	
	$cols = "id {}
			name {
            label: Category Name;
         }		  
         blog_id {
            label: Blog;
         }
		 iorder {
            label: iorder;
         }
          
		  save {
		  script: components/blog_category/id/save.php;
		  }
		  delete{
		   script: components/blog_category/id/delete.php;
		  }
		  ";
$param = array(
	'table_class' => 'listing'
);

 
grid::render($aql,$col,$param);

template::inc('intranet','bottom');
?>