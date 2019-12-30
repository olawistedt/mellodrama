<?php

	require 'mello_db.php';
	$melloDb = new MelloDB();

	function write_to_file() {
		$action    = $_POST["action"];
		$user_id   = $_POST["user_id"];
	    $database  = $_POST["subconstest"];
	    $arr_items = $_POST["item"];
	
	    $txt = "";
	    $txt .= "action:      $action\n";
		$txt .= "user_id:     $user_id\n";
		$txt .= "subconstest: $subconstest\n";
		$i = 1;
		foreach ($arr_items as $elem ) {
				$txt .= "Plats $i : Bidrag nr $elem\n";
				$i++;
		}
		
		$myfile = fopen("mello_ajax_server.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $txt);
		fclose($myfile);
	}
	
	function set_guesses($user_id, $subcontest, $arr_items) {
//		$melloDb->set_guesses_for_id($user_id, $subcontest, $arr_items);
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if($_POST["action"] == "rearrange")
		{
			$is_locked = $melloDb->get_is_locked($_GET["subcontest"]);
//			if( $is_locked == 0 )
			{
				$melloDb->set_guesses_for_id($_POST["user_id"], $_POST["subcontest"], $_POST["item"]);
			}
		}
	}
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if($_GET["action"] == "is_locked") {
			$is_locked = $melloDb->get_is_locked($_GET["subcontest"]);
			echo json_encode( $is_locked );
		}
	}	
	
//	write_to_file();
	
?>