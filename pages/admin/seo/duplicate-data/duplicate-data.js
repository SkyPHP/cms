var mouse_is_inside = false;
var char_count_limit = $('#char_count_limit').val();
String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};
String.prototype.trim = function() {
	return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
};

function trigger_count() {
	var color;
	count = $('#final-phrase').val().length;
	if (count > char_count_limit) color = '#ff0000';
	else color = '#000000'; 
	$('#char-count').css('color',color).html(count + '/' + char_count_limit + ' Characters');
}

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
		var $this = $(this);
		var filter = $this.attr('filter');
		$this.css('border-bottom', 'none');
		$('#'+filter).slideDown('fast');
		$this.removeClass('filter').addClass('filter-on');
	});
	
	$('.filter-on').live('click',function() {
		var $this = $(this);
		var filter = $this.attr('filter');
		$('#'+filter).slideUp('fast',function() {
			$this.css('border-bottom', '2px solid #999');
		});
		$(this).removeClass('filter-on').addClass('filter');
	});
	
	$('.phrase-filter-radio').live('click',function() {
		var phrase_id = $('#final-phrase').attr('p1');
		var section = $(this).attr('section');
		var market = $("input[name=market]:checked").val();
		var volume = $("input[name=volume]:checked").val();
		var market_name = $("input[name=market_name]:checked").val();
		var category = $("input[name=category]:checked").val();
		var base = $("input[name=base]:checked").val();
		var value = $(this).val();
		var filter = $(this).attr('name');
		if (value) $('#'+filter+'_selected').html(' - ' + value);
		else $('#'+filter+'_selected').html('');
		var url = '/admin/seo/duplicate-data/ajax/'+section;
		$.post(url,
			{ 
				market: market,
				volume: volume, 
				market_name: market_name,  
				category: category,
				base: base,
				value: value,
				filter: filter,
				phrase_id: phrase_id
			}, 
			function(data){
				$('#'+section).html(data);
			}
		);
		//$.post('/admin/seo/duplicate-data/ajax/auto-permetate',{ sw: sw, filter: filter, type: type, value: value, or: or }, function(data){
		//	$('#auto').html(data);
		//});
	});

	$('.phrase-listing1-radio').live('click',function() {
		phrase_id = $(this).attr('phrase_id');
		$('.phrase-filter-radio').attr('section','listing2');
		$('.all').attr('section','listing2');
		$('#saved-message').html('');
		var market = $("input[name=market]:checked").val();
		var volume = $("input[name=volume]:checked").val();
		var market_name = $("input[name=market_name]:checked").val();
		var category = $("input[name=category]:checked").val();
		var base = $("input[name=base]:checked").val();
		var val1 = $(this).attr('phrase');
		var val2 = $('input[name=phrase2]:checked').attr('phrase');
		var val3 = $('input[name=phrase3]:checked').attr('phrase');
		var vol1 = $(this).attr('volume');
		var value = val1.trim();
		if (val2) value = value + ' | ' + val2.trim();
		if (val3) value = value + ' | ' + val3.trim();
		$('#final-phrase').val(value.capitalize());
		$('#final-phrase').attr('p1',phrase_id);
		$('#final-phrase').attr('vol1',vol1);
		if (!$('input[name=phrase2]:checked').val()) {
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
		}
		trigger_count();
	});
	
	$('.phrase-listing2-radio').live('click',function() {
		$('.phrase-filter-radio').attr('section','listing3');
		$('.all').attr('section','listing3');
		$('#saved-message').html('');
		var market = $("input[name=market]:checked").val();
		var volume = $("input[name=volume]:checked").val();
		var market_name = $("input[name=market_name]:checked").val();
		var category = $("input[name=category]:checked").val();
		var base = $("input[name=base]:checked").val();
		
		var vol2 = $(this).attr('volume');
		var val2 = $(this).attr('phrase');
		var val3 = $("input[name=phrase3]:checked").attr('phrase');
		var val1 = $("input[name=phrase1]:checked").attr('phrase');
		var phrase_id = $(this).attr('phrase_id');
		var value = val1.trim();
		if (val2) value = value + ' | ' + val2.trim();
		if (val3) value = value + ' | ' + val3.trim();
		$('#final-phrase').val(value.capitalize());
		$('#final-phrase').attr('p2',phrase_id);
		$('#final-phrase').attr('vol2',vol2);
		if (!$('input[name=phrase3]:checked').val()) {
			$.post('/admin/seo/duplicate-data/ajax/listing3',
				{ 
					market: market,
					market_name_n: market_name,  
					category: category,
					base: base,
					phrase_id: phrase_id
				},
				function(data) {
					$('#listing3').html(data);
				}
			);
		}
		trigger_count();
	});
	
	$('.phrase-listing3-radio').live('click',function() {
		var val3 = $(this).attr('phrase');
		var val2 = $("input[name=phrase2]:checked").attr('phrase');
		var val1 = $("input[name=phrase1]:checked").attr('phrase');
		var modifier_id = $(this).attr('modifier_id');
		value = val1.trim();
		if (val2) value = value + ' | ' + val2.trim();
		if (val3) value = value + ' | ' + val3.trim();
		$('#final-phrase').val(value.capitalize());
		$('#final-phrase').attr('mod',modifier_id);
		trigger_count();
	});
	
	$('.a-or-m-switch').live('change',function() {
		val = $(this).val();
		$('.a-or-m-on').slideUp('fast',function() {
			$('#'+val).addClass('a-or-m-on').slideDown('fast');
		}).removeClass('a-or-m-on');
	});
	
	$('#save-final').live('click',function() {
		var val = $('#final-phrase').val();
		var vol1 = $('#final-phrase').attr('vol1');
		var vol2 = $('#final-phrase').attr('vol2');
		var total_volume = parseInt(vol1) + parseInt(vol2);
		var p1 = $('#final-phrase').attr('p1');
		var p2 = $('#final-phrase').attr('p2');
		var mod = $('#final-phrase').attr('mod');
		var person_id = $('#person_id').val();
		var category = $("input[name=category]:checked").val();
		var seo_field = $("#seo_field").val();
		if (val) {
			var data = {
				'phrase1__dup_phrase_data_id' : p1,
				'phrase2__dup_phrase_data_id' : p2,
				'total_volume' : total_volume,
				'category' : category,
				'seo_field' : seo_field,
				'dup_modifier_id' : mod,
				'mod__person_id' : person_id
			};
			$('#saved-message').aqlSave('dup_phrase_group',data);
		}
		else alert('Please select your choices from the lists below.');
	});
	
	$('#clear-all').live('click',function() {
		$('.phrase-filter-radio').attr('section','listing');
		$('.all').attr('section','listing');
		$('#final-phrase').val('');
		$('.phrase-listing1-radio').attr('checked','');
		$('.phrase-listing2-radio').attr('checked','');
		$('#listing2').html('');
		$('#listing3').html('');
	});
	
});