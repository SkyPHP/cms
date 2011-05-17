<?
$p->template('intranet','top');
?>

<div>
<?
if($repmgr_cluster_name){
   ?><fieldset><legend>Stats for Cluster '<?=$repmgr_cluster_name?>'</legend><?

   $conninfo_host_key = 'host=';

   $sql = "select s_nodes.conninfo as standby_conninfo, p_nodes.conninfo as primary_conninfo, substr(s_nodes.conninfo, strpos(s_nodes.conninfo, '$conninfo_host_key') + length('$conninfo_host_key'), strpos(substr(s_nodes.conninfo, strpos(s_nodes.conninfo, '$conninfo_host_key')), ' ') - 1 - length('$conninfo_host_key')) as standby_host, substr(p_nodes.conninfo, strpos(p_nodes.conninfo, '$conninfo_host_key') + length('$conninfo_host_key'), strpos(substr(p_nodes.conninfo, strpos(p_nodes.conninfo, '$conninfo_host_key')), ' ') - 1 - length('$conninfo_host_key')) as primary_host, standby_node, primary_node, time_lag from repmgr_$repmgr_cluster_name.repl_nodes as s_nodes inner join repmgr_$repmgr_cluster_name.repl_status s_status on s_nodes.id = s_status.standby_node inner join repmgr_$repmgr_cluster_name.repl_nodes as p_nodes on s_status.primary_node = p_nodes.id;";

   if($rs = $db->Execute($sql)){
      $primary_nodes = array();
      $standby_nodes = array();

      while(!$rs->EOF){
         if(!$primary_nodes[$primary_node = $rs->Fields('primary_node')]){
            $primary_nodes[$primary_node] = array(
               'primary_conninfo' => $rs->Fields('primary_conninfo'),
               'primary_host' => $rs->Fields('primary_host'),
               'primary_node' => $primary_node
            );
         }

         if(!$standby_nodes[$standby_node = $rs->Fields('standby_node')]){
            $standby_nodes[$standby_node] = array(
               'standby_conninfo' => $rs->Fields('standby_conninfo'),
               'standby_host' => $rs->Fields('standby_host'),
               'standby_node' => $standby_node,
               'primary_node' => $primary_node,
               'time_lag' => $rs->Fields('time_lag')
            );
         }

         $rs->MoveNext();

         unset($primary_node);
         unset($standby_node);
      }

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


      unset($rs);
      unset($sql);
      unset($primary_nodes);
      unset($standby_nodes);
      unset($conninfo_host_key);
   }else{
      ?>$repmgr_cluster_name is defined '<?=$repmgr_cluster_name?>' but there does not appear to be a cluster installed with that name.<?
   }

   ?></fieldset><?
}else{
   ?>$repmgr_cluster_name is not defined<?
}

?>
</div>

<?
$p->template('intranet','bottom');
?>
