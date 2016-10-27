
	$(function(){
		// attach "mouseleave" event handler to every dropdown 
		$(".nav .dropdown").mouseleave(function(e){
			$(this).removeClass("show");
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