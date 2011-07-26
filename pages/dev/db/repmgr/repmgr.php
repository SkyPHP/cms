<?
$p->template('intranet','top');
?>

<div>
<?

unset($repmgr);
$repmgr = new repmgr($db_host, true);

if($repmgr && $repmgr->initialized){
   ?><fieldset><legend>Stats for Cluster '<?=$repmgr_cluster_name?>'</legend><?

      $nodes = $repmgr->get_nodes(true);
      $primary_nodes = &$nodes['primary'];
      $standby_nodes = &$nodes['standby'];
      $unused_nodes = &$nodes['unused'];
 /*
      foreach($nodes as $nods){
         foreach($nods as $nod){
            var_dump($nod['id'] . ' - ' . $repmgr->check_replication($nod['id']));
         } 
      }
*/
      if($needs_cleanup = (count($primary_nodes) > 1)){
         #this is indicative of a sloppy promote, so we should cleanup_repl_monitor
         ?><div id='general_error'><?
         ?>Your repmgr status table says you have more than one primary node, but this is not possible!  This is usually the result of a previous node promotion not properly cleaning up the repl_monitor table.  To perform the cleanup now, click the cleanup button next to the primary node you wish to remove from the status tables (the node which is no longer the cluster's primary node).<?
         ?></div><?
      }

      #output the primary nodes
      if(count($primary_nodes)){
         ?><fieldset><legend>Primary Nodes</legend><?
         ?><div id='primary_error'></div><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th><?=$needs_cleanup?'<th></th>':''?></tr><?
         foreach($primary_nodes as $id => $primary_node){
            $cleanup_button = "<input type='button' value='Cleanup' onclick='repmgr_cleanup($id);' />";
            ?><tr><td><?=$id?></td><td><?=$primary_node['host']?></td><td><?=$primary_node['conninfo']?></td><?=$needs_cleanup?"<td>$cleanup_button</td>":''?></tr><?
         }
         ?></table><?
         ?></fieldset><?

         unset($id, $primary_node); 
      }else{ #there is trouble, this would signify that there is a 'cluster' but that there is no master, highly unlikely this will ever happen
         
      }

 
      #output the standby nodes
      if(count($standby_nodes)){
         ?><fieldset><legend>Standby Nodes</legend><?
         ?><div id='standby_error'></div><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th><th>Primary Node ID</th><th>Lag Time</th><th></th></tr><?
         foreach($standby_nodes as $id => $standby_node){
            $promote_button = "<input type='button' value='Promote' onclick='repmgr_promote($id);' />";
            $drop_button = "<input type='button' value='Drop from Replication' onclick='repmgr_drop_hard($id);' />";
            ?><tr><td><?=$id?></td><td><?=$standby_node['host']?></td><td><?=$standby_node['conninfo']?></td><?
            foreach($standby_node['roles'] as $i => $role){
               ?><?=$i?'<tr><td></td><td></td><td></td>':''?><td><?=$role['primary_node_id']?></td><td><?=$role['time_lag']?></td><td><?=$i?'':$promote_button . $drop_button?></td></tr><?
            }
         }
         ?></table><?
         ?></fieldset><?

         unset($id, $standby_node, $promote_button, $drop_button); 
      }else{ #this should be impossible, if it happens, it is more likely a failed sql statement than a slaveless cluster
         
      }

      if(count($unused_nodes)){
         ?><fieldset><legend>Unused Nodes</legend><?
         ?><div id='unused_error'></div><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th><th></th></tr><?
         foreach($unused_nodes as $id => $unused_node){
            $add_button = "<input type='button' value='Add to Replication' onclick='repmgr_add_hard($id);' />";
            $drop_button = "<input type='button' value='Drop from repmgr' onclick='repmgr_drop_soft($id);' />";
            ?><tr><td><?=$id?></td><td><?=$unused_node['host']?></td><td><?=$unused_node['conninfo']?></td><td><?=$add_button?><?=$drop_button?></td></tr><?
         }
         ?></table><?
         ?></fieldset><?

         unset($id, $unused_node, $add_button, $drop_button);
      }else{

      }

      ?><input id='add_soft' type='button' value='Add node to Repmgr' onclick='$.skyboxShow("/dev/db/repmgr/add");' /><?

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
            ?><input id='ps_<?=$node['id']?>_start' type='button' value='Start Daemon' onclick="repmgr_start(<?=$node['id']?>);" <?/*=count($ps)?'disabled="disabled" ':''*/?>/><br /><?
            ?><div id='ps_<?=$node['id']?>' class='volitile'><?
            if(count($ps)){
               ?><table class='listing'><?
               ?><tr><th>PID</th><th>User</th><th>Command</th><th></th></tr><?
               foreach($ps as $process){
                  $kill_button = "<input type='button' value='Kill' onclick='repmgr_kill({$node['id']}, {$process['pid']});' />";
                  $hide_kill_button = preg_match('#^\s*postgres:#', $process['cmd']);
                  ?><tr><td><?=$process['pid']?></td><td><?=$process['user']?></td><td><?=$process['cmd']?></td><td><?=$hide_kill_button?'':$kill_button?></td></tr><?
               }
               unset($kill_button);
               ?></table><?
            }else{
               ?>No repmgr processes running.<?
            }
            ?></div><?
         }else{
            ?>Local user '<?=`whoami`?>' unable to SSH into <?=$ssh_user?>@<?=$node['host']?><?
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
