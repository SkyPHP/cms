function master__slony_node_ide(){
   $('#slony_cluster_form').append('<input type="hidden" name="master__slony_node_ide" value="'+$('#slony_node_ide').val()+'" />');
}
 
function cluster_ide(){
   $('#slony_node_form').append('<input type="hidden" name="slony_cluster_ide" value="'+$('#slony_cluster_ide').val()+'" />');
}

function cluster_name(){
   $('#slony_node_form').append('<input type="hidden" name="slony_cluster_name" value="'+$('#slony_cluster_name').val()+'" />');
}

var message_div_id = "slony_cluster_message";
var loading_gif_div_id = "loading_gif";

function ajax_func(func,ide,callback){
   loading_gif();
   $.ajax(
      {'type':'POST',
       'url':'/dev/slony/ajax',
       'data':'func='+func+(ide?'&a='+ide:''),
       'success':function(data, textStatus, XMLHttpRequest){(callback || success)(data, textStatus, XMLHttpRequest, ide);}
      }
   );
}

//this function has a different syntax than our other ajax functions
//don't be careless in using it
function ajax_kill(ide,pid,callback){
   var func='kill';

   loading_gif();
   $.ajax(
      {'type':'POST',
       'url':'/dev/slony/ajax',
       'data':'func='+func+(ide?'&a='+ide:'')+(pid?'&b='+pid:''),
       'success':function(data, textStatus, XMLHttpRequest){(callback || success)(data, textStatus, XMLHttpRequest, ide, pid);}
      }
   );
}

function ajax_start(ide,callback){
   var func='start';
   ajax_func(func,ide,callback);  
}

function ajax_stop(ide,callback){
   var func='stop';
   ajax_func(func,ide,callback);
}

function ajax_drop(ide,callback){
   var func='drop';
   ajax_func(func,ide,callback);
}

function ajax_subscribe(ide,callback){
   var func='subscribe';
   ajax_func(func,ide,callback);
}

function ajax_unsubscribe(ide,callback){
   var func='unsubscribe';
   ajax_func(func,ide,callback);
}

function ajax_promote(ide,callback){
   var func='promote';
   ajax_func(func,ide,callback);
}

function ajax_uninstall(ide,callback){
   var func='uninstall';
   ajax_func(func,ide,callback);
}

function ajax_restart(ide,callback){
   var func='restart';
   ajax_func(func,ide,callback);
}

function ajax_cluster_status(ide,callback){
   var func='status';
   ajax_func(func,ide,callback);
}

function loading_gif(){
   $('#'+message_div_id).before("<img id='"+loading_gif_div_id+"' src='/images/loading2.gif' />");
}

function success(content){
   $('#'+message_div_id).html(content).css('display','block').addClass('aql_saved');
   $('#'+loading_gif_div_id).remove();
}

function drop_callback(data,a,b,ide){
   success(data);
   $('#node_row_'+ide).addClass('aql_deleted');
   $('#node_row_'+ide+' input').attr('disabled','true');
}

function subscribe_callback(data,a,b,ide){
   success(data);
   if(data.indexOf('unsubscribe')>=0){
      if(data.indexOf('Fail')>=0){
         //our attempt failed
         $('#subscribed_'+ide).html('Subscribed');
         $('#button_subscribe_'+ide).val('Unsubscribe');
         $('#button_promote_'+ide).css('display','block');
      }else{
         $('#subscribed_'+ide).html('Not Subscribed');
         $('#button_subscribe_'+ide).val('Subscribe');
         $('#button_promote_'+ide).css('display','none');
      }  
   }else{
      if(data.indexOf('Fail')>=0){
         //our attempt failed
         $('#subscribed_'+ide).html('Not Subscribed');
         $('#button_subscribe_'+ide).val('Subscribe');
         $('#button_promote_'+ide).css('display','none');
      }else{
         $('#subscribed_'+ide).html('Subscribed');
         $('#button_subscribe_'+ide).val('Unsubscribe');
         $('#button_promote_'+ide).css('display','block');
     }
   }
}

function promote_callback(data,a,b,ide){
   success(data);
   if(data.indexOf('Success')>=0){
      $('.master_node .slony_role').html('Slave');
      $('.master_node').removeClass('master_node');
      $('#node_row_'+ide).addClass('master_node');
      $('.master_node .slony_role').html('Master');
      $('input').attr('disabled','true');
  }
}

function cluster_status_callback(data,a,b,ide){
   $('#'+loading_gif_div_id).remove();

   $('#cluster_status').html(data);
}

function start_callback(data,a,b,ide){
   success(data);

   refresh_pids();
}

function stop_callback(data,a,b,ide){
   success(data);

   refresh_pids();
}

function restart_callback(data,a,b,ide){
   success(data);

   refresh_pids();
}

function refresh_pids(){
   if(nodes){
      for(i in nodes){
         var span_class = nodes[i]['flag']?'sdm_warning':'sdm_good';
         var span = "<span class='"+span_class+"' >";

         $('#node_comment_'+nodes[i]['ide']).html(span+nodes[i]['comment']+"</span>");

         /*if(!(nodes[i]['worker_pid'] && nodes[i]['watchdog_pid'] && nodes[i]['slon_watchdog_pid'])){
            $('#node_warning_'+nodes[i]['ide']).html("This node is not running correctly, a restart is strongly recomended!");
         }else{
            $('#node_warning_'+nodes[i]['ide']).html(null);
         }*/
      }
      nodes = null;
   }

   ajax_cluster_status(null,cluster_status_callback);
}
