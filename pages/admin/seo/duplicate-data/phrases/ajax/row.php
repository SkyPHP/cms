<? if (!$o) $o = new dup_phrase_data(IDE); ?>
	<td class="column" valign="middle"><?=$o['phrase']?></td>
	<td class="column" valign="middle"><?=$o['category']?></td>
	<td class="column" valign="middle"><?=$o['sub_category']?></td>
	<td class="column" valign="middle"><?=$o['volume']?></td>
	<td class="column" valign="middle"><?=$o['market']?></td>
	<td class="column" valign="middle"><?=$o['market_name']?></td>
	<td class="column" valign="middle"><?=$o['keyword']?></td>
	<td class="column" valign="middle"><?=$o['base']?></td>
	<td class="column" valign="middle"><?=$o['holiday']?></td>
	<td class="column" valign="middle" style="text-align:center;"><input class="edit" type="button" href="/admin/seo/duplicate-data/phrases/skybox/phrase-skybox/" ide="<?=$o['dup_phrase_data_ide']?>" value="edit"></td>