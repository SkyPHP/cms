<?
$q = $_GET['q'];

template::inc('intranet','top');

			// TAG SQL
			$SQL = "select lower(name) as tag_name, count(lower(blog_article_tag.name)) as count
					from blog_article_tag
					where blog_article_tag.active = 1 ";
			if($q)	
				$SQL .= "and name ilike '%$q%' ";	
			$SQL .= "group by lower(name)";
			if($_GET['srt'] == "desc")
				$SQL .= "order by count(lower(blog_article_tag.name)) desc, lower(name) desc ";				
			else
				$SQL .= "order by count(lower(blog_article_tag.name)) asc, lower(name) asc ";
			$SQL .= "limit 2000";
			$r = sql($SQL);
			$row_count = 0;
?>
<div class="has-floats">
    <div class="float-right blog_listing">
        <div class="content_listing">
        
        <div class="add_new_row">
            <form name='search' action="<?=$URL_PATH?>" style="display:inline;" method="get">
                <input type="text" name="q" id="" value="<?=$q?>"style="width:200px;" />
                <input type="submit" value="Search" />
            </form>
       
            <form name="clear" style="display:inline" action="<?=$URL_PATH?>" method="get">
                <input type="submit" value="Clear" />
            </form>
            <a href="<?=$URL_PATH?>?srt=desc">Popular Tags</a>
        </div>
		<div id="note_message"></div>
		<table class='listing'>
              <tr>
              <th>Tag</th><th># of Articles</th><th>Delete</th>
              </tr>

        <?

		
				
		
		while (!$r->EOF) {
			$row_count ++;
			$tagname = $r->Fields('tag_name');
			$esc_tagname = str_replace("'", "\'", $tagname);
			$esc_tagname = str_replace('"', '**hidedoublequote**', $esc_tagname);
			echo '<tr id="' . $row_count . '">
			<td><a href="/tag/'. htmlspecialchars($tagname) . '">' . $tagname . '</a></td>
			<td>' . $r->Fields('count') . '</td>
			<td><button  onclick="delete_tag(\'' . $esc_tagname . "' ,'" . $row_count . '\')">Delete</button></td>
			</tr>';
			$r->MoveNext();
		}
		
        ?>
		</table>
        	<? if ($row_count==2000) echo "Only the first 2,000 results are shown. Make a query to see other tags.";?>
        </div>
    </div>
    <div class="left_nav">
<?
        include( INCPATH . '/../left-nav/left-nav.php');
?>
    </div>
</div>
<?
template::inc('intranet','bottom');
?>
