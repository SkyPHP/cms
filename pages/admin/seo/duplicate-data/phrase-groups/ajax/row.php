<? $o = new dup_phrase_group(IDE); ?>
	<td class="column" valign="middle"><?=$o['name']?></td>
	<td class="column" valign="middle"><?=aql::value('dup_phrase_data.phrase',$o['phrase1__dup_phrase_data_id'])?></td>
	<td class="column" valign="middle"><?=aql::value('dup_phrase_data.phrase',$o['phrase2__dup_phrase_data_id'])?></td>
	<td class="column" valign="middle"><?=aql::value('dup_modifier.phrase',$o['dup_modifier_id'])?></td>
	<td class="column" valign="middle"><?=$o['total_volume']?></td>
	<td class="column" valign="middle"><?=aql::value('website.name',$o['website_id'])?></td>
	<td class="column" valign="middle"><?=$o['category']?></td>
	<td class="column" valign="middle"><?=$o['market_name']?></td>
	<td class="column" valign="middle"><?=$o['page']?></td>
	<td class="column" valign="middle" style="text-align:center;"><input class="assign" type="button" group_ide="<?=$o['dup_phrase_group_ide']?>" value="assign"></td>
	<td class="column" valign="middle" style="text-align:center;"><input class="edit" type="button" href="/admin/seo/duplicate-data/phrase-groups/skybox/phrase-group-skybox/" ide="<?=$o['dup_phrase_group_ide']?>" value="edit"></td>