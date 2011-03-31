var table, id;
$(document).ready(function() {
	table = $('#idePage td').first().text();
	table = table.substring(0, table.length-3);
	id = $('#idePage td').first().next().text();
	
	$('#idePage td').click(function(e) {
		if (e.ctrlKey && $(this).prev().size()) {
			oldVal = $(this).text();
			column = $(this).prev().text();
			$(this).html('<input type="text" oldval="' + oldVal + '" column="' + column + '" value="' + oldVal + '" onblur="updateValue(this)" style="width:95%"/>');
			var field = $(this).find("input");
			field.focus().keypress(function(e2) {
				var code = (e2.keyCode ? e2.keyCode : e2.which);
				if (code == 13) field.blur();
				else if (code == 27) field.val(oldVal).blur();
			});
		}
	});
	
	$("#ide").focus().keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) $("#ideGo").click();
	});
});

function updateValue(element) {
	var element = $(element);
	var oldVal = element.attr("oldVal");
	var column = element.attr("column");
	var newVal = jQuery.trim(element.val());
	if (oldVal == newVal) element.parent().html(oldVal);
	else {
		if (newVal || confirm("Are you sure you want to make this field NULL?")) {
			var dataString = "func=updateValue&table="+table+"&column="+column+"&id="+id+"&newVal="+newVal;
			$.ajax({
				type: "POST",
				url: "/dev/ide/ajax",
				data: dataString,
				beforeSend : function() {
					element.attr("disabled","disabled");
				},
				success: function(data, textstatus) {
					if (data == 'success') element.parent().html(newVal);
					else {
						alert(data);
						element.parent().html(oldVal);
					}
				}
			});
		}
		else element.val(oldVal);
	}
}

function goIde(element) {
	var element = $(element);
	var ide = jQuery.trim($("#ide").val());
	if (ide) location.href = "/dev/ide/" + ide;
	else alert ("Please Enter an IDE");
}