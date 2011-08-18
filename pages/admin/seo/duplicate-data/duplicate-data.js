var mouse_is_inside = false;

$(function() {

    $('.filter-area, .filter').hover(function(){ 
        mouse_is_inside=true; 
    }, function(){ 
        mouse_is_inside=false; 
    });

    $("#container").mouseup(function(){ 
        if(! mouse_is_inside) $('.filter-area').slideUp('fast',function() {
			$('.filter-on').css('border-bottom', '2px solid #999').removeClass('filter-on').addClass('filter');	
		});
    });

	table = $('#table').val();
	
	$('.filter').live('click',function() {
		$this = $(this);
		filter = $this.attr('filter');
		$this.css('border-bottom', 'none');
		$('#'+filter).slideDown('fast');
		$this.removeClass('filter').addClass('filter-on');
	});
	
	$('.filter-on').live('click',function() {
		$this = $(this);
		filter = $this.attr('filter');
		$('#'+filter).slideUp('fast',function() {
			$this.css('border-bottom', '2px solid #999');
		});
		$(this).removeClass('filter-on').addClass('filter');
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
	
	$('.a-or-m-switch').live('change',function() {
		val = $(this).val();
		$('.a-or-m .on').removeClass('on').slideUp('fast',function() {
			$('#'+val).addClass('on').slideDown('slow');
		});
	})
	
});