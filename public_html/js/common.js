Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size += obj[key];
    }
    return size;
};

var bindPlusMinus;
// var baseUrl = '/~tdiw-j3';
var baseUrl = '';

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
	// send AJAX request
	$.get(baseUrl+'/cart', function(data) {
		$('.nav .bubble').html(Object.size(data));
	});
}

function hideModal() {
	$('header, main, footer').removeClass('blur');
	$('#modal, #overlay').hide();
}

function showModal() {
	$('header, main, footer').addClass('blur');
	$('#modal, #overlay').show();
}

$(function(){
	// initialize the products-in-cart counter (from the cookie)
	refreshCart();


	if($('#modal .body').length > 0) {
		showModal();
	}

	$('#modal').on('click','#close',function(e){
		e.preventDefault();
		hideModal();
		if($(this).hasClass('reload')){
			location.reload();
		}
	});

	$('.dropdown.categories-list a').click(function(e){
		e.preventDefault();
		var action = $(this).attr('href');
		$.get(action , function (data) {
			$('main .container #list').html(data);
			Holder.run();
		});
	});

	$('#logout-link').click(function(e){
		e.preventDefault();
		var action = $(this).attr('href');
		$.get(action , function (data) {
			// if there are validation errors, show them
			if(data.errors) {
				parseErrors(data);
				return;
			}
			// otherwise, do whatever
			if(data.result) {
				location.reload();
			}
		});
	});

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
					location.reload();
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	// attach "mouseleave" event handler to every dropdown 
	$(".dropdown").mouseleave(function(e){
		$(this).removeClass("show");
	});

	// to prevent dropdown from closing when we click on the login form, stop propagation of the click event
	$(".dropdown").click(function(e){
		e.stopPropagation();
	});

	// attach "click" event handler to every link with a dropdown 
	$(".dropdown-toggle").click(function(e){
		var closeOwnDropdown = $(".dropdown", $(this)).hasClass("show");

		// just close own dropdown
		if(closeOwnDropdown){
			$(".dropdown",$(this)).removeClass("show");
		}
		// otherwise close opened dropdowns and open own dropdown
		else{
			$(".dropdown.show").removeClass("show");
			$(".dropdown", $(this)).addClass("show");
		}
	});

	// remove default behavior to dropdown-toggle links
	$(".dropdown-toggle > a").click(function(e){
		e.preventDefault();
	});

	// add * to every required form control
	$('.form-control[required]').siblings('label').addClass('required').append('<span class="required">&nbsp;*</span>');

	$("body").on("click",".item-footer .button", function(){
		// get amount and target
		var amount = parseInt($(this).siblings('.amount').html(),10);
		var target = $(this).data('target');
		var id = target.slice(target.lastIndexOf('/')+1);
		var that = $(this); 

		// send AJAX request
		$.ajax(target,{
			method: 'PUT',
			success: function(data) {
				amount = data[id];
				that.siblings('.amount').html(amount);
				$("#admin-section").find("[data-item='" + id + "'] .amount").html(amount);
				refreshCart();
			}
		});
	});
});