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

      if(count($primary_nodes)){
         ?><fieldset><legend>Primary Nodes</legend><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th></tr><?
         foreach($primary_nodes as $id => $primary_node){
            ?><tr><td><?=$id?></td><td><?=$primary_node['primary_host']?></td><td><?=$primary_node['primary_conninfo']?></td></tr><?
         }
         ?></table><?
         ?></fieldset><?

         unset($id);
         unset($primary_node); 
      }else{ #there is trouble, this would signify that there is a 'cluster' but that there is no master, highly unlikely this will ever happen
         
      }

      if(count($standby_nodes)){
         ?><fieldset><legend>Standby Nodes</legend><?
         ?><table class='listing'><?
         ?><tr><th>ID</th><th>Host</th><th>Connection String</th><th>Lag Time</th><th>Primary Node ID</th></tr><?
         foreach($standby_nodes as $id => $standby_node){
            ?><tr><td><?=$id?></td><td><?=$standby_node['standby_host']?></td><td><?=$standby_node['standby_conninfo']?></td><td><?=$standby_node['time_lag']?></td><td><?=$standby_node['primary_node']?></td></tr><?
         }
         ?></table><?
         ?></fieldset><?

         unset($id);
         unset($standby_node); 
      }else{ #this should be impossible, if it happens, it is more likely a failed sql statement than a slaveless cluster
         
      }

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
