<?	
	$p->title = "SEO Phrase Manager";
	$p->template('seo','top');

		$a = array();
		$phrases = dup_phrase_data::getList($a);
?>
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
			<th class="title">Edit</th>
			<th class="title">Holiday</th>
		</tr>
<?		
		$x = 0;
		foreach($phrases as $phrase_id) {
			$x++;
			$o = new dup_phrase_data($phrase_id);	
?>
			<tr class="<?=$x%2?'alternate':'row'?>">
				<td class="column" valign="middle"><?=$o['phrase']?></td>
				<td class="column" valign="middle"><?=$o['category']?></td>
				<td class="column" valign="middle"><?=$o['sub_category']?></td>
				<td class="column" valign="middle"><?=$o['volume']?></td>
				<td class="column" valign="middle"><?=$o['market']?></td>
				<td class="column" valign="middle"><?=$o['market_name']?></td>
				<td class="column" valign="middle"><?=$o['holiday']?></td>
				<td class="column" valign="middle"><?=$o['base']?></td>
				<td class="column" valign="middle"><?=$o['keyword']?></td>
				<td class="column" valign="middle" style="text-align:center;"><input class="edit" type="button" phrase_ide="<?=$o['dup_phrase_data_ide']?>" value="edit"></td>		
			</tr>
<?
		}
?>
	</table>
<?
	$p->template('seo','bottom');
?>