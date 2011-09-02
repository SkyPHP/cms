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
			data = {};
			c = 0;
			$('.sentence').each(function(index,element) {
				c++;
				$this = $(this);
				eval("data.sentence" + index + " = $this.val()");
			});
			data.no_sentences = c;
			$('.manual-order').fadeOut('slow',function() {
				$.post('/admin/seo/duplicate-data/ajax/auto-sentences',data,function(data) {
					$('#auto-sentences').html('data').slideDown('fast');
				});
				
			});
		}
		else {
			$('#auto-sentences').slideUp('fast',function() {
				$('.manual-order').fadeIn('slow');
			});
		}
	});

	$('#hide-or-show').live('click',function(){
		$this = $(this);
		if ($(this).attr('do') == 'hide') 
			$('.hideable').slideUp('fast',function() {
				$this.html('SHOW ORIGINAL+').attr('do','show');
			});
		else 
			$('.hideable').slideDown('fast',function() {
				$this.html('HIDE ORIGINAL-').attr('do','hide');
			});
	});

});