<?
	$data = array(
		'name' => $_POST['website_name'],
		'type' => 'skymedia',
		'domain' => $_SERVER['SERVER_NAME'],
		'mod__person_id' => PERSON_ID
	);
	$insert = aql::insert('website',$data);
	if (is_array($insert)) {
?>
		Website Added Successfully!
        <br><br>
        <button onClick="location.reload()">Close</button>

<?
}
?>