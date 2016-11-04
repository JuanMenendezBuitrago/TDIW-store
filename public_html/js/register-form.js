function parseErrors(data) {
	var errors = data.errors;
	for (var key in errors) {
		var $input = $("#"+key);
		var numErrors = errors[key].length;
		for(var i = 0; i < numErrors; i++) {
			$input.next(".error").html(errors[key][i]).show();
		}
	}
}

function checkPasswords() {
	return $("#password").val() === $("#password2").val();
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
					if(data.errors) {
						parseErrors(data);
					}	
				}
			});
		} else {
			var data = {errors : { passwd2: ["Les contrasenyes no coincideixen"]}};
			parseErrors(data);
		}
	});
});