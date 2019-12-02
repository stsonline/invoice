
//	$(document).ready(function() {
//		alert('JQuery is succesfully included');
//	});


$(function() {
	$("#dialog").dialog({
		autoOpen : false,
		show : {
			effect : "blind",
			duration : 500
		},
		hide : {
			effect : "fade",
			duration : 1000
		}
	});
	$("#opener").click(function() {
		$("#dialog").dialog("open");
	});
});