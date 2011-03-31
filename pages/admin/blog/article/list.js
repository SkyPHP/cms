function showgrid(theform)
{
 	document.getElementById('gridcontent').innerHTML = 'Loading...';
	theform.action = '/admin/blog/article/grid/';
	theform.method = 'post';
	AjaxRequest.submit(theform,{
		'onSuccess' : function(req){
			
			document.getElementById('gridcontent').innerHTML = '';
			document.getElementById('gridcontent').innerHTML = req.responseText;
			document.getElementById('testData').innerHTML = req.responseText;
			document.getElementById('showGridData').innerHTML = req.responseText;
		}
	});	
	
}

 
function submit(theform)
{
	
	document.theform.submit();	
	
	
}
 