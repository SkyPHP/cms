<?
$p->template('intranet','top');
?>

<div>
<?
if($repmgr && $repmgr->initialized){
   ?><fieldset><legend>Stats for Cluster '<?=$repmgr_cluster_name?>'</legend><?

      $nodes = $repmgr->get_nodes(true);
      $primary_nodes = &$nodes['primary'];
      $standby_nodes = &$nodes['standby'];

 
      #output the primary nodes
      if(count($primary_nodes)){
         ?><fieldset><legend>Primary Nodes</legend><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th></tr><?
         foreach($primary_nodes as $id => $primary_node){
            ?><tr><td><?=$id?></td><td><?=$primary_node['host']?></td><td><?=$primary_node['conninfo']?></td></tr><?
         }
         ?></table><?
         ?></fieldset><?

         unset($id);
         unset($primary_node); 
      }else{ #there is trouble, this would signify that there is a 'cluster' but that there is no master, highly unlikely this will ever happen
         
      }

 
      #output the standby nodes
      if(count($standby_nodes)){
         ?><fieldset><legend>Standby Nodes</legend><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th><th>Primary Node ID</th><th>Lag Time</th><th></th></tr><?
         foreach($standby_nodes as $id => $standby_node){
            $promote_button = "<input type='button' value='Promote' onclick='repmgr_promote($id);' />";
            ?><tr><td><?=$id?></td><td><?=$standby_node['host']?></td><td><?=$standby_node['conninfo']?></td><?
            foreach($standby_node['roles'] as $i => $role){
               ?><?=$i?'<tr><td></td><td></td><td></td>':''?><td><?=$role['primary_node_id']?></td><td><?=$role['time_lag']?></td><td><?=$i?'':$promote_button?></td></tr><?
            }
         }
         ?></table><?
         ?></fieldset><?

         unset($id);
         unset($standby_node); 
      }else{ #this should be impossible, if it happens, it is more likely a failed sql statement than a slaveless cluster
         
      }


      #output ps findings on standby_nodes
      ?><fieldset><legend>Monitoring Daemon Status</legend><?
      ?><div>NOTE: repmgr monitoring daemons only run on standby nodes</div><?
      ?><div>NOTE: Killing a repmgr monitoring daemon will NOT stop replication, it will only stop the stats table from being updated</div><?

      $ssh_user = 'postgres';

      foreach($repmgr->get_nodes() as $node){
         $ps = $repmgr->remote_ps($node['id'], $ssh_user);

         ?><fieldset class='ps' ><legend>repmgr Processes on <?=$node['host']?></legend><?
         if(is_array($ps)){
            ?><div id='ps_<?=$node['id']?>_error'></div><?
            ?><input id='ps_<?=$node['id']?>_start' type='button' value='Start Daemon' onclick="repmgr_start(<?=$node['id']?>);" <?=count($ps)?'disabled="disabled" ':''?>/><br /><?
            ?><div id='ps_<?=$node['id']?>' class='volitile'><?
            if(count($ps)){
               ?><table class='listing'><?
               ?><tr><th>PID</th><th>User</th><th>Command</th><th></th></tr><?
               foreach($ps as $process){
                  ?><tr><td><?=$process['pid']?></td><td><?=$process['user']?></td><td><?=$process['cmd']?></td><td><input type='button' value='Kill' onclick="repmgr_kill(<?=$node['id']?>, <?=$process['pid']?>);" /></td></tr><?
               }
               ?></table><?
            }else{
               ?>No repmgr processes running.<?
            }
            ?></div><?
         }else{
            ?>Local user '<?=`whoami`?>' unable to SSH into <?=$ssh_user?>@<?=$standby_node['host']?><?
         }
         ?></fieldset><?

      }
      ?></fieldset><?

      unset($nodes, $primary_nodes, $standby_nodes);
   ?></fieldset><?
}else{
   ?>$repmgr is not initialized<?
}

?>
</div>

<?
$p->template('intranet','bottom');
?>
