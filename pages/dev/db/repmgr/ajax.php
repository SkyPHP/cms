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

      echo var_dump($json);

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

      #perform some sanity checks
      if(!is_numeric($id)){
         $json['error'] = "bad id";
      }

      $rs = $db->Execute("select count(*) as count from repmgr_$repmgr_cluster_name.repl_nodes where id = $id");

      if($rs->Fields('count') > 0){
         $json['error'] = 'that id already exists';
      }

      if(!$json['error']){
         if($rs = $dbw->Execute("insert into repmgr_$repmgr_cluster_name.repl_nodes(cluster, conninfo, id) values('$cluster', '$conninfo', $id) returning *")){
            if(!$rs->EOF){
               $json['success'] = ($rs->Fields('cluster') == $cluster);
            } 

         }else{
            $json['error'] = 'query Failed';
         }
      
      }

      echo json_encode($json);

      break;
   case('drop'):
      $node = $params['a'];

      $json = array();

      if($rs = $dbw->Execute("delete from repmgr_$repmgr_cluster_name.repl_nodes where id = $node and cluster = '$repmgr_cluster_name' returning *")){
         $json['success'] = ($rs->Fields('id') == $node);
      }else{
         $json['error'] = 'query Failed';
      }
      echo json_encode($json);

      break;
   default:
      die('unrecognized function');
}

?>
