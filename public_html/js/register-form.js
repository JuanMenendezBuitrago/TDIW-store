

function checkPasswords() {
	return $("#password").val() === $("#password2").val();
}

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}
$(function(){


	$("main form").submit(function(e){ 
		e.preventDefault(); 

		var data;
		var action = $('main form').attr('action');
		var method = $('main form').attr('method').toUpperCase();

		$(".error").html("").hide();

		data = new FormData($('main form')[0]);
		data.delete('picture');
		// data = $('form').serializeArray();

		$.ajax(action, {
			method: method,
			dataType: 'json',
			data: data,
			success: function (data) {
				if(data.errors) {
					parseErrors(data);
				}	
			},
			cache: false,
			contentType: false,
			processData: false
		});
	});

	$('#upload-picture').click(function(e){
		e.preventDefault();

	    var formData = new FormData();
	    formData.append('picture',$('#picture')[0].files[0]);
	    formData.append('tempId',$('#tempId').val());

	    $.ajax({
	        url: '/upload',  //Server script to process data
	        type: 'POST',
	        xhr: function() {  // Custom XMLHttpRequest
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){ // Check if upload property exists
	                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
	            }
	            return myXhr;
	        },
	        //Ajax events
	        success: function(data){
				if(data.errors) {
					parseErrors(data);
				}
				if(data.result) {
					console.log(data.result);
				}
	        },
	        error:  function(xhr, ajaxOptions, thrownError){console.log(thrownError);},
	        // Form data
	        data: formData,
	        //Options to tell jQuery not to process data or worry about content-type.
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	});

	$('#picture').change(function(){
		if (this.files && this.files[0]) {
	        var reader = new FileReader();

    	    reader.onload = function (e) {
    	    	$('#image-form-group i').hide();
    	    	$('#image-form-group div:first-of-type').addClass('filled');
        	    $('#picture-preview').attr('src', e.target.result).show();
        	};

        	reader.readAsDataURL(this.files[0]);
    	}
	});
});