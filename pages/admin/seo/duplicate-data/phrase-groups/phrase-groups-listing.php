<?	
	$p->title = "SEO Phrase Groups";
	$p->template('seo','top');

		$a = array();
		$a['order_by'] = "name";
		$phrases = dup_phrase_group::getList($a);
?>
	<div style="margin: 15px;"><a href="/admin/seo/duplicate-data">Phrase Manager</a> | <a href="/admin/seo/duplicate-data/split-paragraph" >Paragraph Splitter</a> | <a href="/admin/seo/duplicate-data/phrases">Phrase Listing</a></div>
	<h1><?=$p->title?></h1>
	<div id="count"><?=count($phrases)?> Records</div>
	<table width="95%">
		<tr class="header">
			<th class="title">Name</th>
			<th class="title">Phrase 1</th>
			<th class="title">Phrase 2</th>
			<th class="title">Modifier</th>
			<th class="title">Total Volume</th>
			<th class="title">Website</th>
			<th class="title">Category</th>
			<th class="title">Market Name</th>
			<th class="title">Page</th>
			<th class="title">Assign</th>
			<th class="title">Edit</th>
		</tr>
<?		
		$x = 0;
		foreach($phrases as $phrase_id) {
			$x++;
			$o = new dup_phrase_group($phrase_id);	
?>
			<tr class="<?=$x%2?'alternate':'row'?>" id="row_<?=$o['dup_phrase_group_ide']?>">
				<? include ('pages/admin/seo/duplicate-data/phrase-groups/ajax/row.php'); ?>	
			</tr>
<?
		}
?>
	</table>
<?
	$p->template('seo','bottom');
?>