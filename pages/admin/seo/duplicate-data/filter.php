<div style="padding-top:5px;">
<?
	if ($filter['name'] == 'volume') $DESC = 'desc';
	else $DESC = '';
	$rs = sql("SELECT DISTINCT ".$filter['name']." FROM ".$table." where market != '' and base != '' and volume > 0 and active = 1 order by ".$filter['name']." ".$DESC);
?>
	<!--<input type="checkbox" id="select_all"> <label for="select_all">Select All</label><br>-->
<?
	if($rs) while (!$rs->EOF) {
?>
		<div style="margin-bottom:5px;">
		<input type="checkbox" value="<?=$rs->Fields($filter['name'])?>" filter="<?=$filter['name']?>" class="filter_cb" /> <label style="cursor:pointer;" for="<?=$rs->Fields($filter['name'])?>"><?=strtolower($rs->Fields($filter['name']))?></label>
       	</div>
<?		
		$rs->MoveNext();
	}
?>
</div>