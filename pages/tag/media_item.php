<?

//select media_item_id from media_item_tag where lower(name)=lower('amy winehouse') and active=1;
$limit=9;  

$aql = "
   media_item_tag{
      media_item_id
      where lower(name)=lower('$current_tag') and active=1
      limit $limit
      offset $offset
   }
";

$rs = aql::select($aql);

echo "<div id='tag-media-results'>";

if(is_array($rs)){
   $i=0;
   echo $start_row = "<div class='tag-media-result-row'>";
   foreach($rs as $r){
      if($i%3==0 && $i){echo $start_row;}
      $item_thumb = media::get_item($r['media_item_id'],195,293,true);
      $item_fullres = media::get_item($r['media_item_id']);      

      echo "<div id='tag-media-img-$i' class='tag-media-img-container clickable' onclick='skybox_image(\"{$item_fullres['src']}\",{$item_fullres['width']});'><img class='tag-media-img' src='{$item_thumb['src']}' /></div>";
      
      if(++$i%3==0){echo "<div class='clear'></div></div>";}
   }
   if($i%3!=0){echo "<div class='clear'></div></div>";}

}else{
   include('noresult.php');
}

echo "</div>";

?>
