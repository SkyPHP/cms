<?

$model = 'slony_cluster';
$slony_cluster = aql::profile($model,IDE);
$primary_table = aql::get_primary_table($model);
$slony_cluster_ide = IDE;
$slony_cluster_id = decrypt($slony_cluster_ide,$model);
$slony_cluster_prof = aql::profile($model,$slony_cluster_id);

$title = "Slony Cluster Admin";

$aql = "slony_node{ }";
$rs = aql::select($aql);
if(!slony::cluster_defined() && !is_array($rs)){
   redirect('/dev/slony/node/new-cluster');
}else{
   if(!slony::cluster_defined()){
//      redirect('/dev/slony/');
   }
}

template::inc('intranet','top');

if(!$slony_cluster_id){
   ?><script tyle='text/javascript'> var new_cluster=true; </script><?
}
?>


<fieldset><legend>Cluster Profile</legend>
<div id='slony_failed_tests' style='font-weight:heavy;color:#ff0000;'>
<?

if($bad_ssh = slony::check_ssh()){
   foreach($bad_ssh as $bad){
      echo "Unable to SSH to $bad.  <br />";
   }

 //  $disable_slony = true;
}

//var_dump(slony::check_dbconnection());

if($bad_db = slony::check_dbconnection()){
   foreach($bad_db as $bad){
      echo "User '{$bad['user']}' can not connect to database {$bad['conninfo']} with the error \"{$bad['error']}\"  <br />";
   }
}

//var_dump(slony::check_slony_is_installed());
if(!slony::cluster_defined()){
   //we do not need to perform this check all the time
   //it is reasonably likely that no one is going to uninstall slony from a node
   foreach($bad_slony=slony::check_slony_is_installed() as $key=>$bad){
      if(!$bad['success']){
         $disable_slony = true;
         if($bad['success']===false){
             echo "Unable to determine Slony-I installation status on {$bad['host']} for reason \"{$bad['error']}\" <br />";
         }else{
             echo "Slony-I is not installed on {$bad['host']}.  <br />";
         }
      }  
   }
}

?></div><?


if( !in_array('ssh2',get_loaded_extensions())){
   echo "You are using phpseclib to make your ssh connections.  It is recommended you install the libssh2 php extension, which is 10 times faster.<br />";
}

aql::form('slony_cluster');
?>
<? 

if(!$disable_slony){
   if(!$slony_cluster_id){ /*
   <input type="button" value="Save" onclick="confirm_warning()?function(){new_cluster=false;save_form('<?=$model?>',null,null,function(){location.href='/dev/slony';});}():null;" /> */ ?>
   <input type="button" value="Save" onclick="save_form('<?=$model?>',null,null,function(){location.href='/dev/slony';});" />
   <? }else{ ?>
   <input type="button" id='button_uninstall' value="Uninstall" onclick="confirm('This action will stop all replication and remove all cluster configurations.  This can not be undone.\n\nClick OK to continue.')?ajax_uninstall():null;" />
   <? } 
}?>

</fieldset>
<?


template::inc('intranet','bottom');

?>
