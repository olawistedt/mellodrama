<?php
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <!-- To get button to work -->
  <script src="jquery.ui.touch-punch.min.js"></script>
  
  <title>Mellodrama</title>
</head>
<body>
<p align="right">Kod av Ola Wistedt</p>
<?php
	require 'mello_db.php';
	$melloDb = new MelloDB();

//	print_r($_POST);
//	print_r($_SESSION);

//	if( empty($_SESSION["current_sub_contest"]) ) {
//		echo "Du måste välja en tävling att delta i först";
//	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if($_POST["action"] == "register") {
			$user_id = $melloDb->get_user_id($_POST["first_name"], $_POST["last_name"]);
			if( $user_id == 0 ) {
				$melloDb->add_user($_POST["first_name"], $_POST["last_name"]);
				$user_id = $melloDb->get_user_id($_POST["first_name"], $_POST["last_name"]);
				//echo "Användar ID = " . strval($user_id);
				$_SESSION["user_id"] = $user_id;
				$_SESSION["first_name"] = $_POST["first_name"];
				$_SESSION["last_name"] = $_POST["last_name"];
			}
		}
		elseif($_POST["action"] == "logout") {
			session_unset();
			session_destroy();
		}
		elseif($_POST["action"] == "login") {
			// Get user id from users database
			
			$user_id = $melloDb->get_user_id($_POST["first_name"], $_POST["last_name"]);		
			// echo "Användar ID = " . strval($user_id);
			if( $user_id != 0 ) {
				$_SESSION["user_id"] = $user_id;
				$_SESSION["first_name"] = $_POST["first_name"];
				$_SESSION["last_name"] = $_POST["last_name"];
			}
			else {
				// Maybe add a new user.
				echo "Du måste registrera dig innan din första inloggning<br>";
			}
		}
		elseif(preg_match('/set_sub_contest=(.*)/', $_POST["action"], $matches) == 1) {
			$_SESSION["current_sub_contest"] = $matches[1];
			
			if( !empty($_SESSION["first_name"] AND $_SESSION["current_sub_contest"] != "none") ) {
				$user_id = $melloDb->get_user_id($_SESSION["first_name"], $_SESSION["last_name"]);
				if( $user_id == 0 ) {
					echo "Du måste logga in först";
				}
				else {
					// Look if the user has guessed before
					$guesses = $melloDb->get_guesses_for_id($_SESSION["current_sub_contest"], $user_id);
					if( $guesses == 0 ) {
						// Create a new table entry for this user.
						$melloDb->add_user_to_competition($_SESSION["current_sub_contest"], $user_id);

						// Add initial guesses for the user 1..n
						$arr = $melloDb->get_melodies($_SESSION["current_sub_contest"]);
						$new_guesses = array();
						for ($i = 1; $i <= count($arr); $i++) {
							$new_guesses[] = $i;
						}
						$melloDb->set_guesses_for_id($user_id, $_SESSION["current_sub_contest"], $new_guesses);
					}
				}
			}
		}
	}

	//	if($_POST["action"] == "update_guesses") {
//		$melloDb->update_guesses($_SESSION["current_sub_contest"]);
//	}
	
	
?>





<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- ======================================================== -->


<!-- <H1 align="center">Mellodrama</H1> -->
<center><img id="img_mellodrama" src="Mellodrama.png" style="width:100%;height:auto;"></center>




<!-- ======================================================== -->
<!-- Login session -->
<!-- ======================================================== -->
<div class="container" <?php if( !empty($_SESSION["first_name"]) ) {echo "hidden";} ?>>
<form action="" method="post">
<table class="table table-bordered">
  <tbody>
    <tr>
      <td>Förnamn</td><td><input type="text" name="first_name" value=""></td>
    </tr>
    <tr>
      <td>Efternamn</td><td><input type="text" name="last_name" value=""></td>
    </tr>
  </tbody>
