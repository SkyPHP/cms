
	function forgotpw() {
		$.skybox('/reset-password');
	}

	// login
	$('#login_username:visible').livequery(function(){
		$(this).focus();
	});

	var	fields = ['button', 'message', 'password', 'form', 'username'],
		uri = '/ajax/login-skybox/authenticate',
		messages = {
			'true' : 'You are being redirected to the requested page.',
			'false': 'Incorrect Login. Please try again.'
		},
		redirectToPage = function() {
			
			var uri = window.location.href;
			if (location.hash.substring(0, 2) != '#/') {
				uri = uri.substr(0, uri.indexOf('#'));
				uri = removeParam('skybox', uri);
				uri = removeParam('logout', uri);
			} else {
				uri = window.location.pathname;
			}

			window.location.href = uri;
			
		};

	$('#login_form:visible').die().live('submit', function() {

		var $els = {}, i, data, message;

		// get elements
		for (i = 0; i < fields.length; i++) {
			$els[fields[i]] = $('#login_' + fields[i]);
		}

		// hide message
		$els.message.is(':visible') && $els.message.fadeTo('fast', 0.01);

		// disable login button
		$els.button.val('Authenticating...').attr('disabled', true);

		data = $els.form.serialize();

		$.post(uri, data, function(response) {
			
			message = messages[response] || response;
			$els.message.html(message);
			if ($els.message.is(':visible')) $els.message.fadeTo('fast', 1);
			else $els.message.slideDown('fast');

			if (response == 'true') {
				
				$els.message.css({
					border: 0,
					borderBottom: '1px solid',
					borderTop: '1px solid',
					textAlign: 'center',
					background: 'black',
					color: 'white',
					fontWeight: 'bold',
					fontSize: '16px'
				});

				setTimeout(redirectToPage, 500);

			} else {

				$els.password.val('');
				$els.button.val('Sign In').attr('disabled', false);
				if ($els.username.val() == '') $els.username.focus();
				else $els.password.focus();

			}

		});

		return false;
	});
