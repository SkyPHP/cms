$(document).ready(function() {
	$('.changeable', this).live('mouseover mouseout click', function(e) {
			if (e.type == 'mouseover') {
    			$(this).parent().css("background-color","#eeeeee")
  			} else if (e.type == 'mouseout') {
    			$(this).parent().css("background-color","#ffffff")
  			} else if (e.type == 'click') {
				$(this).parent().css("background-color","#ffffff")
				if ($(this).html() == '_________________') value = ''
				else value = $(this).html()
				ide =  $(this).attr('ide')
				field = $(this).attr('field');
				$.post('/admin/seo/website-page-data/ajax/update-record-input/'+ide, { field: field, value: value }, function(data) {
  					$('#'+field+'_'+ide).html(data)
					$('.ur-input').focus()
				})
			}
		});

		$('.ur-input').live('focusout keyup',function(e) {
			if (e.type=='focusout' || e.keyCode==13) {
				ide =  $(this).attr('ide')
				field = $(this).attr('field')
				$.post('/admin/seo/website-page-data/ajax/update-record/'+ide, { value: $(this).val(), field: field }, function(data) {
  					$('#'+field+'_'+ide).html(data)
				})
			}
		})
})