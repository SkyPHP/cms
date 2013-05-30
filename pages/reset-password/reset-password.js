$(document).ready(function() {
	$('.autoclear').each(function(index) {
		if( $(this).val() == '') {
			$(this).val($(this).attr('default'));
		}
	});
	$('.autoclear').focus(function() {
		if( $(this).val() == $(this).attr('default')) {
			$(this).val('');
		}
	});
	$('.autoclear').blur(function() {
		if( $(this).val() == '') {
			$(this).val($(this).attr('default'));
		}
	});

	var url = '/reset-password/includes/set_hash';
	$('#email_hash').on('submit',function(e) {
		e.preventDefault();
		if( $('#email_address').val() != $('#email_address').attr('default') ) {
			$('#response_div').html('<img src="/images/loading.gif" style="display:block;margin:0 auto;" />');
			$.post(url, $('#email_hash').serialize(), function(json) {
				aql.json.handle(json, $('#response_div'), {
					successMessage: 'An email has been sent to '+$('#email_address').val()+' which contains a link to reset your password.'
				});
			});
		}
	});

	$('#password_form').saveForm({
		saveText:"Your password has been updated."
	});

});
