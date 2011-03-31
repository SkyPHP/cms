function blog_author_skybox(ide) {
	if (!ide) title = 'Add Contributor';
	else title = 'Edit Contributor';
	model = 'blog_author';
	querystring = '';
	div_id = 'blog_author';
	refresh_div_uri = '/admin/blog/contributors/' + document.getElementById('client_ide').value;
	open_skybox_profile(title,model,ide,querystring,div_id,refresh_div_uri);
}

function blog_author_market_save(form_id,model,onSuccessFn,onErrorFn){
    theform = document.getElementById(form_id);
    message_div = form_id + "_message";
    if (document.getElementById(message_div)) { 
       document.getElementById(message_div).innerHTML = "<img src=\"/images/loading2.gif\" />";
    }
    if (!onSuccessFn) { 
       onSuccessFn = function (req) {
          if (req.responseText.indexOf("<!--saved-->") == 0) {
             needle = "<!--ide=";start = req.responseText.indexOf(needle) + needle.length;
             end = req.responseText.indexOf("-->", start);
             ide = req.responseText.substring(start, end);
             if (ide) {
                start = 0;
                end = location.href.indexOf("/add-new");
                if (end > -1) {
                   new_href = location.href.substring(start, end);
                } else {
                   new_href = location.href;
               }location.href = new_href + ("/" + ide);
             } else {
                document.getElementById(message_div).innerHTML = req.responseText;
                $.scrollTo(0, 1000);
             }
          } else if (document.getElementById(message_div)) {
             document.getElementById(message_div).innerHTML = req.responseText;
             $.scrollTo(0, 1000);
          }
       }; 
    } if (!onErrorFn) {
        onErrorFn = function (req) {
           alert("There has been an error. Check your form action.");
        }; 
   }
   theform.method = "post";
   theform.action = "/admin/blog/contributors/includes/add_staff";
   AjaxRequest.submit(theform, {onSuccess: onSuccessFn, onError: onErrorFn}); 
}

function add_new_assignment(pide,fid){
   $.ajax({
      'type':'POST',
      'url':'/admin/blog/contributors/includes/add_new_assignment',
      'data':'pide='+pide+'&fid='+fid,
      'success':function(dat){
         if(dat.indexOf('<!--success-->')==0){

            $('<fieldset id="blog_author_fieldset_'+fid+'"></fieldset>').insertAfter($('#blog_author_fieldset_'+(fid-1)));//.html(dat);      
            document.getElementById('blog_author_fieldset_'+fid).innerHTML=dat;  //this used instead of jquery to avoid jquery bug in firefox
            
            //following to fix firefox bug with stripping of form tags in innerHTML
            if(!document.getElementById('blog_author_form_'+fid)){
               $('<form id="blog_author_form_'+fid+'"> </form>').append($('#blog_author_fieldset_'+fid)).insertAfter($('#blog_author_fieldset_'+(fid-1)));
//               $('#blog_author_form_'+fid).add($('#blog_author_fieldset_'+fid));
            }
         }
      }
   });
} 

var inactive_showing = false;
function toggle_inactive(){
   $('.blog_author_inactive').css('display',inactive_showing?'none':'block');  
   $('#toggle_inactive').html(inactive_showing?'Show Inactive':'Hide Inactive');

   inactive_showing = !inactive_showing;
}

function save_button(save_markets,form_id,model,success_callback,failure_callback){
   var message_div = form_id+'_message';
   function save_profile_callback(req){
   if (req.responseText.indexOf("<!--saved-->") == 0) {
      needle = "<!--ide=";
      start = req.responseText.indexOf(needle) + needle.length;
      end = req.responseText.indexOf("-->", start);
      ide = req.responseText.substring(start, end);
      if (ide) {
         start = 0;
         end = location.href.indexOf("/add-new");
         if (end > -1) {
            new_href = location.href.substring(start, end);
         } else {
            new_href = location.href;
         }
         location.href = new_href + ("/" + ide);
     } else {
        document.getElementById(message_div).innerHTML = req.responseText;
     }
   } else if (document.getElementById(message_div)) {
      document.getElementById(message_div).innerHTML = req.responseText;
   };
}


    success_callback = success_callback || save_profile_callback;

   
   function cleanup_commas(){
      $('#access_group_'+form_id).val($('#access_group_'+form_id).val().replace(/,+/g,',').replace(/^,/g,'').replace(/,$/g,''));
   }

   if($('#editor_'+form_id+':checked').val()){
      if( $('#access_group_'+form_id).val().indexOf('editor')==-1){
         $('#access_group_'+form_id).val($('#access_group_'+form_id).val()+',editor');
         cleanup_commas();
      }
   }else{
      if( $('#access_group_'+form_id).val().indexOf('editor')!=-1){
         $('#access_group_'+form_id).val($('#access_group_'+form_id).val().replace(/(^|,)editor/ig,''));
         cleanup_commas();
      }
   }

   

   if(save_markets){blog_author_market_save(form_id,'',success_callback);}

   save_primary_profile(form_id,model,success_callback,failure_callback);
}

function save_all(){
   var functions = new Array();
   $('.blog_author_form_save_button').each(function(a,b){functions[a]=b.click;})
}
