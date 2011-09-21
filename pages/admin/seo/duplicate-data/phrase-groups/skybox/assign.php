<?
	$p->title = "Phrase Group Assignment";
	$p->template('skybox','top');
?>
		<div class="float-left" style="margin-right:5px;">Choose a website:</div>
		<div class="float-left">
			<? $field = "website_id" ?>
			<label class="label" for="<?=$field?>">Website</label><br>
			<select id="website_id" style="width:200px;">
				<option value="">- Website -</option>
<?
				$rs = aql::select("website { name order by name }");
				foreach ($rs as $r) {
?>	
					<option value="<?=$r['website_id']?>" <?=$r['website_id'] == $o['website_id']?'selected':''?>><?=$r['name']?></option>
<?
				}
?>
			</select>
		</div>
		<div class="clear">
		<div style="margin: 20px; padding: 10px;">OR</div>
		<div class="float-left" style="margin-right:5px;">Choose a page:</div>
		<div class="float-left">
			<div><input type="radio" name="page_type" id="both" /> <label for="both">All Pages</label></div>
			<div><input type="radio" name="page_type" id="page" /> <label for="page">Page Level</label></div>
			<div><input type="radio" name="page_type" id="uri" /> <label for="uri">URI</label></div>
		</div>
		<div class="clear"></div>
		<div id="results"></div>
<?
	
	$p->template('skybox','bottom');	
?>
<script type="text/javascript">

	$(function() {
		html = $(this).attr('id');
		$('input[name=page_type]').live('change',function() {
			$('#results').html(html);
		});
	});
</script>