</table>
<button class="btn btn-secondary" type="submit" name="action" value="login">Logga in</button>
<button class="btn btn-secondary" type="submit" name="action" value="register">Registrera</button>
</form>
<br>
<p>Om första gången, skriv in dina uppgifter och tryck registrera. Annars skriv in dina uppgifter och tryck logga in.</p>
<br>
<p>Vinnare år för år (poängräkningen har skiljt sig vissa år så det är inte jämförbara)</p>
<table class="table">
  <tbody>
    <tr>
      <td>2024</td><td>Thea Wistedt + Lars-Erik Elebjörk (delad första plats)</td><td>54 poäng</td>
    </tr>
    <tr>
      <td>2023</td><td>Lars-Erik Elebjörk</td><td>60 poäng</td>
    </tr>
    <tr>
      <td>2022</td><td>Martin Samuelsson</td><td>58 poäng</td>
    </tr>
    <tr>
      <td>2021</td><td>Martin Samuelsson</td><td>63 poäng</td>
    </tr>
    <tr>
      <td>2020</td><td>Martin Samuelsson</td><td>77 poäng</td>
    </tr>
    <tr>
      <td>2019</td><td>Johan Lindström</td><td>60 poäng</td>
    </tr>
    <tr>
      <td>2018</td><td>Martin Samuelsson</td><td>70 poäng</td>
    </tr>
    <tr>
      <td>2017</td><td>Lars Nyström</td><td>61 poäng</td>
    </tr>
    <tr>
      <td>2016</td><td>Ewa Pettersson</td><td>63 poäng</td>
    </tr>
    <tr>
      <td>2015</td><td>Lars Nyström</td><td>55 poäng</td>
    </tr>		
    <tr>
      <td>2014</td><td>Sandra Lindvall</td><td>59 poäng</td>
    </tr>		
    <tr>
      <td>2014</td><td>Sandra Lindvall</td><td>68 poäng</td>
    </tr>		
    <tr>
      <td>2012</td><td>Johan Lindström</td><td>57 poäng</td>
    </tr>
    <tr>
      <td>2011</td><td>Sandra Lindvall</td><td>50 poäng</td>
    </tr>		
    <tr>
      <td>2010</td><td>Martin Samuelsson</td><td>44 poäng</td>
    </tr>
</table>

</div>


<!-- ======================================================== -->
<!-- Logout session -->
<!-- ======================================================== -->
<div class="container" <?php if( empty($_SESSION["first_name"]) ) {echo "hidden";} ?>>
<p>
<?php
if( !empty($_SESSION["first_name"]) ) {
  echo "Du är inloggad som "; echo $_SESSION["first_name"]; echo " "; echo $_SESSION["last_name"];
}
?>
<form action="" method="post">
<button class="btn btn-secondary" type="submit" name="action" value="logout">Logga ut</button>
</form>
</p>
</div>



<!-- ======================================================== -->
<!-- Dropdown menu that chooses sub contest to participate in -->
<!-- ======================================================== -->
<div class="container" <?php if( empty($_SESSION["first_name"]) ) {echo "hidden";} ?>>

<!-- <p style="color:red;">OBSERVERA: Eftersom SVT inte presenterar plats nummer sex och sju längre så blir den helt färdiga
räkningen inte klar förrän plats sex och sju redovisats på Wikipedia.</p> -->

<!-- <p style="color:red;">Viktigt: Läs detta! Eftersom vinnare av duellerna i andra chansen redovisas direkt efter duellen så stängs tippningen
redan innan resultatet av första duellen redovisats. Du kan alltså inte se duellerna och sen tippa utan du måste tippa i förväg.</p> -->

<form action="" method="post">
	<div class="dropdown">
	<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?php if(!isset($_SESSION["current_sub_contest"])) {
			echo "Välj deltävling";
		}else {
			echo $_SESSION["current_sub_contest"];
		}
			 ?>
	</button>
	<div class="dropdown-menu" aria-labelledby="dropdownMenu2">
	<?php
    $arr = $melloDb->get_sub_contests();
    for ($i = 0; $i < count($arr); $i++) {
        // Check if the string contains "2025"
        if (strpos($arr[$i], "2025") !== false) {
            echo '<button class="dropdown-item" type="submit" name="action" value="set_sub_contest=' . $arr[$i] . '">' . $arr[$i] . '</button>';
        }
    }
?>
	</div>
	</div>
</form>

















</div>


