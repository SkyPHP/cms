<?

if ($_POST['form_posted']) {
#	$table_name = 'fruit';
#	$fields['name'] = $_POST['name'];
#	$fields['qty'] = $_POST['qty'];
#	$fields['price'] = $_POST['price'];
#	$fields['discount'] = $_POST['discount'];
#	$rs = aql::insert( $table_name, $fields );
#	$rs['fruit_id'] => this is the id of the newly inserted record
#	$rs['fruit_ide'] => this new id is also encrypted for your convenience	
?>
<div style="background-color:#FFFFFF; padding:10px;">
	You posted...<br />
	lemon: <?=$_POST['lemon']?><br />
	lime: <?=$_POST['lime']?>
	
</div>

<?

} else { 

?>

	<script>
	
	function submitForm(theform) {
		theform.action = '/pages/dev/ajax_form.php';
		theform.method = 'post';
		AjaxRequest.submit(theform,{
			'onSuccess' : function(req){
				document.getElementById('test').innerHTML = req.responseText;
			}
		});
	}//function
		
	</script>
	
	<div id="test"></div>
	<form>
		<input type="hidden" name="form_posted" value="1">
		<input type="text" name="lemon" value="yellow">
		<input type="text" name="lime" value="green">
		<input type="button" onclick="submitForm(this.form)" value="Submit">
	</form>

<?

}//if

?>