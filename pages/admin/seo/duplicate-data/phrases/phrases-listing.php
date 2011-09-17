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
		</tr>
<?		
		foreach($phrases as $phrase_id) {
			$dup_phrase = new dup_phrase_data($phrase_id);	
?>
			<tr class="row">
				<td class="column"><?=$dup_phrase['phrase']?></td>
				<td class="column"><?=$dup_phrase['category']?></td>
				<td class="column"><?=$dup_phrase['volume']?></td>
				<td class="column"><?=$dup_phrase['keyword']?></td>
				<td class="column"><?=$dup_phrase['holiday']?></td>
				<td class="column"><?=$dup_phrase['base']?></td>				
			</tr>
<?
			
		}
?>
	</table>
<?
	

	$p->template('seo','bottom');
?>