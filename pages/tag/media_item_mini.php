<?

//select media_item_id from media_item_tag where lower(name)=lower('amy winehouse') and active=1;

$grid_x = 6;
$grid_y = 2;

$limit_mini = $grid_x * $grid_y;
 
$sql = "select distinct on (mit.media_item_id) mit.media_item_id, pe.id as photog_event_id, m.slug as market_slug from media_item_tag as mit left join media_item as mi on mit.media_item_id=mi.id left join photog_event as pe on mi.media_vfolder_id=pe.media_vfolder_id left join venue as v on pe.venue_id=v.id left join market as m on v.market_id=m.id where lower(mit.name)=lower('$current_tag') and mit.active=1 limit $limit_mini";

echo "<!-- $sql -->";

$res = $db->Execute($sql) or die($db->ErrorMsg());//aql::select($aql);

$rs = array();

while(!$res->EOF){
   $arr = $res->fields;
   $arr['media_item_ide']=encrypt($arr['media_item_id'],'media_item');
   $arr['photog_event_ide']=encrypt($arr['photog_event_id'],'photog_event');
   $rs[]=$arr;

   $res->MoveNext();   
}

$height = 98;
$width = 98;

echo "<!--";
var_dump($rs);
echo "-->";

if(count($rs)){
   ?>
      <div id='tag-media-results-mini'>
         <div id='tag-media-results-mini-head'>
            Images:
         </div>
         <div id='tag-media-results-mini-body'>
   <?
   $count = count($rs);

   echo "<div class='tag-media-result-row-mini'>";
   foreach($rs as $i=>$r){
      if($i%$grid_x==0 && $i){echo "<div class='tag-media-result-row-mini ".(($count-($i+1))<$grid_x?"last":"")."'>";}
      $item_thumb = media::get_item($r['media_item_id'],$width,$height,true);  

      echo "<a href='/{$r['market_slug']}/photos/image/{$r['photog_event_ide']}/{$r['media_item_ide']}' ><img class='tag-media-img-mini ".((($i+1)%$grid_x)==0?"rightmost":"")."' src='{$item_thumb['src']}' /></a>";
      
      if((($i+1)%$grid_x)==0){echo "<div class='clear'></div></div>";}
   }
   if((($i+1)%$grid_x)!=0){echo "<div class='clear'></div></div>";}

   echo "</div></div>";
}

?>