<!-- ======================================================== -->
<!-- Sortable table with melodies and results -->
<!-- ======================================================== -->
<div class="container" <?php if( empty($_SESSION["current_sub_contest"]) ) {echo "hidden";} ?>>
<script>
</script>	
<script>
$(document).ready(function () {

	// First change the width of the banner image
	var w = $(window).width();
	document.getElementById('img_mellodrama').style.width=Math.min(w-400,700) +'px';

	// Then control the lock
	var is_locked = "1";

	function check_lock() {
		 $.getJSON("mello_ajax_server.php",
	        		{ action: "is_locked", subcontest: <?php echo "\""; echo $_SESSION["current_sub_contest"]; echo "\""; ?> },
	                 function(result){
	        			is_locked = result;
	        			if(is_locked == "0") {
	            			$("#open_or_closed").text("");
	        			}
	        			else {
	            			$("#open_or_closed").text("Tippningen är stängd");
	        			}
	        		}); 
	}

	check_lock();
	setInterval(function(){ check_lock(); }, 10000); // Check every ten seconds.

	// Then control the sortable
    $('#sortable').sortable({
        axis: 'y',
        stop: function (event, ui) {
	       	if( is_locked === "1" ) {
            	$(this).sortable('cancel');
        	}
        	else
        	{        	
		        var data = "action=rearrange&";
		        data += "user_id=<?php echo $_SESSION["user_id"]?>&";
		        data += "subcontest=<?php echo $_SESSION["current_sub_contest"]?>&";
		        data += $(this).sortable('serialize');
	            $.ajax({
	                data: data,
	                type: 'POST',
	                url: 'mello_ajax_server.php'
	            });
        	}
        }
    });
});
</script>

<p style="color:red;" id="open_or_closed"></p>

<p>Du kan ändra din tippning fram till strax innan resultaten redovisas på tv.</p>
<!--
<p style="color:blue;">För resultat gällande deltävling 3 (17 februari) så gick det
  väldigt fort när svt tog bort resultattabellen. Jag hade rättat fel och bytt plats
  på Klaudi och Gunilla ett tag. Det är fixat nu. Gunilla kom 4:a och inte 3:a.</p>
-->
<!-- <p style="color:red;">För andra chansen läs noga nedan.</p> -->

<table class="table">
<td>
<ul class="list-group">
<?php
    if( !empty($_SESSION["current_sub_contest"]) AND $_SESSION["current_sub_contest"] != "none") {
    	$count_system = $melloDb->get_count_system($_SESSION["current_sub_contest"]);
		for ($i = 1; $i <= count($melloDb->get_melodies($_SESSION["current_sub_contest"])); $i++) {
			if( $count_system == "andra_chansen" AND ($i >= 1 AND $i <= 4) ) {
				echo "<li class=\"list-group-item\">Till final<br>---</li>";
			}
			elseif( $count_system == "andra_chansen" AND ($i >= 5 AND $i <= 8) ) {
				echo "<li class=\"list-group-item\">Åker ur<br>---</li>";
			}
			else {
				echo "<li class=\"list-group-item\">$i<br>---</li>";
			}
		}
    }
?>
</ul>
</td>
<td>
<ul class="list-group" id="sortable">
<?php
    if( !empty($_SESSION["current_sub_contest"]) AND $_SESSION["current_sub_contest"] != "none") {
    	$arrMelodies = $melloDb->get_melodies($_SESSION["current_sub_contest"]);
		$guesses = $melloDb->get_guesses_for_id($_SESSION["current_sub_contest"], $_SESSION["user_id"]);
		
		// Get an ordered list sorted by guess
		array_shift($guesses); // Throw first element (user id)
		foreach ( $guesses as $guess_for_melody_nr ) {
			$melody_nr = $arrMelodies[$guess_for_melody_nr - 1][0];
			$artist = $arrMelodies[$guess_for_melody_nr - 1][1];
			$melody = $arrMelodies[$guess_for_melody_nr - 1][2];
			echo "<li class=\"list-group-item\" id=\"item-$guess_for_melody_nr\">$artist<br><i>$melody</i></li>";
		}
	}
	?>
</ul>
</td>
</table>
<!-- Query string: <span></span> -->

<!-- ======================================================== -->
<!-- Sortable table with melodies and results -->
<!-- ======================================================== -->

