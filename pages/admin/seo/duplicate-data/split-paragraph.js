$(function() {
	$("#split").live('click',function() {
		var val = $('#paragraph').val()
		if (val) {
			$.post('/admin/seo/duplicate-data/ajax/split',{ val: val }, function(data) {
				$('#results').html(data);
			});
		}
	});
	
	$('input[name=auto-switch]').live('change',function() { 
		if ($(this).val() == 'auto') {
			$('.manual-order').fadeOut('slow');
		}
		else {
			$('.manual-order').fadeIn('slow');
		}
	});
	$('button .hide').live('click',function(){
		$this = $(this);
		$('.hideable').slideUp('slow',function() {
			$this.html('SHOW +').removeClass('hide').addClass('show');
		});
	});
	$('button .show').live('click',function(){
		$this = $(this);
		$('.hideable').slideDown('slow',function() {
			$this.html('HIDE -').removeClass('show').addClass('hide');
		});
	});
});