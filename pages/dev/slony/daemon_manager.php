<?

$title = "Slony Daemon Manager for Cluster $slony_cluster_name";



template::inc('intranet','top');

$running = slony::get_running_slons();

$running_by_node = slony::get_running_slons_by_node($running);

//var_dump($running);

//echo " ||| ";

//var_dump($running_by_node);


function print_kill_button($node_id, $pid, $callback=NULL){
   if(!$callback){
      $callback = "function(){location.reload(true);}";
   }
 
   $ide = encrypt($node_id,'slony_node');
   ?>
      <input type='button' value='Kill' onclick="ajax_kill('<?=$ide?>',<?=$pid?>,<?=$callback?>);" />
   <? 
}

?>

   <script type='text/javascript' src='/pages/dev/slony/slony.js'></script>

   <div id='slony_cluster_message'></div>


   <fieldset class='sdm_cluster' id='sdm_cluster'>
      <legend>Cluster Overview</legend>
      <?
         $count = 0;
         $flag_count = 0;
 
         foreach($running_by_node as $node){
            $count++;
            $flag_count+=$node['flag'];
         }
      ?>
      <div class='sdm_cluster_status' id='sdm_cluster_status'>
         <?
            echo ($flag_count>0?"<span class='sdm_warning'>$flag_count node".($flag_count>1?'s':'')." not running properly.</span>":"All nodes appear to be running properly.");
         ?>
      </div>
      <table class='listing'>
         <tr>
            <th>Node #</th> <th>Running On</th> <th>Comment</th> <th> </th>
         </tr>
         <?
            foreach($running_by_node as $node){
               ?>
                  <tr <?=$node['flag']?"class='sdm_warning'":""?>>
                     <td><?=$node['node']?></td> <td><?=is_array($node['running_on'])?implode(', ',$node['running_on']):$node['running_on']?></td> <td><?=$node['comment']?></td>
                     <td>
                        <input type='button' value='Restart' onclick='ajax_restart("<?=encrypt($node['node'],'slony_node')?>",function(){location.reload(true);});' />
                        <input type='button' value='Stop' onclick='ajax_stop("<?=encrypt($node['node'],'slony_node')?>",function(){location.reload(true);});' />
                     </td>
                  </tr>
               <?
            }
         ?>
      </table>
   </fieldset>

