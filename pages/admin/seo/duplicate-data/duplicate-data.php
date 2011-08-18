<?
	$p->title = 'Duplicate Data System ';
	$p->tabs = array(
		'Title' => '/admin/seo/duplicate-data/title',
		'H1' => '/admin/seo/duplicate-data/h1',
		'H1 Blurb' => '/admin/seo/duplicate-data/h1-blurb',
		'Meta Title' => '/admin/seo/duplicate-data/meta-title',
		'Meta Description' => '/admin/seo/duplicate-data/meta-description'
	);
	switch(IDE) {
		
		case 'title':
			$title .= '- Title';
			$type = "phrase";
		break;
		
		case 'h1':
			$type = "phrase";
			$title .= '- H1';
		break;
		
		case 'h1-blurb':
			$type = "paragraph";
			$title .= '- H1 Blurb';
		break;
		
		case 'meta-title':
			$type = "phrase"; 
			$title .= '- Meta Title';
		break;
		
		case 'meta-description':
			$type = "paragraph";
			$title .= '- Meta Description';
		break;
	}
	snippet::tab_redirect($p->tabs);
	
	$p->template('seo','top');	
	
	snippet::tabs($p->tabs);
	
	if ($type == 'phrase') {
		$table = 'dup_phrase_data';
		$width = 310;
		$listing = aql::select($table." { lower(phrase) as lower_phrase, phrase, volume where market != '' and base != '' and volume > 0 order by volume DESC, phrase asc }"); 
	}
	else if ($type == 'paragraph') {
		$table = 'dup_sentence';
		$listing = aql::select($table." { sentence, volume where market is not null order by sentence asc }");
	}
	
	$count = count($listing);
	$rs = aql::select("dup_filters { name where type = '{$type}' order by id ASC }");
?>	<div style="padding-top:10px;">
		<div style="float:left; margin-right:15px; font-weight:bold;">Filters:</div>
        <input type="hidden" id="table" value="<?=$table?>" />
<?
		foreach ($rs as $filter) {
?>			<div style="float:left; margin-right:50px;">
				<div class="filter" type="<?=$type?>" style="font-weight:bold; width: 145px; padding-left:5px; cursor:pointer; border: 1px solid #999; border-bottom: 2px solid #999;" filter="<?=$filter['name']?>"><?=str_replace('_',' ',$filter['name'])?></div>
                <div id="<?=$filter['name']?>" style="position:absolute; display:none; min-width:150px; background-color: #fff; border-bottom: 1px solid #999; border-left: 1px solid #999; border-right: 1px solid #999;" class="filter-area"><? include('pages/admin/seo/duplicate-data/filter.php') ?></div>
            </div>
<?	
		}
?>
		<div class="clear"></div>
	</div>
    <fieldset style="width:80%">
    	<legend class="legend">Final Phrase</legend>
        <input type="text" id="final-phrase" style="width:93%;" readonly  /> <input type="button" value="save" id="save-final" />
    </fieldset>
    
    
    <div id="auto-switch" style="margin-top:10px;">
    	<label for="auto-switch-dd">Auto On/Off</label>
    	<select id="auto-switch-dd">
        	<option>Manual</option>
            <option>Auto</option>
    	</select>
    </div>
    
    <div id="auto" class="a-or-m">
        <h2>Auto</h2>
    </div>
    
    
    <div id="listing" class="a-or-m">
    	<h2>Manual</h2>
<? 		
		include ('pages/admin/seo/duplicate-data/ajax/filter-listing.php'); 
?>
    </div>
<?	
	$p->template('seo','bottom');	
?>