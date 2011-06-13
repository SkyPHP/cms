function gallery(id){

var galleryVars = eval(id+'_galleryVars');

var currentSlide = 0;

function galleryFocus(i){
   $('#'+id+'_gallery_pane').cycle(i);
   $('.'+id+'_grid_cell_selected').removeClass('grid_cell_selected '+id+'_grid_cell_selected');
   $('#'+id+'_grid_cell_'+Math.floor(i/galleryVars['grid_x'])+'_'+(i)).addClass('grid_cell_selected '+id+'_grid_cell_selected');
   currentSlide = i;
   arrowClasses();
}

function doIt(){
   for(var i=0;i<galleryVars.length;i++){
      $('#'+id+'_gallery_pane').append("<img src='"+galleryVars[i]['bigimage']+"' style='z-index:"+(i+19)+"'>");
   }

   //fix for google chrome
   function finish(){
      $('#'+id+'_gallery_pane').cycle({timeout: 0, fx: galleryVars['transition'], speed: galleryVars['speed'], easing: galleryVars['easing']?galleryVars['easing']:null});
      galleryFocus(0);
      $('#'+id+'_gallery').removeClass('preloaded');
   }

   function safeCheck(){      
      setTimeout(($('#'+id+'_gallery_pane').cycle?finish:safeCheck),250);
   }

   safeCheck();
}

function next(){
   if(arrows_wrap && currentSlide==galleryVars.length-1){
      return;
   }
   
   galleryFocus(currentSlide=(currentSlide+1)%galleryVars.length);
}

function previous(){
   if(arrows_wrap && currentSlide==0){
      return;
   }

   galleryFocus(currentSlide=(((currentSlide-1)%galleryVars.length)+galleryVars.length)%galleryVars.length);
}

function arrowClasses(){

   $('#'+id+"_gallery_pane_wrap").removeClass('onlast onfirst');
   if(currentSlide == galleryVars.length-1){
      $('#'+id+"_gallery_pane_wrap").addClass('onlast');
   }
   if(currentSlide == 0){
      $('#'+id+"_gallery_pane_wrap").addClass('onfirst');
   }
}

function enlarge(){
   var str = '<div class="skybox-close-button"><a href="javascript:history.back();">Close [x]</a></div><a href="javascript:history.back();"><img src="'+galleryVars[currentSlide]['enlargedimage']+'" /></a></div>';

   skybox(str,galleryVars[currentSlide]['enlargedimage_width']);
}

function showArrows(e){
   alert(e.pageX+" "+e.pageY);
}

this.galleryFocus=galleryFocus;
this.doIt = doIt;
this.next = next;
this.previous = previous;
this.enlarge = enlarge;
}
