<?	
	$p->title = "SEO Phrase Manager";
	$p->template('seo','top');

		$a = array();
		$phrases = dup_phrase_data::getList($a);
?>
	<table width="1500px">
		<tr class="header">
			<th class="title">Phrase</th>
			<th class="title">Category</th>
			<th class="title">Volume</th>
			<th class="title">Keyword</th>
			<th class="title">Holiday</th>
			<th class="title">Base</th>
			<th class="title">Edit</th>
		</tr>
<?		
		$x = 0;
		foreach($phrases as $phrase_id) {
			print_a($phrases);
			$x++;
			$dup_phrase = new dup_phrase_data($phrase_id);	
?>
			<tr class="<?=$x%2?'alternate':'row'?>">
				<td class="column" valign="middle"><?=$dup_phrase['phrase']?></td>
				<td class="column" valign="middle"><?=$dup_phrase['category']?></td>
				<td class="column" valign="middle"><?=$dup_phrase['volume']?></td>
				<td class="column" valign="middle"><?=$dup_phrase['keyword']?></td>
				<td class="column" valign="middle"><?=$dup_phrase['holiday']?></td>
				<td class="column" valign="middle"><?=$dup_phrase['base']?></td>
				<td class="column" valign="middle" style="text-align:center;"><input class="edit" type="button" phrase_ide="<?=$dup_phrase['dup_phrase_ide']?>" value="edit"></td>		
			</tr>
<?
		}
?>
	</table>
<?
	$p->template('seo','bottom');
?>