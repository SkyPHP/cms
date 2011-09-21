<div style="padding-top:5px;">
<?
	if ($class != 'modifier') {
		if ($filter == 'volume') $DESC = 'desc';
		else $DESC = '';
		$rs = sql("SELECT DISTINCT ".$filter." FROM dup_phrase_data where active = 1 and ".$filter." is not null order by ".$filter." ".$DESC);
	}
	else $rs = sql("SELECT DISTINCT ".$filter." FROM dup_modifier where active = 1 and ".$filter." is not null order by ".$filter);
?>
	<div style="margin-bottom:5px;">
		<input type="radio" section="listing" value="" section="<?=$class?>" id="all_<?=$filter?>" name="<?=$filter?>" class="phrase-filter-radio" style="margin-left:3px;" /> <label style="cursor:pointer;" for="all_<?=$filter?>">ALL</label>
    </div>
<?
	if($rs) while (!$rs->EOF) {
?>
		<div style="margin-bottom:5px;">
		<input type="radio" section="<?=$class?>" value="<?=$rs->Fields($filter)?>" id="<?=$filter?>_<?=$rs->Fields($filter)?>" name="<?=$filter?>" style="margin-left:3px;" class="phrase-filter-radio" /> <label style="cursor:pointer;" for="<?=$filter?>_<?=$rs->Fields($filter)?>"><?=strtolower($rs->Fields($filter))?></label>
       	</div>
<?		
		$rs->MoveNext();
	}
?>
</div>