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

    $('.filter-area, .filter').hover(
		function(){ 
        	mouse_is_inside=true; 
    	}, function(){ 
        	mouse_is_inside=false; 
    	}
	);

    $("#container").mouseup(function(){ 
        if(! mouse_is_inside) $('.filter-area').slideUp('fast',function() {
			$('.filter-on').css('border-bottom', '2px solid #999').removeClass('filter-on').addClass('filter');	
		});
    });

	$('.filter').live('click',function() {
		var $this = $(this);
		var filter = $this.attr('filter');
		$this.css('border-bottom', 'none');
		$('#'+filter).slideDown(100);
		$this.removeClass('filter').addClass('filter-on');
	});
	
	$('.filter-on').live('click',function() {
		var $this = $(this);
		var filter = $this.attr('filter');
		$('#'+filter).slideUp(100,function() {
			$this.css('border-bottom', '2px solid #999');
		});
		$(this).removeClass('filter-on').addClass('filter');
	});
	
	$('.type-filter-radio').live('click',function() {
		var val = $(this).val();
		$('#type').val(val);
		$('#type_selected').html(' - ' + val);
	});
		
	$('.phrase-filter-radio').live('click',function() {
		var phrase_id = $('#final-phrase').attr('p1');
		var section = new Array();
		if ($('#phrase1-filter-cb').attr('checked')) section.push('listing1');
		if ($('#phrase2-filter-cb').attr('checked')) section.push('listing2');
		if ($('#mod-filter-cb').attr('checked')) section.push('modifier');
		if (!section.length) {
			$(this).removeAttr('checked');
			alert('Pick a group to filter');
			$('.filter-area').slideUp('fast');
			$('.filter-on').css('border-bottom', '2px solid #999').removeClass('filter-on').addClass('filter');	
		}
		else {
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
			var count = section.length;
			for (i = 0; i < count; i++) { 
				$('#'+section[i]).html('<img src="/images/loading.gif" />');
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
						$('#'+section[i]).html(data);
					}
				);
			}
		}
		//$.post('/admin/seo/duplicate-data/ajax/auto-permetate',{ sw: sw, filter: filter, type: type, value: value, or: or }, function(data){
		//	$('#auto').html(data);
		//});
	});


	$('.listing1-cb').livequery('click',function() {		
		
		phrase_id = $(this).attr('phrase_id');
		if ($(this).attr('checked')) $('#listing2_'+phrase_id).hide();
		else $('#listing2_'+phrase_id).show();
		saveDisableCheck();
	});
	
	$('.listing2-cb').livequery('click',function() {
		phrase_id = $(this).attr('phrase_id');
		if ($(this).attr('checked')) $('#listing1_'+phrase_id).hide();
		else $('#listing1_'+phrase_id).show();
		saveDisableCheck();
	});
		
	$('.save').livequery('click',function(){
		$('#multi-saved').html('<img src="/images/loading.gif" />');
		var text;
		type = $('#type').val();
		volume1 = new Array();
		volume2 = new Array();
		phrase1 = new Array();
		phrase2 = new Array();
		mods = new Array();
		phrase1_ids = new Array();
		phrase2_ids = new Array();
		mod_ids = new Array();
		category = $("input[name=category]:checked").val();
		
		
		$('.listing1-cb').each(function(index) {
            if ($(this).attr('checked')) {
				volume1.push($(this).attr('volume'));
				phrase1_ids.push($(this).attr('phrase_id'));
				phrase1.push($(this).attr('phrase'));
			}
        });
		$('.listing2-cb').each(function(index) {
            if ($(this).attr('checked')) {
				volume2.push($(this).attr('volume'));
				phrase2_ids.push($(this).attr('phrase_id'));
				phrase2.push($(this).attr('phrase'));
			}
        });
		$('.mod-cb').each(function(index) {
            if ($(this).attr('checked')) {
				mod_ids.push($(this).attr('mod_id'));
				mods.push($(this).attr('phrase'));
			}
        });
		if (phrase1_ids.length < 1 && phrase2_ids.length < 1) text = 'Please Select The Phrases and Modifiers You Wish to Use';
		else if (phrase1_ids.length < 1) text = 'Check a Phrase from Phrase Part 1';
		else if (phrase2_ids.length < 1) text = 'Check a Phrase from Phrase Part 2';
		if (text) $('#multi-saved').html(text);
		else { 
			$.post('/admin/seo/duplicate-data/ajax/save-multi-phrase',{ type: type, category: category, volume1: volume1, volume2: volume2, phrase1: phrase1, phrase2: phrase2, mods: mods, phrase1_ids: phrase1_ids, phrase2_ids: phrase2_ids, mod_ids: mod_ids },function(data) {
				$('#multi-saved').html(data);
			});
		}
	});
	
});

function saveDisableCheck() {
	cb1 = false;
	$('.listing1-cb').each(function() {
		if ($(this).attr('checked')) cb1 = true;
		if (cb1) return false;
	});
	cb2 = false;
	$('.listing2-cb').each(function(index, element) {
		if ($(this).attr('checked')) cb2 = true;
		if (cb2) return false;			
	});
	if (!cb1 || !cb2) $('.save').attr('disabled','disabled');
	else $('.save').removeAttr('disabled');
}