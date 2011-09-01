$(function() {
	$("#split").live('click',function() {
		if ($('#paragraph').val()) {
			$.post('/admin/seo/duplicate-data/ajax/split.php',{ val: val }, function(data) {
				$('#result').html(data);
			});
		}
	});
});