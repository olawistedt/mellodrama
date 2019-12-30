<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Sortable - Default functionality</title>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> 
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="jquery.ui.touch-punch.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<script>
$(document).ready(function () {
    $('ul').sortable({
        axis: 'y',
        stop: function (event, ui) {
	        var data = $(this).sortable('serialize');
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

<ul class="list-group" id="sortable">
    <li class="list-group-item" id="item-1">1. Panetoz : Håll om mig hårt</li>
    <li class="list-group-item" id="item-2">2. Molly Pettersson Hammar : Hunger</li>
    <li class="list-group-item" id="item-3">3. Albin & Mattias : Rik</li>
    <li class="list-group-item" id="item-4">4. Boris René : Put your love on me</li>
</ul>
Query string: <span></span>


</body>
</html>
