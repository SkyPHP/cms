function codebase_editor(obj){
	var codebase = $(obj).parent().parent().find('.codebase').html();
	var table = $(obj).parent().parent().parent().find('.tablename').html();	
	var url = '/dev/db/codebase-editor?codebase='+codebase+'&table='+table;
	skybox(url,640);
}

function find_table(){
	$('#find_loading').show();
	setTimeout(function(){
		val = $('#find').val();
		if(val.length>0){
			$("[name*="+val+"].table_row").show();
			$(".table_row").not("[name*="+val+"]").hide();
		}else{
			$('.table_row').show();
		}
		$('#find_loading').hide();
	},500);
	

}
function save_codebase(){
	var codebase = $('#codebase_name').val();
	var table = $('#table_name').val();
	$.post( "/dev/db/save-codebase/" + table + "/" + codebase, function(data)	{
																						if(data=='success'){
																							$('#'+table+'_row').find('.codebase').text(codebase);
																							history.back();
																						}else{
																							alert(data);
																						}
																					});
}

function toggle(data) {
	if ( data == 'on') {
		$('#button_'+tablename).attr('class','disable');
		$('#button_'+tablename).html('disable');
		$('#onoff_'+tablename).attr('class','rep_on');
		$('#onoff_'+tablename).html('ON');
	} else if (data == 'off') {
		$('#button_'+tablename).attr('class','enable');
		$('#button_'+tablename).html('enable');
		$('#onoff_'+tablename).attr('class','rep_off');
		$('#onoff_'+tablename).html('OFF');
	} else {
		alert(data);
	}
}

$('.enable').live('click', function() {
	tablename = $(this).attr('tablename');
	$.post( "/dev/db/toggle-replication/" + tablename + "/enable", toggle );
});

$('.disable').live('click', function() {
	tablename = $(this).attr('tablename');
	$.post( "/dev/db/toggle-replication/" + tablename + "/disable", toggle );
});

function sql_editor(table,fn) {
	url = '/dev/db/sql-editor';
	if (fn) url = url + '/' + fn;
	if (table) url = url + '/' + table;
	skybox(url,640);
}
function enter_codebase(){
	var search = '"codebase":"';
	var sql = $('#sql_editor').val();
	var codebase_name = $('#codebase_name').val();
	var start_pos = sql.search(search)+search.length;
	var end_pos = sql.search('"}\';');
	if(start_pos && end_pos){
		var new_sql = sql.substr(0,start_pos)+codebase_name+sql.substr(end_pos);
		$('#sql_editor').val(new_sql);
	}
}
String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

function execute_sql(table) {
	if(table=='my_table'){
		var search = 'create table';
		var sql = $('#sql_editor').val();
		var start_pos = sql.search(search,"i")+search.length;
		var end_pos = sql.indexOf('(', start_pos);
		var table = sql.substring(start_pos,end_pos).trim();
	}
	$('#execute').html('<img src="/images/loading2.gif" />');
	$.post( 
		"/dev/db/execute",
		{
			sql:$('#sql_editor').val(),
			table:table
		},
		function(data){
			$('#execute').html(data);
		}
	);	
}

function backup_table(table) {
	$('#backup_'+table).html('<img src="/images/loading2.gif" />');
	$.post( 
		"/dev/db/backup-table",
		{table:table},
		function(data){
			$('#backup_'+table).html(data);
		}
	);
}

function toggle_replication(){
   var tables_turn_on = '';
   var tables_turn_off = '';
 
   $('.rep_checkbox').each(function(){
      var id = $(this).attr('id');
 
      var pat = /rep_checkbox_(\S+)/;

      var matches = pat.exec(id);

      var table_name = matches[1];
 
      var orig_state = eval('rep_checkbox_orig_state_'+table_name);
      if(!orig_state && (orig_state != $(this).attr('checked'))){
         tables_turn_on+=((tables_turn_on?',':'')+table_name);
      }else{
         if(orig_state && (orig_state != $(this).attr('checked'))){
            tables_turn_off+=((tables_turn_off?',':'')+table_name);
         }
      }
   });

   if(tables_turn_on){
      clear_message();
      ajax_func('enable',tables_turn_on,tables_turn_off?function(data){
         enable_success(data,tables_turn_on);
         ajax_func('disable',tables_turn_off,function(dataa){disable_success(dataa,tables_turn_off);});
      }:function(data){enable_success(data,tables_turn_on);});
   }else{
      if(tables_turn_off){
         clear_message();
         ajax_func('disable',tables_turn_off,function(data){disable_success(data,tables_turn_off);});
      }
   }

}

var message_div_id = "tableadmin_message";
var loading_gif_div_id = "loading_gif";

function loading_gif(){
   $('#'+message_div_id).before("<img id='"+loading_gif_div_id+"' src='/images/loading2.gif' />");
}

function clear_message(){
   $('#'+message_div_id).html(null);
}

function success(content){
   $('#'+message_div_id).html($('#'+message_div_id).html()+"<br />"+content).css('display','block').addClass('aql_saved');
   $('#'+loading_gif_div_id).remove();
}

function enable_success(content,tables){
   if(content.indexOf("Successfully added tables and sequences to replication")>=0){
      var dump = tables.split(',');
      for(i in dump){
         $('#onoff_'+dump[i]).removeClass('rep_off').addClass('rep_on').html('ON');
         $('#rep_checkbox_'+dump[i]).attr('checked','true');
         $('#button_'+dump[i]).val('disable');
         eval('rep_checkbox_orig_state_'+dump[i]+'=true;');
      }
   }

   success(content);
}

function disable_success(content,tables){
   var dump = tables.split(',');
   for(i in dump){
      if(content.indexOf("Successfully dropped table "+dump[i]+" from")>=0){
         $('#onoff_'+dump[i]).removeClass('rep_on').addClass('rep_off').html('OFF');
         $('#rep_checkbox_'+dump[i]).attr('checked',null);
         $('#button_'+dump[i]).val('enable');
         eval('rep_checkbox_orig_state_'+dump[i]+'=false;');
      }
   } 

   success(content);
}

function ajax_func(func,tables,callback){
   loading_gif();
   $.ajax(
      {'type':'POST',
       'url':'/dev/db/toggle-replication/-multiple-/'+func,
       'data':'tables='+tables,
       'success':function(data, textStatus, XMLHttpRequest){(callback || success)(data, textStatus, XMLHttpRequest, tables);}
      }
   );
}














