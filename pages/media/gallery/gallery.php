<div id='<?=$id ?>_gallery' class='<?=$class ?> gallery preloaded'>
   <div id='<?=$id ?>_gallery_pane_wrap' class='gallery_pane_wrap'>
      <div id='<?=$id ?>_gallery_pane' class='gallery_pane'>
      </div>
      <div class='arrow_wrap arrow_wrap_left' onclick='galleries["<?=$id ?>"].previous();'>
         <div id='<?=$id ?>_pane_leftnav' class='arrow pane_leftnav' onclick='/*galleries["<?=$id ?>"].previous();*/'>
         </div>
      </div>
      <div class='arrow_wrap arrow_wrap_right' onclick='galleries["<?=$id ?>"].next();'>
         <div id='<?=$id ?>_pane_rightnav' class='arrow pane_rightnav' onclick='/*galleries["<?$id ?>"].next();*/'>
         </div>
      </div>
	  <?
	  if (!$disable_enlarge) {
		?>
		  <div id='<?=$id ?>_gallery_enlarge' class='gallery_enlarge' onclick='galleries["<?=$id ?>"].enlarge();'>
			 Enlarge
		  </div>
		<?
	  }
	  ?>
   </div>
   <div id='<?=$id ?>_gallery_grid' class='gallery_grid'>
      <? $index_x = -1;
         $index_y = -1;
         $index = 0;
         foreach($gallery_images as $gallery_image){
            if($_GET['debug']){
               var_dump($gallery_image);
            }

            $index_x++;
            if($index_x%$gallery_grid_x ==0){
               $index_y++;
               if($index_y!=0){?></div><?}
               ?><div id='<?=$id ?>_grid_row_<?=$index_y ?>' class='<?=$id ?>_grid_row grid_row <?=($index_y==0)?'grid_row_first':(($index_y+1)==$gallery_grid_y?'grid_row_last':'') ?>'><?
            }
            ?><img id='<?=$id ?>_grid_cell_<?=$index_y.'_'.$index_x ?>' class='<?=$id ?>_grid_cell grid_cell <?=($index_x%$gallery_grid_x==0)?'grid_cell_first':(($index_x+1)%$gallery_grid_x==0?'grid_cell_last':'') ?>' src='<?=$gallery_image['image']['src'] ?>' onclick='galleries["<?=$id ?>"].galleryFocus(<?=$index++ ?>);'><?
               
         } 
         echo "</div>";      
      ?>
   </div>
</div>

<script type='text/javascript'>

   /*   var galleryid = '<?=$id?>';

   alert(<?=$id?>);
   alert(galleryid);   */

   //fix for google chrome
   var safeCheck_tries = 0;
   var safeCheck_maxTries = 100;
   function safeCheck(func,callback){
      try{
         if(++safeCheck_tries>=safeCheck_maxTries){alert('failure');return;}
         setTimeout(func?callback:function(){safeCheck(func,callback);},250);
      }catch(e){alert(e + "safeCheck");}
   }

   function <?=$id ?>_finish(){
      try{
         if(++safeCheck_tries>=safeCheck_maxTries){/*alert('failure')*/;return;}
         galleries['<?=$id ?>']=new gallery('<?=$id ?>');
         $(document).ready(function(){try{galleries['<?=$id ?>'].doIt();}catch(e){alert(e);}});
      }catch(e){
         setTimeout(<?=$id ?>_finish,250);
      }
   }

   try{
      safeCheck(gallery, <?=$id ?>_finish);
   }catch(e){
      <?=$id ?>_finish();
   }

</script>
