$(function() {
	$('#name').live('keyup',function() {
		if ($(this).val()) $('#split').show();
		else $('#split').hide();
	});
	
	$("#split").live('click',function() {
		var val = $('#paragraph').val()
		var name = $('#name').val();
		var source = $('#source').val();
		if (val) {
			$.post('/admin/seo/duplicate-data/ajax/split',{ val: val, source: source, name: name }, function(data) {
				$('#results').html(data);
			});
		}
	});
	
	$('input[name=auto-switch]').die().live('change',function() { 
		if ($(this).val() == 'auto') {
			$('#auto-sentences').html('<img src="/images/loading.gif">');
			data1 = {};
			c = 0;
			$('.sentence').each(function(index,element) {
				c++;
				$this = $(this);
				eval("data1.sentence" + index + "_id = $this.attr('dup_sentence_data_id')");
			});
			data1.no_sentences = c;
			$('.manual-order').fadeOut('slow',function() {
				$.post('/admin/seo/duplicate-data/ajax/auto-sentences',data1,function(data) {
					$('#auto-sentences').html(data).slideDown('fast');
				});
			});
			$('#switch-on').val('auto');
		}
		else {
			$('#auto-sentences').slideUp('fast',function() {
				$('#switch-on').val('manual');
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
	$("#save-sentences").live('click',function() {
		if ($('#switch-on').val() == 'manual') {
		
		}
		else {
			
		}
	});
});