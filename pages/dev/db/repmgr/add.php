<?
#This page is meant to be used as an ajax called page
?>
<form class='skyform'>

<fieldset><legend>Add a Node to repmgr</legend>

<div id='skyform_error'></div>

<? $field='cluster'; ?>
<div class='field'>
   <label for='<?=$field?>' >Cluster Name</label> 
   <input id='<?=$field?>_input' type='text' name='<?=$field?>' value='<?=$repmgr_cluster_name?>' />   
</div>

<? $field='id'; ?>
<div class='field'>
   <label for='<?=$field?>' >ID</label>
   <input id='<?=$field?>_input' type='text' name='<?=$field?>' value='' />
</div>

<? $field='conninfo'; ?>
<div class='field'>
   <label for='<?=$field?>' >Connection String</label>
   <input id='<?=$field?>_input' type='text' name='<?=$field?>' value='' />
</div>

<input type='button' value='Add' onclick='repmgr_add_soft($("#cluster_input").val(), $("#conninfo_input").val(), $("#id_input").val());' />
<input type='button' value='Cancel' onclick='$.skyboxHide();' />

</fieldset>

</form>