<?php
	// Count result with the final count system
	function count_result_final_system($melloDb, $sub_contest, $user_id) {
		$sum = 0;
		// Loop results
		
		$arrMelodies = $melloDb->get_melodies($sub_contest);
		$guesses = $melloDb->get_guesses_for_id($sub_contest, $user_id);
		if($guesses == 0) {
			return 0;
		}
		array_shift($guesses); // First element is id, throw it!
	
		$i = 1;
		foreach ($arrMelodies as $melody) {
			$correct_result = $melody[3];
			$my_guess = array_search($i, $guesses) + 1; // Find melody guess in guesses
			if($my_guess == $correct_result)
			{
				$sum += 2;
				if($my_guess == 1) {
					$sum += 1; // Extra bonus for first place correct
				}
			}
			elseif ($my_guess == $correct_result - 1) {
				$sum += 1;
			}
			elseif ($my_guess == $correct_result + 1 AND $my_guess != 1) {
				$sum += 1;
			}
			$i++;
		}
		return $sum;
	}
	
	// Count result with the eurovision count system
	function count_result_eurovision_system($melloDb, $sub_contest, $user_id) {
		$sum = 0;
		// Loop results
	
		$arrMelodies = $melloDb->get_melodies($sub_contest);
		$guesses = $melloDb->get_guesses_for_id($sub_contest, $user_id);
		if($guesses == 0) {
			return 0;
		}
		array_shift($guesses); // First element is id, throw it!
	
		$i = 1;
		foreach ($arrMelodies as $melody) {
			$correct_result = $melody[3];
			$my_guess = array_search($i, $guesses) + 1; // Find melody guess in guesses
			if($my_guess == $correct_result)
			{
				$sum += 3;
				if($my_guess == 1) {
					$sum += 2; // Extra bonus for first place correct
				}
			}
			elseif ($my_guess == $correct_result - 1) {
				$sum += 2;
			}
			elseif ($my_guess == $correct_result + 1 AND $my_guess != 1) {
				$sum += 2;
			}
			elseif ($my_guess == $correct_result - 2) {
				$sum += 1;
			}
			elseif ($my_guess == $correct_result + 2 AND $my_guess != 1) {
				$sum += 2;
			}
			$i++;
		}
		return $sum;
	}

	// Count result with the deltävlig count system
	function count_result_deltavling_system($melloDb, $sub_contest, $user_id) {
		$sum = 0;
		// Loop results
		
		$arrMelodies = $melloDb->get_melodies($sub_contest);
		$guesses = $melloDb->get_guesses_for_id($sub_contest, $user_id);
		if($guesses == 0) {
			return 0;
		}
		array_shift($guesses); // First element is id, throw it!
	
		$i = 1;
		foreach ($arrMelodies as $melody) {
			$correct_result = $melody[3];
			$my_guess = array_search($i, $guesses) + 1; // Find melody guess in guesses
			if($my_guess == $correct_result)
			{
				$sum += 2;
				if($my_guess == 1) {
					$sum += 1; // Extra bonus for first place correct
				}
			}
			elseif ($my_guess == $correct_result - 1) {
				$sum += 1;
			}
			elseif ($my_guess == $correct_result + 1 AND $my_guess != 1) {
				$sum += 1;
			}
			$i++;
		}
		return $sum;
	}
	
	
	// Count result with the final count system
	function count_result_andra_chansen_system($melloDb, $sub_contest, $user_id) {
		$sum = 0;
		// Loop results
		
		$arrMelodies = $melloDb->get_melodies($sub_contest);
		$guesses = $melloDb->get_guesses_for_id($sub_contest, $user_id);
		if($guesses == 0) {
			return 0;
		}
		array_shift($guesses); // First element is id, throw it!
	
		$i = 1;
		foreach ($arrMelodies as $melody) {
			$correct_result = $melody[3];
			$my_guess = array_search($i, $guesses) + 1; // Find melody guess in guesses
			

			if ( $my_guess == 1 AND $correct_result == 1) {
				$sum += 2;
			}
			elseif ( $my_guess == 2 AND $correct_result == 1) {
				$sum += 2;
			}
			elseif ( $my_guess == 3 AND $correct_result == 1) {
				$sum += 2;
			}
			elseif ( $my_guess == 4 AND $correct_result == 1) {
				$sum += 2;
			}
			
			$i++;
		}
		return $sum;
	}
	
	// Count result
	function count_result($melloDb, $sub_contest, $user_id) {
		$count_system = $melloDb->get_count_system($sub_contest);
		if($count_system == "final") {
			$sum = count_result_final_system($melloDb, $sub_contest, $user_id);
		}
		elseif ($count_system == "eurovision") {
			$sum = count_result_eurovision_system($melloDb, $sub_contest, $user_id);
		}
		elseif ($count_system == "deltavling") {
			$sum = count_result_deltavling_system($melloDb, $sub_contest, $user_id);
		}
		elseif ($count_system == "andra_chansen") {
			$sum = count_result_andra_chansen_system($melloDb, $sub_contest, $user_id);
		}
		
		return $sum;
	}
		
	?>

