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
		$field = 'phrase';
		$width = 310;
		$listing = aql::select($table." { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume where market != '' and base != '' and volume > 0 order by volume DESC, phrase asc }"); 
	}
	else if ($type == 'paragraph') {
		$table = 'dup_sentence';
		$field = 'sentence';
		$listing = aql::select($table." { sentence, volume where market is not null order by sentence asc }");
	}
	
	$count = count($listing);
	$rs = aql::select("dup_filters { name where type = '{$type}' order by id ASC }");
?>	
	<div style="margin: 15px 0 0 0;">
     	<input type="radio" id="auto-switch-off" <? if ($_GET['area'] != 'auto') echo 'checked' ?> value="manual" class="a-or-m-switch" name="auto-switch" /> <label for="auto-switch-off">Manual Permutations</label><br>
        <input type="radio" id="auto-switch-on" <? if ($_GET['area'] == 'auto') echo 'checked' ?> value="auto" class="a-or-m-switch" name="auto-switch" /> <label for="auto-switch-on">Auto Permutations</label><br>
  	</div>
    
	<div style="padding-top:10px;">
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
    
    <div id="auto" <? if ($_GET['area'] != 'auto') echo 'style="display:none;"'; else echo 'class="a-or-m-on"'; ?>>
<?
		//include ('pages/admin/seo/duplicate-data/ajax/auto-permutate.php');
?>
    </div>
    
    <div id="manual" <? if ($_GET['area'] == 'auto') echo 'style="display:none;"'; else echo 'class="a-or-m-on"'; ?>>
        <fieldset style="width:80%">
            <legend class="legend">Final Phrase</legend>
			<div id="saved-message"></div>
            <input type="text" id="final-phrase" style="width:93%; font-size:16px;" readonly  /><br>
			<div style="margin-top:5px"><input type="button" value="save" id="save-final" /> <input type="button" value="clear" id="clear-all" /></div>
        </fieldset>   
        <div id="listing">
<? 		
			include ('pages/admin/seo/duplicate-data/ajax/filter-listing.php'); 
?>
        </div>
        <div class="clear"></div>
	</div>
<?	
	$p->template('seo','bottom');	
?>