<?
if(!$repmgr || !$repmgr->initialized){
   die('no cluster installed');
}

$params = (count($_POST)?$_POST:(count($_GET)?$_GET:die('no parameters')));

switch($params['func']){
   case('kill'):
      $standby_node = $params['a'];
      $pid = $params['b'];

      $output = $repmgr->remote_kill($standby_node, $pid);

      $json = array(
         'exit_status' => $exit_status = array_pop($output),
         'success' => $exit_status == '0',
         'output' => is_array($output)?implode('\n', $output):$output,
         'ps' => $repmgr->remote_ps($standby_node)
      );

      echo json_encode($json);

      break;
   case('start'):
      $standby_node = $params['a'];

      $output = $repmgr->remote_start($standby_node);

      $json = array(
         'exit_status' => $exit_status = array_pop($output),
         'success' => $exit_status == '0',
         'output' => is_array($output)?implode('\n', $output):$output,
         'ps' => $repmgr->remote_ps($standby_node)
      );

      echo json_encode($json);

      break;
   case('promote'): #still experimental
      $new_primary_node = $params['a'];

      $output = $repmgr->promote($new_primary_node);

      $json = array(
         'output' => $output
      );

      echo json_encode($json);

      break;
   case('add_hard'):
      $node = $params['a'];

      $output = $repmgr->add($node);

      $json = array(
         'output' => $output
      );

      echo json_encode($json);

      break;
   case('add_soft'):
      $cluster = $params['a'];
      $conninfo = $params['b'];
      $id = $params['c'];

      $json = array();

      #$repmgr->add_soft has all these checks
      #but it does not give detailed error output
      #we need these details for our interface
      if(!is_numeric($id)){
         $json['error'] = "bad id";
      }

      $rs = $db->Execute("select count(*) as count from repmgr_$repmgr_cluster_name.repl_nodes where id = $id");

      if($rs->Fields('count') > 0){
         $json['error'] = 'that id already exists';
      }

      if(!$json['error']){
         $json[$soft = $repmgr->add_soft($cluster, $conninfo, $id)?'success':'error'] = $soft?true:'query_failure';
      }

      echo json_encode($json);

      break;
   case('drop_soft'):
      $node = $params['a'];

      $json = array();

      $json[$repmgr->drop_soft($node)?'success':'error'] = true;

      echo json_encode($json);

      break;
   case('drop_hard'):
      $node = $params['a'];

      $json = array('success' => $repmgr->stop_replication($node));

      echo json_encode($json);
      
   break;
   case('cleanup'):
      $node = $params['a'];

      $json = array('success' => 0, 'attempted' => 0);

      foreach($repmgr->cleanup_repl_monitor($node) as $cleanup){
         if($cleanup){
            $json['success']++;
         }

         $json['attempted']++;
      }

      echo json_encode($json);
  
   break;
   default:
      die('unrecognized function');
}

?>
