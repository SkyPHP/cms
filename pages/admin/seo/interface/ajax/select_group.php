	<form id="wg">
		<select name="wg_id" id="wg">
    		<option value="">- Choose Website Group -</option>
<?
	if ($rs) {
		while(!$rs->EOF) {
?>
			<option value="<?=$rs->Fields('name')?>"><?=$rs->Fields('name')?></option>
<?
			$rs->MoveNext();
		}
	}
?>
		</select>
    </form>