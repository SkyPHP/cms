<div style="width:700px">
<? 
	if (is_array($seo_field_array)) {
		foreach($seo_field_array as $type => $array) {
			
			if (!isset($header)) {
?>
				<fieldset style="width:180px; margin-right:15px; border: 1px solid #ccc; padding:20px; float:left;">
                	<legend class="legend"><?=ucwords(str_replace('_',' ',$type))?></legend>
<?
				$header = $type;
			}
			else if ($header != $type) {
?>
					</fieldset>
                    <fieldset style="width:180px; margin-right:15px; border: 1px solid #ccc; padding:20px; float:left;">
                    	<legend class="legend"><?=ucwords(str_replace('_',' ',$type))?></legend>
<?				
				$header = $type;	
				
			}
			foreach($array as $field) {
?>			
			<div style="padding-bottom:10px;">
				<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
	    		<input type="text" class="seo-input" field="<?=$field?>" value="" />
            </div>
<?
			}
		}
	}
?>
</fieldset>
<div style="clear:both;"></div>
</div>