<?

$blog_author_ide = $_POST['blog_author_ide'];
$blog_author_id = decrypt($blog_author_ide,'blog_author');

$aql = "
market {
   name as market_name
   where market.primary = 1
   order by name asc
}";

$markets = aql::select($aql);

$new_market_ids = array();

foreach($markets as $market){
   if($_POST['market_'.$market['market_ide']]){
      $new_market_ids[] = $market['market_id'];
   }
}

$where = 'market_id not in ('.implode(',',$new_market_ids).')';

$aql = "
blog_author_market{
   market_id
   where blog_author_id=$blog_author_id and active=1
}";

//'bam' short for 'blog_author_market'

$all_bam = aql::select($aql);

if(is_array($all_bam)){
foreach($all_bam as $bam){
   if(!in_array($bam['market_id'],$new_market_ids)){
      aql::update('blog_author_market',array('active'=>0),$bam['blog_author_market_id']);
   }else{
      unset($new_market_ids[array_search($bam['market_id'],$new_market_ids)]);
   }   
}
}

foreach($new_market_ids as $new_market_id){
   aql::insert('blog_author_market',array('market_id'=>$new_market_id,'blog_author_id'=>$blog_author_id));
}


?>
