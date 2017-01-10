$(function(){
	$("main .container").on("click",".item > h3 > a", function(e){
		e.preventDefault();

		var action = $(this).attr('href');
		$.get(action , function (data) {
			$('#modal').html(data);
			showModal();
			Holder.run();			
		});
	});
});
