<div style="padding-top:5px;">
<?
	if ($filter == 'volume') $DESC = 'desc';
	else $DESC = '';
	$rs = sql("SELECT DISTINCT ".$filter." FROM ".$table." where market != '' and base != '' and volume > 0 and active = 1 order by ".$filter." ".$DESC);
?>
	<!--<input type="checkbox" id="select_all"> <label for="select_all">Select All</label><br>-->
<?
	if($rs) while (!$rs->EOF) {
?>
		<div style="margin-bottom:5px;">
		<input type="radio" value="<?=$rs->Fields($filter)?>" id="<?=$rs->Fields($filter)?>" name="<?=$filter?>" class="phrase-filter-radio" /> <label style="cursor:pointer;" for="<?=$rs->Fields($filter)?>"><?=strtolower($rs->Fields($filter))?></label>
       	</div>
<?		
		$rs->MoveNext();
	}
?>
</div>