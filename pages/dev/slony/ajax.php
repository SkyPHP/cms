<?
   if($_POST['a']){
      $id = decrypt($_POST['a'],'slony_node');
   }

   slony::$silent=false;

   switch($_POST['func']){
      case('stop'):
         if(!count($pre_dump = slony::get_running_slons())){
            echo "No Slon daemons are running.  Stop aborted.";
            ?><script type='text/javascript'>var nodes=null;</script><?
            break;
         }
         ?><script type='text/javascript'>var nodes = new Array();</script><?
         $proc = slony::stop_slon_on_node($id);
         if(count($dump = slony::get_running_slons())){
            $i = 0;

            $slons_by_node = slony::get_running_slons_by_node($dump);

            foreach($slons_by_node as $node=>$slon){
               ?><script type='text/javascript'>nodes[<?=$i++?>]={'ide':'<?=$ide=encrypt($node,'slony_node')?>','comment':'<?=$slon['comment']?>',
                 'running_on':'<?=$slon['watchdog_pid']?>'} ;
                 </script><?
            }
            echo "Stop failed!";
         }else{
            $i=0;
            foreach($pre_dump as $node=>$slon){
               ?><script type='text/javascript'>nodes[<?=$i++?>]={'ide':'<?=$ide=encrypt($node,'slony_node')?>','comment':'Node not running',
                 'running_on':null} ;
                 </script><?
            }
            echo "Slon daemons killed.  REPLICATION IS NOT OCCURRING! <br /><br />";
         }
         break;
      case('restart'):
         slony::stop_slon_on_node($id);
         $force = true;
         #break intentionally excluded
      case('start'):
         if(!$force && count($running_slons = slony::get_running_slons($id))){
            echo "Slon daemons already running on cluster.  Start aborted.  Stop first to restart.";
            ?><script type='text/javascript'>var nodes=null;</script><?
            break;
         }
         $proc = slony::start_slon_on_node($id);
         $running_slons = $proc['running_slons'];
         $slons_by_node = slony::get_running_slons_by_node($running_slons);
         if(($id && count($running_slons))||(!$id && count($running_slons)==slony::get_number_of_nodes())){
            echo "Slon daemons started<br /><br />";
            ?><script type='text/javascript'>var nodes = new Array();</script><?
            $i = 0;
            foreach($slons_by_node as $node=>$slon){
               if($id && $node!=$id){continue;}
               echo "<u><b>Node $node</b></u><br />";
               echo "<b>Running On</b>: {$slon['running_on']} <br/>";
               echo "<b>Comment</b>: {$slon['comment']} <br />";

               ?><script type='text/javascript'>nodes[<?=$i++?>]={'ide':'<?=$ide=encrypt($node,'slony_node')?>','comment':'<?=$slon['comment']?>',
                 'running_on':'<?=$slon['running_on']?>'} ;
                 </script><?
            }
         }else{
            echo "Start failed! <!-- ";
            var_dump($proc);
            echo " -->";
         }
         break;
      case('unsubscribe'):
      case('subscribe'):
         $node = aql::profile('slony_node',$id);
         $node['subscribed']?slony::unsubscribe_node($id):slony::subscribe_node($id);
         break;
      case('drop'):
         slony::drop_node($id);
         break;
      case('add'):
         slony::add_node($id);
      case('promote'):
         slony::promote($id);
         break;
      case('uninstall'):
         slony::uninstall();
         break;
      case('status'):
         $not_running = slony::get_number_of_nodes()-count(slony::get_running_slons());
         if($not_running){
            echo "<strong class='status status_bad'>$not_running node".($not_running>1?'s are':' is')." not replicating!</strong>";
         }else{
            echo "<strong class='status status_good'>All nodes are replicating.</strong>";
         }
         break;
      case('kill'):
         //killing pids given to us via javascript has the potential
         //to wreak havoc if somebody is trying to send non-slony pids...
         //for instance, someone could feasibly kill our database, or apache...
         //so we check the given values first to see if they match our slony pids

         $pid = $_POST['b'];

         if(!array_key_exists($pid,slony::ps($id))){
            echo "No slony related processes are running with pid $pid, are you being shady?";
         }else{
            slony::kill($id,$pid);
            echo "kill ran for $pid";
         }
         break;
      default:
         die("Wrong parameters.");
   }

?>
