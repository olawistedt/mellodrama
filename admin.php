<?php
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mellodrama Admin</title>
</head>
<body>

<?php
require 'mello_db.php';
$melloDb = new MelloDB();

if($_SERVER["REQUEST_METHOD"] == "POST")
{

	if( $_POST["action"] == "add_sub_contest") {
		if( empty($_POST["sub_name"]) ) {
			echo "Du måste fylla i namnet på deltävlingen";
		}
		elseif( empty($_POST["sub_nr_of_songs"]) ) {
			echo "Du måste fylla i antalet bidrag till deltävlingen";
		}
		else {
			$sub_name = $_POST["sub_name"];
			$sub_nr_of_songs = intval($_POST["sub_nr_of_songs"]);
			$sub_count_system = $_POST["sub_count_system"];
			$melloDb->add_sub_contest($sub_name, $sub_count_system, $sub_nr_of_songs);
			$_SESSION["current_sub_contest"] = $sub_name;
			$_SESSION["current_nr_of_melodies"] = $sub_nr_of_songs;
		}
	}
	
	elseif($_POST["action"] == "set_sub_contest") {		
	    $arr = $melloDb->get_sub_contests();
	    $_SESSION["current_sub_contest"] = $_POST["subcontest"];
    //	    print_r($_SESSION);
	}
	
	elseif($_POST["action"] == "update_artists") {

		
		
		
		// Ta reda på antalet melodier (gör detta på ett bättre sätt sen)
		$arr = $melloDb->get_melodies($_SESSION["current_sub_contest"]);
		$nr_of_melodies = count($arr);

		
		
		
    	for ($i = 1; $i <= $nr_of_melodies; $i++) {
    		$strnr = strval($i);
    		$strkeyname = "name".$strnr;
    		$strkeytitle = "title".$strnr;
    		$strkeyresult = "result".$strnr;
			$melloDb->update_melody_nr($i, $_SESSION["current_sub_contest"], $_POST[$strkeyname], $_POST[$strkeytitle], $_POST[$strkeyresult]);
    	}
	}
	
	elseif($_POST["action"] == "update_locked") {
		if( $_POST["locked"] == "locked" ) {
    	    $melloDb->set_is_locked($_SESSION["current_sub_contest"], 1);
		}
		elseif ( $_POST["locked"] == "open" ) {
    	    $melloDb->set_is_locked($_SESSION["current_sub_contest"], 0);
		}
		else {
			echo "Error: Unknown value posted to update_locked";
		}
	}
}

?>

<H1>Mellodrama administration</H1>


<!-- 
<h2>Ny Totaltävling</h2>
<form action="admin.php" method="post">
Namn på totaltävlingen: <input type="text" name="name" value="">
<input type="submit">
</form>

Lägg till deltävling:<br>
<form action="admin.php" method="post">
<select name="subcontest">
  <option value="2016_Delfinal_1">2016 Delfinal 1</option>
  <option value="2016_Delfinal_2">2016 Delfinal 2</option>
  <option value="2016_Delfinal_3">2016 Delfinal 3</option>
  <option value="2016_Delfinal_4">2016 Delfinal 4</option>
  <option value="2016_Final">2016 Final</option>
</select>
<input type="submit">
</form>
 -->
 

<h2>Ny deltävling</h2>
<form action="admin.php" method="post">
Namn på deltävlingen: <input type="text" name="sub_name" value=""><br>
Antal bidrag: <input type="text" name="sub_nr_of_songs" value=""><br>
Poängräkningssystem
<select name="sub_count_system">
<option value="andra_chansen">Andra Chansen</option>
<option value="deltavling">Deltavling</option>
<option value="final" selected>Final</option>
<option value="eurovision">Eurovision</option>
</select>
<br>
<button type="submit" name="action" value="add_sub_contest">Lägg till deltävling</button>
</form>

<h2>Bidrag</h2>

Deltävling:
<form action="admin.php" method="post">
<select name="subcontest">
<option value="none" selected>Not selected</option>
<?php 
	$arr = $melloDb->get_sub_contests();
	for ($i = 0; $i < count($arr); $i++) {
		if( $arr[$i] == $_SESSION["current_sub_contest"] ) {
			echo "<option value=\"$arr[$i]\" selected>$arr[$i]</option>";
		}
		else {
			echo "<option value=\"$arr[$i]\">$arr[$i]</option>";
		}
	} 
?>
</select>
<button type="submit" name="action" value="set_sub_contest">Välj deltävling</button>
</form>
<p>I delfinalerna mata in placeringen för bidraget. Samma sak gäller i finalen.</p>
<form action="admin.php" method="post">
<?php
    if(!empty($_SESSION["current_sub_contest"])) {
    	$arr = $melloDb->get_melodies($_SESSION["current_sub_contest"]);
		foreach ($arr as $melody) {
			echo "<input type=\"text\" name=\"name$melody[0]\" value=\"$melody[1]\"><input type=\"text\" name=\"title$melody[0]\" value=\"$melody[2]\"><input type=\"text\" name=\"result$melody[0]\" value=\"$melody[3]\"><br>";
		}
    }
?>
<button type="submit" name="action" value="update_artists">Uppdatera</button>
</form>

<h2>Lås</h2>
<?php
    if(!empty($_SESSION["current_sub_contest"])) {
		$is_locked = $melloDb->get_is_locked($_SESSION["current_sub_contest"]);
    }
?>

<form action="admin.php" method="post">
<input type="radio" name="locked" value="locked" <?php if ( isset($is_locked) && $is_locked == 1 ) { echo "checked"; } ?> >Låst<br>
<input type="radio" name="locked" value="open"   <?php if ( isset($is_locked) && $is_locked == 0 ) { echo "checked"; } ?> >Öppen<br>
<button type="submit" name="action" value="update_locked">Verkställ</button>
</form>

<h2>Användare</h2>
<textarea name="message" rows="10" cols="30">
<?php $melloDb->get_users(); ?>
</textarea>

<table>
<body>
<?php
	$table = $melloDb->get_users_table();
	foreach ($table as $row) {
		echo "<tr><td><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
	}

?>
</body>
</table>

</body>
</html>
