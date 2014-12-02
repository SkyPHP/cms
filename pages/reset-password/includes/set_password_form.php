<?php

$_POST['refresh'] = 1 ;

use \Sky\Model\person;

$o = new person($p->queryfolders[0]);
$o->_token = $o->getToken();

?>

<div class="info" id="saveForm_message_person">Enter your new password</div>
<form model="person" class="aqlForm" id="password_form">

    <input type="hidden" name="_token" value="<?=$o->_token?>" />
    <input type="hidden" name="person_ide" value="<?=$o->person_ide?>" />
    <input type="hidden" name="password_reset_hash" value="<?=$o->password_reset_hash?>" />

    <label for="password1">New password</label>
	<input name="password1" id="password1" type="password" style="width:200px;" />

    <label for="password2">Repeat new password</label>
    <input name="password2" id="password2" type="password" style="width:200px;" />

    <input type="submit" value="Submit" style="width:75px;margin-top:25px;" />

</form>
