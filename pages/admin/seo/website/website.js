$(function() {
	$('.directory').live('click',function() {
		path = $(this).attr('path')
		dirid = $(this).attr('dirid')
		if ($(this).attr('status') == 'closed') {
			$(this).attr('status','open')
			$.post('/admin/seo/website/view-folder', { path: path }, function (data) {
				$('#images_'+dirid).html('<img src="/images/minus.png" width="9" height="9" />...<img src="/images/open-folder.png" width="16" height="13" />')
				$('#'+dirid).html(data)	
			})
		} else {
			$(this).attr('status','closed')
			$('#images_'+dirid).html('<img src="/images/plus.png" width="9" height="9" />...<img src="/images/closed-folder.png" width="16" height="13" />')
			$('#'+dirid).html('')
		}
			
	})
	$('.file').live('click',function() {
		path = $(this).attr('path')
		formid = $(this).attr('formid')
		$("#"+formid+"_form").submit()
		$('.file').each(function() {
			$(this).css('background-color','#eeeee2')
		})
		$(this).css('background-color','#ccccc2')
	})
})