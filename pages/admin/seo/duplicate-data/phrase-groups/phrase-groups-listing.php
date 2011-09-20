<?	
	$p->title = "SEO Phrase Groups";
	$p->template('seo','top');

		$a = array();
		$phrases = dup_phrase_group::getList($a);
?>
	<div style="margin: 15px;"><a href="/admin/seo/duplicate-data">Phrase Manager</a> | <a href="/admin/seo/duplicate-data/split-paragraph" >Paragraph Splitter</a> | <a href="/admin/seo/duplicate-data/phrases">Phrase Listing</a> <h1><?=$p->title?></h1>
	<div id="count"><?=count($phrases)?> Records</div>
	<table width="95%">
		<tr class="header">
			<th class="title">Name</th>
			<th class="title">Phrase 1</th>
			<th class="title">Phrase 2</th>
			<th class="title">Modifier</th>
			<th class="title">Total Volume</th>
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
			<tr class="<?=$x%2?'alternate':'row'?>">
				<td class="column" valign="middle"><?=$o['name']?></td>
				<td class="column" valign="middle"><?=aql::value('dup_phrase_data.phrase',$o['phrase1__dup_phrase_data_id'])?></td>
				<td class="column" valign="middle"><?=aql::value('dup_phrase_data.phrase',$o['phrase2__dup_phrase_data_id'])?></td>
				<td class="column" valign="middle"><?=aql::value('dup_modifier.phrase',$o['dup_modifier_id'])?></td>
				<td class="column" valign="middle"><?=$o['total_volume']?></td>
				<td class="column" valign="middle"><?=$o['category']?></td>
				<td class="column" valign="middle"><?=$o['market_name']?></td>
				<td class="column" valign="middle"><?=$o['page']?></td>
				<td class="column" valign="middle" style="text-align:center;"><input class="assign" type="button" group_ide="<?=$o['dup_phrase_group_ide']?>" value="assign"></td>
				<td class="column" valign="middle" style="text-align:center;"><input class="edit" type="button" group_ide="<?=$o['dup_phrase_group_ide']?>" value="edit"></td>		
			</tr>
<?
		}
?>
	</table>
<?
	$p->template('seo','bottom');
?>