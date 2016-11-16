Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

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

function refreshCart() {
	var cart = Cookies.getJSON('cart');
	$('.nav .bubble').html(Object.size(cart));
}

$(function(){
	// initialize the products-in-cart counter (from the cookie)
	refreshCart();

	// handle login submission
	$(".login form").submit(function(e) {
		// prevet from default behaviour
		e.preventDefault();

		// get login data into a FormData object
		var data = new FormData($('.login form')[0]);
		// get form's action attr
		var action = $('.login form').attr('action');
		// empty previous validation errors
		$(".login form .error").html("").hide();

		$.ajax(action, {
			method: 'POST',
			dataType: 'json',
			data: data,
			success: function (data) {
				// if there are validation errors, show them
				if(data.errors) {
					parseErrors(data);
					return;
				}
				// otherwise, do whatever
				if(data.result) {
					console.log(data);
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	// attach "mouseleave" event handler to every dropdown 
	$(".nav .dropdown").mouseleave(function(e){
		$(this).removeClass("show");
	});

	// to prevent dropdown from closing when we click on the login form, stop propagation of the click event
	$(".nav .dropdown").click(function(e){
		e.stopPropagation();
	});

	// attach "click" event handler to every link with a dropdown 
	$(".nav .dropdown-toggle").click(function(e){
		var closeOwnDropdown = $(".dropdown", $(this)).hasClass("show");

		// just close own dropdown
		if(closeOwnDropdown){
			$(".dropdown",$(this)).removeClass("show");
		}
		// otherwise close opened dropdowns and open own dropdown
		else{
			$(".nav .dropdown.show").removeClass("show");
			$(".dropdown", $(this)).addClass("show");
		}
	});

	// remove default behavior to dropdown-toggle links
	$(".dropdown-toggle > a").click(function(e){
		e.preventDefault();
	});

	// add * to every required form control
	$('.form-control[required]').siblings('label').addClass('required').append('<span class="required">&nbsp;*</span>');

});