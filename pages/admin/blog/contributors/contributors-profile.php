<?
      
$blog_author_ide = $_POST['sky_ide'];
$blog_author_id = decrypt($blog_author_ide,'blog_author');
$blog_author = aql::profile('blog_author',$blog_author_ide);

if (is_numeric($blog_author['blog_author_id'])) $title = $blog_author['fname'] . ' ' . $blog_author['lname'];
else $title = 'Add New Contributor';

template::inc('intranet','top');

if($_GET['debug']){

   echo "blog_author_ide: $blog_author_ide <br />blog_author_id: $blog_author_id <br />";
   var_dump($blog_author);
}
?>



<div class="has-floats">
<? if (!$blog_author['blog_author_ide']): ?>
    <form method="get" action="/admin/blog/contributors/add-new">
        <div class="filter_nav">
            
            <div class="col">
                <span style="margin-right:10px;">Search for contributor by email address:</span>
                <input type="text" name="q" class="q" style="width:300px;" value="<?=$_GET['q']?>" />
            </div>
    
            <div class="clear"></div>
            
        </div>
    </form>
    
    <?
    $q = '';
    
    if ($_GET['q']):
        $_GET['q'] = addslashes(trim(urldecode($_GET['q'])));
        $aql = "person {
                    id as name,
                    id as add_contributor,
                    fname,
                    lname,
					access_group,
                    email_address
                    where ( email_address ilike '" . $_GET['q'] . "%' )
					and password is not null and password != ''
                }";
        $cols = "
            name { label: Name; }
            email_address { label: Email; }
			access_group {}
            add_contributor {}
        ";
        aql::grid($aql,$cols);
    endif;

endif;

if( !$blog_author['blog_author_ide'] ){

   if($_GET['debug']){
      echo "no blog_author_ide <br />";
   }

   if(is_numeric($person_id = decrypt($_POST['sky_qs'][0],'person'))){
      if($_GET['debug']){
         echo "person_id : $person_id ". decrypt($_POST['sky_qs'][0],'person')."<br />";
      }
   
      $aql = "blog_author{
         where person_id=$person_id
      }";

      $person_res = aql::select($aql);

      if(is_array($person_res)){
         if($_GET['debug']){
            echo "found blog_author <br />";
            var_dump($person_res);
         }

         $blog_author = aql::profile('blog_author',$person_res[0]['blog_author_ide']);
         $blog_author_ide = $blog_author['blog_author_ide'];
         $blog_author_id = $blog_author['blog_author_id'];
      }else{
         if($_GET['debug']){
            echo "no found blog_author <br />";
            var_dump($person_res);
         }

         $person_res = aql::select("person{fname,lname,email_address where id=$person_id}");

         if(!is_array($person_res)){
            echo "This is not a valid user!";
         }else{

           $prevent_default = true;
?>
           <fieldset><legend>Contributor Profile</legend>
              <div class="field">
                  <label class="label">Name</label>
                  <?=$person_res[0]['fname']?>&nbsp;
                  <?=$person_res[0]['lname']?>
              </div>
   
              <div class="field">
                  <label class="label">Email Address</label>
                  <?=$person_res[0]['email_address']?>
              </div>
		</fieldset>
           <fieldset><legend>First Assignment</legend>
<?
         
           aql::form('blog_author');

           ?>
           <input class='blog_author_form_save_button' type="button" value="Save" onclick="save_primary_profile('blog_author_form_<?=$form_id?>','blog_author');" /></fieldset>
			</fieldset>
		<?
      }
    }
  }
}

if ( $blog_author['blog_author_ide'] && !$prevent_default):
?>

    <div class="col">
        <fieldset>
            <legend>Contributor Profile</legend>
               <div class="field">
               <label class="label">Name</label>
               <?=$blog_author['fname']?$blog_author['fname']:$rs_person[0]['fname']?>&nbsp;
               <?=$blog_author['lname']?$blog_author['lname']:$rs_person[0]['lname']?>
           </div>
 
           <div class="field">
               <label class="label">Email Address</label>
               <?=$blog_author['email_address']?$blog_author['email_address']:$rs_person[0]['email_address']?>
           </div>
		
           <? $page_views_carear = blog::get_pageviews('person',$blog_author['person_id']);
              $page_views_article = blog::get_pageviews('blog_article',array('where'=>"where author__person_id={$blog_author['person_id']}"));
           ?>

           <fieldset><legend>Page View Stats</legend>
              <label class='label'>Carear</label>
              Hits: <?=$page_views_carear['sum'] ?><br />
              Hits per article: <?=$page_views_carear['ratio'] ?><br />
              <br /> 
              <label class='label'>Top 20 Articles</label>
              <?    
                  ?><table class='listing'>
                    <tr><th>Position</th><th>Hits</th><th>Article</th><th>% of Total Hits</th></tr>
                  <?                
                   foreach($page_views_article as $i=>$pva){
                   ?>
                      <tr <?=(($i+1)%2)?'':'class="alternate-row"'?>>
                         <td><?=$i+1?></td>
                         <td><?=$pva['sum']?></td>
                         <td><a href='/newyork/frequency/<?=encrypt($pva['blog_article_id'],'blog_article')?>'><?=$pva['title']?></a></td>
                         <td><?=(floor(($pva['sum']/$page_views_carear['sum'])*1000)/10)?> %</td>
                      </tr>                                                
                   <?
                }
              ?>
              </table>
              

           </fieldset>

           <fieldset><legend>Assignments</legend>

              <a href='javascript:toggle_inactive();' id='toggle_inactive'>Show Inactive</a>

           <?   $aql = "blog_author{
                            where person_id={$blog_author['person_id']}
                            order by status!='A'
                         }";

                 $blog_authors_rs = aql::select($aql);

                 foreach($blog_authors_rs as $form_id=>$blog_author_rs){

                     $blog_author = aql::profile('blog_author',$blog_author_rs['blog_author_ide']);
                     ?><fieldset id='blog_author_fieldset_<?=$form_id?>' class='blog_author_<?=$blog_author['status']!='A'?'inactive':'active'?>'><?
//                     include( 'aql/models/blog_author/blog_author_form.php' );
                       aql::form('blog_author');
                     ?>
                     <input class='blog_author_form_save_button' type="button" value="Save" onclick="save_button(true,'blog_author_form_<?=$form_id?>','blog_author');" /><?
                     ?></fieldset><?
                 } ?>

           <br />
           <script type='text/javascript'>var fid = <?=$form_id?>;</script>
           <input id='add-new-assignment' type="button" value="Add new assignment" onclick="add_new_assignment('<?=$blog_author['person_ide']?>',++fid);" /> <br />
			</fieldset>
 
        <br />
        <input type="button" value="Save all" onclick="$('.blog_author_form_save_button').each(function(a,b){b.click();});" />

        </fieldset>
    </div>
</div>
<?
endif;

template::inc('intranet','bottom');
?>
