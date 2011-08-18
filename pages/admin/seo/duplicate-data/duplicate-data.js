$(function() {
	
	table = $('#table').val();
	
	$('.filter').live('click',function(e) {
		$this = $(this);
		filter = $this.attr('filter');
		$this.css('border-bottom', 'none');
		$('#'+filter).slideDown('fast',function() {
			$(this).removeClass('filter').addClass('filter-on');
		});
		e.stopImmediatePropagation();
	});
	
	$('.filter-on').die().live('click',function(e) {
		$this = $(this);
		filter = $this.attr('filter');
		$('#'+filter).slideUp('fast',function() {
			$this.css('border-bottom', '2px solid #999');
		});
		$(this).removeClass('filter-on').addClass('filter');
		e.stopImmediatePropagation();
		
	});
	
	$('.filter_cb').die().live('click',function() {
		value = $(this).val();
		filter = $(this).attr('filter');
		or = $('#or').val();
		type = $(this).attr('type');
		if ($(this).attr('checked')) sw = 'on';
		else sw = 'off';
		$.post('/admin/seo/duplicate-data/ajax/filter-listing',{ sw: sw, filter: filter, type: type, value: value, or: or }, function(data){
			$('#listing').html(data);
		});
	});
	
	$('.listing_radio').die().live('click',function() {
		val = '';
		$('.listing_radio').each(function() {
			if ($(this).attr('checked')) {
				val += ' '+$(this).attr('phrase');	
			}
		});		
		$('#final-phrase').val(val);
	});
	
	$('#container').live('click',function () {
		$('.filter-list').slideUp('fast',function() {
			$('.filter-on').removeClass('filter-on').addClass('filter').css('border-bottom', '2px solid #999');
		});
	});
	$('.filter-list').live('click', function(e) {
		e.stopPropagation();	
	});
});