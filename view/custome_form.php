<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <center><h2>Custome Form Creation</h2></center>
	  <div class="form-group col-md-6">
	      <label for="text">Form Id</label>
	      <input type="text" class="form-control" id="text" placeholder="Form Id" name="form_id">
	    </div>
	   <div class="form-group col-md-6">
	      <label for="text">String</label>
	      <input type="text" class="form-control" id="text" placeholder="String" name="string">
	    </div>  
   <!--  <div class="form-group col-md-6">
      <label for="text">Label Name</label>
      <input type="text" class="form-control" id="text" placeholder="Enter text" name="label[]">
    </div>
    <div class="form-group col-md-6">
      <label for="pwd">Field Name</label>
      <input type="text" class="form-control" id="field" placeholder="text,password" name="field[]">
    </div>
    <div class="custome_form"></div>
    <center><input type="button" id="add_more" class="btn btn-default" value="Add More"> -->
    <button type="submit" class="btn btn-default">Submit</button></center>
</div>
</body>
</html>
