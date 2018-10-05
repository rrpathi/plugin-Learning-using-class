jQuery(document).ready(function(){
	jQuery("#add_more").click(function(e){
		e.preventDefault();
		jQuery(".custome_form").append('<div class="form-group col-md-6"><label for="text">Label Name</label><input type="text" class="form-control" id="text" placeholder="Enter text" name="label[]"></div><div class="form-group col-md-6"><label for="pwd">Field Name</label><input type="text" class="form-control" id="field" placeholder="text,password" name="field[]"></div>');
	});

});