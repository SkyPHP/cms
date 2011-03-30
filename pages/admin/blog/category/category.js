function save_blog_category(theform, ide) {
	
	document.getElementById('save_'+ide).innerHTML = 'saving...';
	theform.action = '/admin/blog/category/save/' + ide;
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			 
			document.getElementById('save_'+ide).innerHTML = req.responseText;
		}
	});	
	
}

function delete_blog_category(theform, ide)
{
	if(confirm("Are you sure want to delete")){
	theform.action = '/admin/blog/category/delete/' + ide;
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			window.location.href = window.location.href;
		}
	});	
	
			   }
	 
	
	
	
	
}

function add_category(theform)
{
	
	 
	theform.action = '/admin/blog/category/add/';
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			window.location.href = window.location.href;
		}
	});	
	
}