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
			setTimeout("$('.filter-on').css('border-bottom', '2px solid #999').removeClass('filter-on').addClass('filter');	",250);
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
	
	$('input[name=filter-selected]').live('change',function() {
		var new_filter = $(this).val();
		var prev_filter = $('#filter-this').val();		
		$('#filter-this').val(new_filter);
		if (prev_filter == 'modifier') {
			$('.modifier-filter-container').fadeOut('fast', function() {
				$('.phrase-filter-container').fadeIn('fast');
			});
		}
		else if (new_filter == 'modifier') {
			$('.phrase-filter-container').fadeOut('fast', function() {
				$('.modifier-filter-container').fadeIn('fast');
			});
		}
	});
	
	$('.phrase-filter-radio').live('click',function() {
		phrase_id = $('#final-phrase').attr('p1');
		section = new Array();
		section.push($('#filter-this').val());
		count = section.length;
		if (!count) {
			$(this).removeAttr('checked');
			alert('Pick a group to filter');
			$('.filter-area').slideUp('fast');
			$('.filter-on').css('border-bottom', '2px solid #999').removeClass('filter-on').addClass('filter');	
		}
		else {
			// GET THE VALUES OF THE DROPDOWNS
			var market = $("input[name=market]:checked").val();
			var volume = $("input[name=volume]:checked").val();
			var market_name = $("input[name=market_name]:checked").val();
			var category = $("input[name=category]:checked").val();
			var page = $("input[name=page]:checked").val();
			var modifier = $("input[name=modifier]:checked").val();
			var base = $("input[name=base]:checked").val();
			var value = $(this).val();
			var filter = $(this).attr('name');
			if (value) $('#'+filter+'_selected').html(' - ' + value);
			else $('#'+filter+'_selected').html('');
			var l1 = false;
			var l2 = false;
			var ids = new Array();
			for (i=0;i<count;i++) {
				$('#'+section[i]).fadeOut('fast');
				if (section[i]=='listing1')	l1 = true;
				if (section[i]=='listing2') l2 = true;
			}
			if (!l1 && l2) $('.listing1-cb').each(function() {
				phrase_id = $(this).attr('phrase_id');
				if ($(this).attr('checked')) ids.push(phrase_id);
			});
			else if (l1 && !l2) $('.listing2-cb').each(function() {
				phrase_id = $(this).attr('phrase_id');
				if ($(this).attr('checked')) ids.push(phrase_id);
			});
			var data = { 
				ids: ids,
				market: market,
				volume: volume, 
				market_name: market_name,  
				category: category,
				base: base,
				value: value,
				filter: filter,
				phrase_id: phrase_id
			};
			var url = '/admin/seo/duplicate-data/ajax/'+section[0];
			$.post(url, data, function(html1) { // first post
			
				$('#'+section[0]).html(html1);
				url = '/admin/seo/duplicate-data/ajax/'+section[1];
				
				if (section[1]) $.post(url, data, function(html2) { // second post
					$('#'+section[1]).html(html2);
					showSections(section);
				});
				else showSections(section);
			
			});
		}
		
		//$.post('/admin/seo/duplicate-data/ajax/auto-permetate',{ sw: sw, filter: filter, type: type, value: value, or: or }, function(data){
		//	$('#auto').html(data);
		//});
	});


	$('.listing1-cb').livequery('click',function() {		
		
		var phrase_id = $(this).attr('phrase_id');
		if ($(this).attr('checked')) $('#listing2_'+phrase_id).hide();
		else $('#listing2_'+phrase_id).show();
		saveDisableCheck();
	});
	
	$('.listing2-cb').livequery('click',function() {
		var phrase_id = $(this).attr('phrase_id');
		if ($(this).attr('checked')) $('#listing1_'+phrase_id).hide();
		else $('#listing1_'+phrase_id).show();
		saveDisableCheck();
	});
		
	$('.save').livequery('click',function(){
		$('#multi-saved').html('<img src="/images/loading.gif" />');
		var text;
		var group_name = $('#group_name').val();
		var type = $('#seo_field').val();
		var volume1 = new Array();
		var volume2 = new Array();
		var phrase1 = new Array();
		var phrase2 = new Array();
		var mods = new Array();
		var phrase1_ids = new Array();
		var phrase2_ids = new Array();
		var mod_ids = new Array();
		var category = $("input[name=category]:checked").val();
			
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
			$.post('/admin/seo/duplicate-data/ajax/save-multi-phrase',
			{ 
				group_name: group_name,
				seo_field: seo_field,
				category: category,
				volume1: volume1,
				volume2: volume2,
				phrase1: phrase1,
				phrase2: phrase2,
				mods: mods,
				phrase1_ids: phrase1_ids,
				phrase2_ids: phrase2_ids,
				mod_ids: mod_ids 
			},
			function(data) {
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

function showSections(section) {
	for (i=0;i<count;i++) {
		$('#'+section[i]).fadeIn('fast');
	}	
}