var ajax_url = '/dev/db/repmgr/ajax';
var timeout = 2000;

function default_callback(data, textStatus){
   alert(data);
   if(typeof(console.log) == 'function'){
      console.log({'data':data, 'textStatus':textStatus});
   }
}

function ps_callback(node, data){
   $('#ps_' + node + '_error').html();

   if(!data['success']){
   //   $('#ps_' + node + '_error').html('Command failed:<br />' + data['output']);
   }

   refresh_ps_table(node, data['ps']);
}

function refresh_ps_table(node, ps){
   var div = $('#ps_' + node);
   var start = $('#ps_' + node + '_start');
 
   if(ps.length){
      div.html('<table id="ps_' + node + '_table" class="listing"></table>');

      var table = $('#ps_' + node + '_table');

      table.append("<tr><th>PID</th><th>User</th><th>Command</th><th></th></tr>");

      for(i in ps){
         var button = '<input type="button" value="Kill" onclick="repmgr_kill(' + node + ', ' + ps[i]['pid'] + ');" />';

         table.append("<tr><td>" + ps[i]['pid'] + "</td><td>" + ps[i]['user'] + "</td><td>" + ps[i]['cmd'] + "</td><td>" + button + "</td></tr>");
      }
      
      start.attr('disabled', 'disabled');
   }else{
      div.html('No repmgr processes running.');
      start.removeAttr('disabled');

   }

}

function send_ajax(params, callback, method, url, type){
   method = method || 'post';
   url = url || ajax_url;
   callback = callback || default_callback;
   type = type || 'json';  

   ajax_func = null;

   switch(method.toLowerCase()){
      case('get'):
         ajax_func = $.get;
         break;
      case('post'):
      default:
         ajax_func = $.post;
   }

   switch(typeof(params)){
      case('function'):
         //this is a weird case but we still should handle it
         params = params();
         if(typeof(params) != 'function'){
            //if params() returned a function, we should stop right now
            send_ajax(params, callback, method, url, type);
         }
         return;
      case('object'):
         params = array_to_param_string(params);
         break;
      case('string'):
      case('number'):
      case('undefined'):
      default:
   }

   ajax_func(url, (typeof(params) == 'object'?array_to_param_string(params):params), callback, type);
}

//We use the delay to compensate for replication lag
function refresh(){
   setTimeout(function(){document.location = '/dev/db/repmgr';}, timeout);
}

function array_to_param_string(arr){
   var return_string = '';   

   for(i in arr){
      return_string += (return_string?'&':'') + i + '=' + arr[i];
   }

   return(return_string);
}

function loading(id){
   $("<img id='" + id + "_loading' src='/images/loading.gif' />").insertBefore('#' + id);
}

function clear_loading(id){
   $('#' + id + '_loading').remove();
}

function repmgr_kill(node, pid, callback){
   send_ajax({'func':'kill', 'a':node, 'b':pid}, callback || function(data){ps_callback(node, data);});
}

function repmgr_start(node, callback){
   send_ajax({'func':'start', 'a':node}, callback || function(data){ps_callback(node, data);});
}

function repmgr_promote(node, callback){
   loading('standby_error');
   send_ajax({'func':'promote', 'a':node}, callback || refresh);
}

function repmgr_add_hard(node, callback){
   loading('unused_error');
   send_ajax({'func':'add_hard', 'a':node}, callback || refresh);
}

function add_soft_callback(data, textStatus){
   clear_loading('skybox_error');  

   if(data['success']){
      refresh();
      return;
   }

   $('#skyform_error').html(data['error']);
}

function repmgr_add_soft(cluster, conninfo, id, callback){
   loading('skybox_error');
   send_ajax({'func':'add_soft', 'a':cluster, 'b':conninfo, 'c':id}, callback || add_soft_callback);
}

function drop_soft_callback(data, textStatus){
   clear_loading('unused_error');

   if(data['success']){
      refresh();
      return;
   }

   $('#unused_error').html(data['error']);
}

function repmgr_drop_soft(node, callback){
   loading('unused_error');
   send_ajax({'func':'drop_soft', 'a':node}, callback || drop_soft_callback);
}

function drop_hard_callback(data, textStatus){
   clear_loading('standby_error');
   if(data['success']){
      refresh();
      return;
   }

   $('#standby_error').html(data['error']);
}

function repmgr_drop_hard(node, callback){
   loading('standby_error');
   send_ajax({'func':'drop_hard', 'a':node}, callback || drop_hard_callback);
}

function repmgr_cleanup(node, callback){
   loading('general_error');
   send_ajax({'func':'cleanup', 'a':node}, callback || refresh);
}