<?
if(count($running)){
?>

   <fieldset class='sdm_nodes' id='sdm_nodes'>
      <legend>Nodes</legend>
<?
foreach($running as $node){
   $node_prof = slony::get_node_profile($node['node']);
?>
   <fieldset class='sdm_node' id='sdm_node_<?=$node['node']?>'>
      <legend class='sdm_node_title' id='sdm_node_title_<?=$node['node']?>'>
         Node # <?=$node['node']?> - <?=$node_prof['host']?>
      </legend>
      <fieldset class='sdm_node_analysis' id='sdm_node_analysis_<?=$node['node']?>'>
         <legend>Overview</legend>
         <?=$count_slon=count($node['slons'])?> slon daemon<?=$count_slon>1?'s':''?> running, <?=$count_watchdog=count($node['watchdogs'])?> watchdog<?=$count_watchdog>1?'s':''?> running.  
         <?
            if($count_orphans = count($node['orphans'])){
               ?><span class='sdm_warning'><?=$count_orphans?> orphan<?=$count_orphans>1?'s':''?> running.</span><?
            }
            if($count_unknown = count($node['unknown'])){
               ?><span class='sdm_warning'><?=$count_unknown?> Unknown process<?=$count_orphans>1?'es':''?> running.</span><?
            }

            if($count_slon%2!=0){
               ?><br /><span class='sdm_warning'>One or more of your slon daemons is running improperly.</span><?
            }
            if(($count_watchdog)!=($count_slon/2)){
               if($count_watchdog<($count_slon/2)){
                  ?><br /><span class='sdm_warning'>You may have downed watchdogs.</span><?
               }else{
                  ?><br /><span class='sdm_warning'>One or more daemons were downed, your watchdog may start new ones shortly.  Do so manually if not.</span><?
               }
            }

         ?>
      </fieldset>
      <fieldset class='sdm_ps' id='sdm_ps_<?=$node['node']?>'>
         <legend>Running Processes</legend>
         <fieldset class='sdm_ps_slons' id='sdm_ps_slons_<?=$node['node']?>'>
            <legend class='sdm_ps_title'>
               Slon Daemons
            </legend>
            <div class='sdm_ps_body'>
               <table class='listing'>
                  <tr>
                     <th>PID</th> <th>Node #</th> <th>Comment</th> <th>Command</th> <th> </th>
                  </tr>
                  <?
                     foreach($node['slons'] as $slon){
                        ?>
                           <tr>
                              <td><?=$slon['pid']?></td> <td><?=$slon['node']?></td> <td><?=$slon['comment']?></td> <td><?=$slon['cmd']?></td>
                              <td><?=print_kill_button($node['node'],$slon['pid'])?></td>
                           </tr> 
                        <?
                     }
                  ?>
               </table>
            </div>
         </fieldset>
         <fieldset class='sdm_ps_watchdogs' id='sdm_ps_watchdogs_<?=$node['node']?>'>
            <legend class='sdm_ps_title'>
               Slon Watchdogs
            </legend>
            <div class='sdm_ps_body'>
               <table class='listing'>
                  <tr>
                     <th>PID</th> <th>Node #</th> <th>Comment</th> <th>Command</th> <th> </th>
                  </tr>
                  <?
                     foreach($node['watchdogs'] as $slon){
                        ?>
                           <tr> 
                              <td><?=$slon['pid']?></td> <td><?=$slon['node']?></td> <td><?=$slon['comment']?></td> <td><?=$slon['cmd']?></td>
                              <td><?=print_kill_button($node['node'],$slon['pid'])?></td>
                           </tr> 
                        <?
                     }
                  ?>
               </table>
            </div>
         </fieldset>
         <?
            if($count_orphan){
               ?>
                  <fieldset class='sdm_ps_orphans' id='sdm_ps_orphans_<?=$node['node']?>'>
                     <legend class='sdm_ps_title'>
                        Orphans
                     </legend>
                     <div class='sdm_ps_body'>
                        <table class='listing'>
                           <tr>
                              <th>PID</th> <th>Node #</th> <th>Comment</th> <th>Command</th> <th> </th>
                           </tr>
                           <?
                              foreach($node['orphans'] as $slon){
                                 ?>
                                    <tr> 
                                       <td><?=$slon['pid']?></td> <td><?=$slon['node']?></td> <td><?=$slon['comment']?></td> <td><?=$slon['cmd']?></td> 
                                       <td><?=print_kill_button($node['node'],$slon['pid'])?> </td>
                                    </tr> 
                                 <?
                              }
                           ?>
                        </table>
                     </div>
                  </fieldset>
               <?
            }
            if($count_unknown){
               ?>
                  <fieldset class='sdm_ps_unknown' id='sdm_ps_unknown_<?=$node['node']?>'>
                     <legend class='sdm_ps_title'>
                        Unknown
                     </legend>
                     <div class='sdm_ps_body'>
                        <table class='listing'>
                           <tr>
                              <th>PID</th> <th>Command</th> <th> </th>
                           </tr>
                           <?
                              foreach($node['unknown'] as $slon){
                                 ?>
                                    <tr>
                                       <td><?=$slon['pid']?></td> <td><?=$slon['cmd']?></td> 
                                       <td><?=print_kill_button($node['node'],$slon['pid'])?></td>
                                    </tr>
                                 <?
                              }
                           ?>
                        </table>
                     </div>
                  </fieldset>
               <?
            }
         ?>
      </fieldset>
   </fieldset>
<?
}
?>
   </fieldset>
<?
}//end if($running)

template::inc('intranet','bottom');

?>
