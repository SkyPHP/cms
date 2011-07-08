<?
	$title = "SEO Interface";
	template::inc('seo','top');

	$rs = sql("SELECT DISTINCT name FROM website_group WHERE active = 1 ORDER BY name ASC");
?>
<div id="left-col">
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
	<div id="website_group_result"></div>
</div>
<div class="divider"></div>
<div id="middle-col">

</div>
<div class="divider"></div>
<div id="right-col">
    <div id="comparison">
    </div>
</div>
<div class="clear"></div>
<? 
	template::inc('seo','bottom');
?>