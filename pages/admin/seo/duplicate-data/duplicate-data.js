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
		//$.post('/admin/seo/duplicate-data/ajax/auto-permetate',{ sw: sw, filter: filter, type: type, value: value, or: or }, function(data){
		//	$('#auto').html(data);
		//});
	});
	
	$('.listing_radio').die().live('click',function() {
		val = '';
		vals = new Array();
		$('.listing_radio').each(function(index) {
			if ($(this).attr('checked')) {
				val += ' '+$(this).attr('phrase');
				vals.push($(this).attr('phrase_id'));
			}
		});	
		$('#final-phrase').val(val);
		$('#final-phrase').attr('p1',vals[0]);
		$('#final-phrase').attr('p2',vals[1]);
		$('#final-phrase').attr('p3',vals[2]);
		if (vals[3]) $('#final-phrase').attr('p4',vals[3]);
	});
	
	$('.a-or-m-switch').die().live('change',function() {
		val = $(this).val();
		$('.a-or-m-on').slideUp('fast',function() {
			$('#'+val).addClass('a-or-m-on').slideDown('fast');
		}).removeClass('a-or-m-on');
	})
	
	$('#save-final').live('click',function() {
		var val = $('#final-phrase').val();
		var p1 = $('#final-phrase').attr('p1');
		var p2 = $('#final-phrase').attr('p2');
		var p3 = $('#final-phrase').attr('p3');
		var p4 = $('#final-phrase').attr('p4');
		var person_id = $('#person_id').val();
		if (val) {
			var data = {
				'phrase': val,
				'phrase1__dup_phrase_data_id' : p1,
				'phrase2__dup_phrase_data_id' : p2,
				'phrase3__dup_phrase_data_id' : p3,
				'phrase4__dup_phrase_data_id' : p4,
				'mod__person_id' : person_id
			};
			$('#saved-message').aqlSave('dup_phrase',data); 
		}
		else alert('Please select your choices from the lists below.');
	});
	
	$('#clear-all').live('click',function() {
		$('#final-phrase').val('');
		$('.listing_radio').each(function(index) {
			if ($(this).attr('checked')) $(this).attr('checked','');
		});
	});
	
});