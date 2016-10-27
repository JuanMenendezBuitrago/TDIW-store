function parseErrors(data) {
	var errors = data.responseJSON.errors;
	for (var key in errors) {
		$input = $("#"+key);
		$input.next(".error").html(errors[key]).show();
	}
}

function checkPasswords() {
	return $("#passwd").val() === $("#passwd2").val();
}

$(function(){
	$("form").submit(function(e){ 
		e.preventDefault(); 
		$(".error").html("").hide();

		if(checkPasswords()) {
			data = $('form').serializeArray();

			$.ajax('/usuari', {
				method: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {
					console.log("success", data);
				},
				error: parseErrors
			});
		} else {
			$("#passwd2").next(".error").append("Les contrasenyes no concideixen").show();
		}
	});
});