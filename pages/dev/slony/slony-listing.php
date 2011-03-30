<?

$aql = "slony_cluster{ id as slony_cluster_id where name='$slony_cluster_name' }";
$rs = aql::select($aql);

if(is_array($rs)){
   redirect('/dev/slony/'.encrypt($rs[0]['slony_cluster_id'],'slony_cluster'));
   //show nodes
}else{
   //show new cluster page
   $show_new_cluster = true;
   //redirect('/dev/slony/add-new');
}

template::inc('intranet','top');

?>

<div style="margin: 10px 0;">
    <?
       if($show_new_cluster){
       ?>Before you begin setting up your Slony Cluster, you should thoroughly read the documentation <a href='http://skyphp.org/doc/slony'>here</a>.  Make sure you have all the below prerequisites met, you will not be able to continue until you do.<br><br>

       <? $prerequisites = slony::check_environment();

          $prereqs = array(
             'slony_tables'=>'slony_* Tables Installed',
             'slony'=>'Slony-I Installed',
             'skyphp_storage_path_writable'=>'$skypph_storage_path is Writable',
             'libssh2'=>'libssh2 Installed',
             'cpan'=>'Perl CPAN Moduels Installed'
          ); 
       ?>

        <? $prereqs_met = 0;
           $errors = array();
           foreach($prereqs as $key=>$prereq){
              if($prerequisites[$key]===true){$prereqs_met++;}else{
                 $errors[]=$prerequisites[$key];
              }
           }
           if($prereqs_met==count($prereqs)){
              ?><center>You meet all prerequisites.  Click <a href='/dev/slony/add-new'>here</a> to proceed.</center><?
           }
           ?>

       <center>
       <table>
          <? foreach($prereqs as $key=>$prereq){
                ?><tr><td><?=$prereq?></td><td><img src='/images/<?=$prerequisites[$key]===true?'success.gif':'error.gif'?>' /><?=$prerequisites[$key]===true?'':$prerequisites[$key] ?></td></tr><?
             }
          ?>          
       </table>
       </center>

<?       };


    ?>
</div>

<?

template::inc('intranet','bottom');

?>
