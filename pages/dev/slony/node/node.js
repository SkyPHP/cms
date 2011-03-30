function master__slony_node_ide(){
   $('#slony_cluster_form').append('<input type="hidden" name="master__slony_node_ide" value="'+$('#slony_node_ide').val()+'" />');
}

function cluster_name(){
   $('#slony_node_form').append('<input type="hidden" name="slony_cluster_name" value="'+$('#slony_cluster_name').val()+'" />');
}

function save_new_cluster(data){

 

 var model = "slony_node";

 var new_href = "/dev/slony/node";
 var options = null;
 var onSuccessFn = null;

 var theform = $('form[name='+model+']:first, form[id='+model+'_form]');
 var silent = false;
 var onFailFn2 = null;
 var onSuccessFn2 = null

 var get = "?cluster=1";

 var message_div = model + '_message'; 

 if ( trimString(data).indexOf('<!--saved-->') != 0 ) {
    if(!silent){
       $( '#'+message_div ).html(data);
       $.scrollTo(0,1000);
    }
    if (onFailFn2)
       eval(onFailFn2);
    } else {
       var needle = '<!--ide=';
       var start = data.indexOf(needle) + needle.length;
       var end = data.indexOf('-->',start);
       var ide = data.substring(start,end);
    if (ide) {
       var date = new Date();
       date.setTime(date.getTime()+600000);
       var expires = " expires="+date.toGMTString();
       document.cookie = 'aql_state'+"="+'saved_'+model+';'+expires+"; path=/";
          if(!new_href){
             // get the url up to /add-new
             start = 0;
             end = location.href.indexOf('/add-new');
             if ( end > -1 )
                new_href = location.href.substring(start,end); // profile page add new
             else new_href = location.href; // submit page add new
             location.href = new_href + '/' + ide;
          } else {
             location.href = new_href + '/' + ide;
          }
    } else {
       if(!silent){
          $( '#'+message_div ).html(data);
          $.scrollTo(0,1000);

       }
      if (onSuccessFn2)
         eval(onSuccessFn2);
   }
 }
}
