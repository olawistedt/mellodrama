<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Mellodrama</title>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> 
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="jquery.ui.touch-punch.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<script>
$(document).ready(function () {
	
    $('#sortable').sortable({
        axis: 'y',
        stop: function (event, ui) {
	        var data = "action=rearrange&";
	        data += "user_id=1&";
	        data += "subcontest=Final_2015&";
	        data += $(this).sortable('serialize');
            $('span').text(data);
            $.ajax({
                data: data,
                type: 'POST',
                url: 'mello_ajax_server.php'
            });
		}
    });
});

</script>


<body>
<p>Sortera listan</p>
<table class="table table-bordered">
<tr>
<td>
<ul class="list-group">
    <li class="list-group-item">1</li>
    <li class="list-group-item">2</li>
    <li class="list-group-item">3</li>
    <li class="list-group-item">4</li>
</ul>

</td>
<td>
<ul class="list-group" id="sortable">
    <li class="list-group-item" id="item-1">1. Panetoz : Håll om mig hårt</li>
    <li class="list-group-item" id="item-2">2. Molly Pettersson Hammar : Hunger</li>
    <li class="list-group-item" id="item-3">3. Albin & Mattias : Rik</li>
    <li class="list-group-item" id="item-4">4. Boris René : Put your love on me</li>
</ul>
</td>
</tr>
</table>
Query string: <span></span>


</body>
</html>
