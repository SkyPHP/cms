$(function() {
	$("#split").live('click',function() {
		var val = $('#paragraph').val()
		if (val) {
			$.post('/admin/seo/duplicate-data/ajax/split',{ val: val }, function(data) {
				$('#results').html(data);
			});
		}
	});
	
	$('input[name=auto-switch]').die().live('change',function() { 
		if ($(this).val() == 'auto') {
			data1 = {};
			c = 0;
			$('.sentence').each(function(index,element) {
				c++;
				$this = $(this);
				eval("data1.sentence" + index + " = $this.val()");
			});
			data1.no_sentences = c;
			if (c > 4) data1.limit = 25;
			$('.manual-order').fadeOut('slow',function() {
				$.post('/admin/seo/duplicate-data/ajax/auto-sentences',data1,function(data) {
					$('#auto-sentences').html(data).slideDown('fast');
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