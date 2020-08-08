//Binds menu buttons

window.onload = function(){
	
	/* Menu label functionality */
	$(":radio").change(function(){
		//Remove all classes, then add this id as class
		$("#table-wrap").removeClass();
		$("#table-wrap").addClass($(this).attr('id'));
		});
	
};