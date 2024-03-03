<?php

/////////////////////////////////////////////
// Set up initial database and initial tables
// The information about the database is
// stored in mello_db.php
/////////////////////////////////////////////

require 'mello_db.php';

function create_database() {
	global $servername, $username, $password, $dbname;
	
	// Create connection witout database
	$conn = new mysqli($servername, $username, $password);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Create database
	$sql = "CREATE DATABASE " . $dbname;
	if ($conn->query($sql) === TRUE) {
		echo "Database created successfully";
	} else {
		echo "Error creating database: " . $conn->error;
	}
	$conn->close();	
}

//
// NOTE:
// We don't want to do this. Because we already have the database
// and a lot of history in it.
//
// //create_database();
// $melloDb = new MelloDB();
// $melloDb->create_users_table();
// $melloDb->create_sub_contest_table();
// $melloDb->add_user("Ola", "Wistedt");
// $melloDb->add_user("Martin", "Samuelsson");
// $melloDb->add_user("Johan", "Lindstr�m");
?>
