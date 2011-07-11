<?
	echo "Inserting";
	
	for ($x = 28; $x <= 31; $x++) {
		$data = array(
			'website_id' => $x,
			'name' => 'Bar Crawls',
			'mod__person_id' => PERSON_ID,
		);
		//aql::insert('website_group',$data);
	}
?>