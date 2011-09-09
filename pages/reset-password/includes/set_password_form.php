<?
$o = new person($p->queryfolders[0]);
$o->_token = $o->getToken();

?>

<div class="info" id="response_div">Enter your new password</div> 
<form model="person" class="aqlForm">
	
    <label for="password1">New password</label>
	<input name="password1" id="password1" type="password" style="width:200px;" />
	
    <label for="password2">Repeat new password</label>
    <input name="password2" id="password2" type="password" style="width:200px;" />

    <input name="_token" type="hidden" value="<?=$o->_token?>" />

    <input type="submit" value="Submit" style="width:75px;margin-top:25px;" />

</form>