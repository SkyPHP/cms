<?
	$o = new dup_phrase_data(IDE);
	$p->title = "Phrase Skybox (".$o['dup_phrase_data_id'].")";
	$p->template('skybox','top');
	
	krumo ($o);
	$p->template('skybox','bottom');
?>