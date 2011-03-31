<?

$model = 'slony_node';
$slony_node = aql::profile($model,IDE);
$primary_table = aql::get_primary_table($model);
$slony_node_ide = IDE;
$slony_node_id = decrypt($slony_node_ide,$model);
$slony_node_prof = aql::profile($model,$slony_node_id);

$title = "Slony Node Definition";

template::inc('intranet','top');

?>
<fieldset><legend>Node Profile</legend>
<?
aql::form('slony_node');
?>

   <input type="button" value="Save" onclick="save_form('<?=$model?>'<?=slony::cluster_defined()?",null,null":"" ?> );" />
<? if(!slony::cluster_defined()){ ?>
   <input type="button" value="Save and Define Another Node" onclick="save_form('<?=$model?>',null,null,function(){location.href='/dev/slony/node/add-new';});" />
   <input type="button" value="Save and Continue to Next Step" onclick="save_form('<?=$model?>',null,null,function(){location.href='/dev/slony/add-new';});" />
   <input type="button" value="Discard and Continue to Next Step" onclick="location.href='/dev/slony/add-new';" />
<? } ?>
</fieldset>
<?

template::inc('intranet','bottom');

?>
