$(function(){
	$('.form-control[required]').siblings('label').addClass('required').append('<span class="required">&nbsp;*</span>');
});