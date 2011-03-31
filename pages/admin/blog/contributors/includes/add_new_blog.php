<div>
<?

$i = $_POST['i']+1;
$j=-1;

$blog_aql = "blog{
                  name as blog_name
                  where active=1
                  order by name asc
               }";

$blog_dropdown = array(
   'select_name' => "assignment_{$i}_blog_ide",
   'value_field' => 'blog_ide',
   'option_field' => 'blog_name',
   'null_option' => '- Blog -'
);

aql::dd($blog_aql,$blog_dropdown);

?><script type='text/javascript'> var bas_i_<?=$i?>_j = 0;  </script><input id='blog_author_assignment_add_market_<?=$i?>' type='button' value='Add New Market' onclick='add_new_market(<?=$i?>,bas_i_<?=$i?>_j++,this.id);' /><?

?>
</div>
