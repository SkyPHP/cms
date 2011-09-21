<?	
	$p->title = "SEO Phrase Manager";
	$p->template('seo','top');

		$a = array();
		$a['order_by']="category, phrase";
		$phrases = dup_phrase_data::getList($a);
?>
	<div style="margin: 15px;"><a href="/admin/seo/duplicate-data">Phrase Manager</a> | <a href="/admin/seo/duplicate-data/split-paragraph" >Paragraph Splitter</a> | <a href="/admin/seo/duplicate-data/phrases">Phrase Groups</a></div>
	<h1><?=$p->title?></h1>
	<div id="count"><?=count($phrases)?> Records</div>
	<table width="95%">
		<tr class="header">
			<th class="title">Phrase</th>
			<th class="title">Category</th>
			<th class="title">Sub Category</th>
			<th class="title">Volume</th>
			<th class="title">Market</th>
			<th class="title">Market Name</th>
			<th class="title">Keyword</th>
			<th class="title">Base</th>
			<th class="title">Holiday</th>
			<th class="title">Edit</th>
		</tr>
<?		
		$x = 0;
		foreach($phrases as $phrase_id) {
			$x++;
			$o = new dup_phrase_data($phrase_id);	
?>
			<tr class="<?=$x%2?'alternate':'row'?>" id="row_<?=$o['dup_phrase_data_ide']?>">
				<? include ('pages/admin/seo/duplicate-data/phrases/ajax/row.php'); ?>		
			</tr>
<?
		}
?>
	</table>
<?
	$p->template('seo','bottom');
?>