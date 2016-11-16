$(function(){
	$(".button").click(function(){
		// get the cart from the cookie
		var cart = Cookies.getJSON('cart');
		// get the id of the product
		var id = $(this).data('id');

		// set <id>:0 if there's no <id>
		if(!cart[id]) 
			cart[id] = 0;

		if($(this).hasClass('plus')){
			cart[id] += 1;
			$(this).siblings('.amount').html(cart[id]);
		}else if($(this).hasClass('minus')){
			if(cart[id] > 0) cart[id] -= 1;
			$(this).siblings('.amount').html(cart[id]);
			if(cart[id] === 0) {
				delete(cart[id]);
			}
		}
		// set cookie and refresh cart bubble
		Cookies.set('cart',cart);
		refreshCart();
		// console.log(id + " : " + cart[id]);
	});
});
