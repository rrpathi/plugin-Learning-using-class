<?php include_once 'header.php'; ?>
<body>
<div class="container">
  <center><h2>Custome Form Creation</h2></center>
      <div class="form-group col-md-12">
      <label for="text">Shot Code Name</label>
      <input type="text" class="form-control" id="shot_code_name" placeholder="Enter text" name="shot_code_name">
    </div>
    <div class="form-group col-md-6">
      <label for="text">Label Name</label>
      <input type="text" class="form-control label_name" id="text" placeholder="Enter text" name="label[]">
    </div>
    <div class="form-group col-md-6">
      <label for="pwd">Field Name</label>
      <input type="text" class="form-control field_name" id="field" placeholder="text,password" name="field[]">
    </div>
    <div class="custome_form"></div>
    <center><input type="button" id="add_more" class="btn btn-default" value="Add More">
    <button type="submit" id="submit" class="btn btn-default">Submit</button></center>
</div>
</body>
</html>