$(document).ready(function () {
	$(".sigDisplay").each(function (i) {
		var sig_data = $(this).children('#sig_json').html();
		$('.sigDisplay').signaturePad({displayOnly:true}).regenerate(sig_data);
	});
});