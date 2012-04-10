<?

/*

	SAMPLE USAGE:

	$mlr = new CSVMailerBlast;
	
	$mlr->setRecipients(array(
		'path' => 'path/to/csv',
		'distinct_field' => 'email',
		'map' => array(
			'fname' => 2,
			'lname' => 3,
			'email' => 4
		),
		'has_headers' => true
	));

	$mlr->configureMailer(array(
		'template' => 'path/to/template'
	));
	
	try {
		$mlr->sendBlast();
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	

*/

class CSVMailerBlast extends MailerBlast {
	
	/*
		@setRecipients
		params include: 
			- path: if remote this is the url
			- remote: (bool) if true, we would use file_get_contents, otherwise we use fopen
			- map: key value pairs, 'email' => 0 
									'fname' => 1
				where the values are the positions in the csv
			- has_headers: if has_headers, we skip the first row
			- delimiter: defaults to ','
			- distinct_field: if this is set we do a fake distinct on that field in the map

	*/
			
	public function setRecipients($a) {
		$parser = new CSVParser($a);
		$this->recipients = $parser->parse();
	}

}