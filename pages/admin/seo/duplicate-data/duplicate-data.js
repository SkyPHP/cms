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
	
	$('.phrase-filter-radio').live('click',function() {
		market = $("input[name=market]:checked").val();
		volume = $("input[name=volume]:checked").val();
		market_name = $("input[name=market_name]:checked").val();
		category = $("input[name=category]:checked").val();
		base = $("input[name=base]:checked").val();
		value = $(this).val();
		filter = $(this).attr('name');
		if (value) $('#'+filter+'_selected').html(' - ' + value);
		else $('#'+filter+'_selected').html('');
		$.post('/admin/seo/duplicate-data/ajax/listing',
			{ 
				market: market,
				volume: volume, 
				market_name: market_name,  
				category: category,
				base: base,
				value: value,
				filter: filter 
			}, 
			function(data){
				$('#listing').html(data);
			}
		);
		//$.post('/admin/seo/duplicate-data/ajax/auto-permetate',{ sw: sw, filter: filter, type: type, value: value, or: or }, function(data){
		//	$('#auto').html(data);
		//});
	});

	$('.phrase-listing1-radio').live('click',function() {
		//$('.phrase-listing1-radio').attr('checked','');
		//$(this).attr('checked','checked');
		market = $("input[name=market]:checked").val();
		volume = $("input[name=volume]:checked").val();
		market_name = $("input[name=market_name]:checked").val();
		category = $("input[name=category]:checked").val();
		base = $("input[name=base]:checked").val();
		val = $(this).attr('phrase');
		phrase_id = $(this).attr('phrase_id');
		$('#final-phrase').val(val);
		$('#final-phrase').attr('p1',phrase_id);
		$.post('/admin/seo/duplicate-data/ajax/listing2',
			{ 
				market: market,
				market_name_n: market_name,  
				category: category,
				base: base,
				phrase_id: phrase_id
			},
			function(data) {
				$('#listing2').html(data);
			}
		);
	});
	
	$('.phrase-listing2-radio').live('click',function() {
		val2 = $(this).attr('phrase');
		val1 = $("input[name=phrase1]:checked").attr('phrase');
		phrase_id = $(this).attr('phrase_id');
		$('#final-phrase').val(val1 + ' ' + val2);
		$('#final-phrase').attr('p2',phrase_id);
	});
	
	$('.a-or-m-switch').die().live('change',function() {
		val = $(this).val();
		$('.a-or-m-on').slideUp('fast',function() {
			$('#'+val).addClass('a-or-m-on').slideDown('fast');
		}).removeClass('a-or-m-on');
	});
	
	$('#save-final').live('click',function() {
		var val = $('#final-phrase').val();
		var p1 = $('#final-phrase').attr('p1');
		var p2 = $('#final-phrase').attr('p2');
		var p3 = $('#final-phrase').attr('p3');
		var p4 = $('#final-phrase').attr('p4');
		var person_id = $('#person_id').val();
		if (val) {
			var data = {
				'phrase1__dup_phrase_data_id' : p1,
				'phrase2__dup_phrase_data_id' : p2,
				'phrase3__dup_phrase_data_id' : p3,
				'phrase4__dup_phrase_data_id' : p4,
				'mod__person_id' : person_id
			};
			$('#saved-message').aqlSave('dup_phrase_group',data);
		}
		else alert('Please select your choices from the lists below.');
	});
	
	$('#clear-all').live('click',function() {
		$('#final-phrase').val('');
		$('.phrase-listing1-radio').attr('checked','');
		$('.phrase-listing2-radio').attr('checked','');
		$('#listing2').html('');
	});
	
});