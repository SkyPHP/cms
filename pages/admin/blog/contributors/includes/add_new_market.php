<?

$i = $_POST['i'];
$j = $_POST['j'];
 
$market_aql = "market{
                  name as market_name
                  where market.primary=1
                  order by name asc
               }";

$market_dropdown = array(
   'select_name' => "assignment_{$i}_market_ide_{$j}",
   'value_field' => 'market_ide',
   'option_field' => 'market_name',
   'null_option' => '- Market -'
);

aql::dd($market_aql,$market_dropdown);

?>
