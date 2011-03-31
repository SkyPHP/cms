<?
if ($_POST['func'] == 'updateValue') {
	$table = $_POST['table'];
	if (!$table) exit("Please enter a table name.");
	$column = $_POST['column'];
	if (!$table) exit("Please enter a column name.");
	$id = $_POST['id'];
	if (!$table) exit("Please enter an id name.");
	$newVal = $_POST['newVal'];
	if(!$newVal) $newVal = NULL;
	
	aql::update($table,array($column => $newVal),$id);
	exit("success");
}
?>