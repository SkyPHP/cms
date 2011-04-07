<?
	if (is_array($seo_field_array)) {
		foreach($seo_field_array as $type => $array) {
			
			if (!isset($header)) {
?>
				<fieldset class="seo-fieldset">
                	<legend class="legend"><?=ucwords(str_replace('_',' ',$type))?></legend>
<?
				$header = $type;
			}
			else if ($header != $type) {
?>
					</fieldset>
                    <fieldset class="seo-fieldset">
                    	<legend class="legend"><?=ucwords(str_replace('_',' ',$type))?></legend>
<?				
				$header = $type;	
				
			}
			foreach($array as $field) {
?>			
			<div>
				<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
	    		<input type="text" class="seo-input" field="<?=$field?>" value="<?=$rs[0]['value']?>" />
            </div>
<?
			}
		}
	}
?>
</fieldset>