<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<script>
$(document).ready(function(){
    $("button").click(function(){
        $.get("test.php", function(data, status){
            alert("Data: " + data + "\nStatus: " + status);
        });
    });
});
</script>

<button>Send an HTTP GET request to a page and get the result back</button>


<div class="container">
  <h2>Vertical (basic) form</h2>
  <form role="form">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Email">
    </div>
    <div class="form-group">
      <label for="pwd">Lösenord:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Lösenord">
    </div>
    <div class="checkbox">
      <label><input type="checkbox"> Remember me</label>
    </div>
    <button type="submit" class="btn btn-default">Logga in</button>
  </form>
</div>



<div class="container">
  <h2>Dropdowns</h2>
  <p>The .dropdown class is used to indicate a dropdown menu.</p>
  <p>Use the .dropdown-menu class to actually build the dropdown menu.</p>
  <p>To open the dropdown menu, use a button or a link with a class of .dropdown-toggle and data-toggle="dropdown".</p>                                          
  <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Deltävling
    <span class="caret"></span></button>
    <ul class="dropdown-menu">
      <li><a href="#">HTML</a></li>
      <li><a href="#">CSS</a></li>
      <li><a href="#">JavaScript</a></li>
    </ul>
  </div>
</div>




<div class="container">
  <h2>Bordered Table</h2>
  <p>The .table-bordered class adds borders to a table:</p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Artist</th>
        <th>Låt</th>
        <th>Gissning</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td><input type="text" class="form-control" id="usr1"></td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>Moe</td>
        <td><input type="text" class="form-control" id="usr2"></td>
      </tr>
      <tr>
        <td>July</td>
        <td>Dooley</td>
        <td><input type="text" class="form-control" id="usr3"></td>
      </tr>
    </tbody>
  </table>
</div>


<?php
print_r($_POST);
?>

</body>
</html>


