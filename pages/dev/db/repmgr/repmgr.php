<?
$p->template('intranet','top');
?>

<div>
<?
if($repmgr && $repmgr->initialized){
   ?><fieldset><legend>Stats for Cluster '<?=$repmgr_cluster_name?>'</legend><?

      $nodes = $repmgr->get_nodes();
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
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th><th>Lag Time</th><th>Primary Node ID</th></tr><?
         foreach($standby_nodes as $id => $standby_node){
            ?><tr><td><?=$id?></td><td><?=$standby_node['host']?></td><td><?=$standby_node['conninfo']?></td><td><?=$standby_node['time_lag']?></td><td><?=$standby_node['primary_node_id']?></td></tr><?
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

      foreach($standby_nodes as $standby_node){
         $ps = $repmgr->remote_ps($standby_node['id'], $ssh_user);

         ?><fieldset class='ps' ><legend>repmgr Processes on <?=$standby_node['host']?></legend><?
         if(is_array($ps)){
            ?><div id='ps_<?=$standby_node['id']?>_error'></div><?
            ?><input type='button' value='Start Daemon' onclick="repmgr_start(<?=$standby_node['id']?>);" /><br /><?
            ?><div id='ps_<?=$standby_node['id']?>' class='volitile'><?
            if(count($ps)){
               ?><table class='listing'><?
               ?><tr><th>PID</th><th>User</th><th>Command</th><th></th></tr><?
               foreach($ps as $process){
                  ?><tr><td><?=$process['pid']?></td><td><?=$process['user']?></td><td><?=$process['cmd']?></td><td><input type='button' value='Kill' onclick="repmgr_kill(<?=$standby_node['id']?>, <?=$process['pid']?>);" /></td></tr><?
               }
               ?></table><?
            }else{
               ?>No repmgr processes running.<?
            }
         }else{
            ?>Local user '<?=`whoami`?>' unable to SSH into <?=$ssh_user?>@<?=$standby_node['host']?><?
         }
         ?></div><?
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
