<?
	if (is_array($seo_field_array)) {
		foreach($seo_field_array as $type => $array) {
			
			if (!isset($header)) {
?>
				<fieldset style="width:200px; margin-bottom:15px; border: 1px solid #ccc; padding:20px;">
                	<legend class="legend"><?=ucwords(str_replace('_',' ',$type))?></legend>
<?
				$header = $type;
			}
			else if ($header != $type) {
?>
					</fieldset>
                    <fieldset style="width:200px; margin-bottom:15px; border: 1px solid #ccc; padding:20px;">
                    	<legend class="legend"><?=ucwords(str_replace('_',' ',$type))?></legend>
<?				
				$header = $type;	
				
			}
			foreach($array as $field) {
?>			
			<div>
				<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
	    		<input type="text" class="seo-input" field="<?=$field?>" value="" />
            </div>
<?
			}
		}
	}
?>
</fieldset>