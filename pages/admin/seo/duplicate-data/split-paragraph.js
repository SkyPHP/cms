$(function() {
	$("#split").live('click',function() {
		var val = $('#paragraph').val()
		if (val) {
			
			$.post('/admin/seo/duplicate-data/ajax/split.php',{ val: val }, function(data) {
				$('#result').html(data);
			});
		}
	});
});