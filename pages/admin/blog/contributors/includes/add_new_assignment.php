<?

$person_ide = $_POST['pide'];

$form_id = $_POST['fid'];

if($_GET['debug']){
   $person_ide = $_GET['pide'];
   $form_id = $_GET['fid'];
}

if(!($person_ide && ($form_id || $form_id==0))){
   echo "<!--failure-->";
 //  die();
}

?><!--success-->
<?

aql::form('blog_author');

?>
<input class='blog_author_form_save_button_new' type="button" value="Save" onclick="save_button(false,'blog_author_form_<?=$form_id?>','blog_author');" /><?
                    


?>