<p style="color: red; font-weight: bold;">Klantig som jag var glömde
		jag bort att kolla att <a href="http://witechnology.se/mellodrama">http://witechnology.se/mellodrama</a> fungerade. Prova nu igen så ska det fungera. Om inte så säg till 😊
	</p>

<h2>Tippningstabell</h2>
<?php
if( !empty($_SESSION["current_sub_contest"]) AND $_SESSION["current_sub_contest"] != "none") {
    $count_system = $melloDb->get_count_system($_SESSION["current_sub_contest"]);
	if($count_system == "final") {
		echo "<p>Rätt placering ger 2 poäng, en placering ifrån ger 1 poäng. 1 bonuspoäng för rätt förstaplacering. Max poäng är 25.</p>";			
	}
	elseif ($count_system == "eurovision") {
		echo "<p>Rätt placering ger 3 poäng, en placering ifrån ger 2 poäng, två placeringar ifrån ger 1 poäng. 2 bonuspoäng för rätt förstaplacering. Max poäng är 80.</p>";			
	}
	elseif ($count_system == "deltavling") {
		echo "<p>Rätt placering ger 2 poäng, en placering ifrån ger 1 poäng. 1 bonuspoäng för att pricka in ettan. Max poäng är 13. Observera att från 2022 så är det exakta placeringar som gäller.</p>";
	}
	elseif ($count_system == "andra_chansen") {
		echo "<p>Rätt gissning till final ger 2 poäng. Max poäng är 8.</p>";
	}
}
?>

<table class="table table-bordered">
<tbody>
<?php
if( !empty($_SESSION["current_sub_contest"]) AND $_SESSION["current_sub_contest"] != "none") {
	$arrCompeditors = $melloDb->get_compeditors($_SESSION["current_sub_contest"]);

	$arr = array();
	foreach ($arrCompeditors as $compeditor) {
		$id = array_shift($compeditor);		
		$sum = count_result($melloDb, $_SESSION["current_sub_contest"], $id);		
		$arr[$id] = $sum;
	}
	arsort($arr);
	foreach ($arr as $kid => $epoints) {
		echo "<tr>";
		$arrUser = $melloDb->get_user_with_id($kid);
		echo "<td>$arrUser[0] $arrUser[1]</td>";
		echo "<td>$epoints</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
<form action="" method="post">
<button class="btn btn-secondary" type="submit" name="action" value="update">Uppdatera</button>
</form>
<p>Dina gissningar uppdateras automatiskt när du drar och släpper men den här tippningstabellen uppdateras inte automatiskt. Så tryck uppdatera när röstningen på TV är avslutad.</p>

<h2>Maratontabell</h2>

<table class="table table-bordered">
<tbody>
<?php
if( !empty($_SESSION["current_sub_contest"]) AND $_SESSION["current_sub_contest"] != "none") {
	
  // The maraton table
	$arrParts = array(
			"final_2025",
			"deltavling_1_2025",
      "deltavling_2_2025",
			"deltavling_3_2025",
			"deltavling_4_2025",
      "deltavling_5_2025"
    );

$arrCompeditors = array();
	foreach ($arrParts as $part) {
	    $arrCompeditors = array_merge($arrCompeditors, $melloDb->get_compeditors($part));
	}

	$arr = array();
	foreach ($arrCompeditors as $compeditor) {
		$id = array_shift($compeditor);		
		$sum = 0;	
		foreach ($arrParts as $part) {
			$sum += count_result($melloDb, $part, $id);
		}		
		$arr[$id] = $sum;
	}
	arsort($arr);
	foreach ($arr as $kid => $epoints) {
		echo "<tr>";
		$arrUser = $melloDb->get_user_with_id($kid);
		echo "<td>$arrUser[0] $arrUser[1]</td>";
		echo "<td>$epoints</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>

</div>
</body>
</html>
