
/**
*	Message dismisser stuff
**/
$(document).ready(function(){
	
	$('.message').append('<div class="float-right cursor-pointer dismisser">x</div>');
	$('.dismisser').click(function(){
		$(this).parent().toggle();
	});
});
