function checkout(){
	path = $('#path').val();
	repo = $('#repo').val();
	$('#output').html('<img src="/images/loading3.gif"/>');
	$('#output').load('/dev/checkout/ajax/functions',({	
														func:'checkout',
														path:path,
														repo:repo
													}),function(data){
														
														if(data=='success')
															$('#output').html("<div class='aql_saved'>Tag Created</div>");
														else{
															$('#output').html("<div class='aql_error'>Output (there might or might not be error):<br>"+data+"</div>");
														}
													});
	
}