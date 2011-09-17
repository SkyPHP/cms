<?	
	$p->title = "SEO Phrase Manager";
	$p->template('seo','top');

		$a = array();
		$phrases = dup_phrase_data::getList($a);
		
		foreach($phrases as $phrase_id) {
			echo $phrase_id;
			$dup_phrase = new dup_phrase_data($phrase_id);	
			krumo($dup_phrase);
		}
	

	$p->template('seo','bottom');
?>