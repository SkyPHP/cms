<?	
	$p->title = "SEO Phrase Manager";
	$p->template('seo','top');

	$rs = aql::select();

	$p->template('seo','bottom');
?>