<?php

$servername = "witech.se.mysql";
$username = "witech_se";
$password = "34H26SzT";
$dbname = "witech_se";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "melloDB1";

class MelloDB {

	private $conn;
	
	function __construct() {
		global $servername, $username, $password, $dbname;
		
		// Create connection WITH database
		$this->conn = new mysqli($servername, $username, $password, $dbname);	
		// Check connection
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
	}
	
	function __destruct() {
		$this->conn->close();	
	}
	
	function create_users_table()
	{
		// sql to create table of users
		$sql = "CREATE TABLE mello_users (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		firstname VARCHAR(30) NOT NULL,
		lastname  VARCHAR(30) NOT NULL,
		reg_date  TIMESTAMP
		)";
		
		if ($this->conn->query($sql) === TRUE) {
			echo "Table Users created successfully";
		} else {
			echo "Error creating table: " . $this->conn->error;
		}
	}
	
	function create_sub_contest_table()
	{
		// sql to create table of users
		$sql = "CREATE TABLE mello_sub_contest (
		id INT(6)     UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name          VARCHAR(30) NOT NULL,
		count_system  VARCHAR(30) NOT NULL,
		is_locked     BOOLEAN NOT NULL default 0)";
		
		if ($this->conn->query($sql) === TRUE) {
			echo "Table Users created successfully";
		} else {
			echo "Error creating table: " . $this->conn->error;
		}
	}

	function add_user($fname, $lname) {
		$sql = "INSERT INTO mello_users (firstname, lastname)
	   	VALUES ('" . $fname . "', '" . $lname . "')";
	
		if ($this->conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $this->conn->error;
		}
	}
	
	function get_users() {
		$sql = "SELECT id, firstname, lastname FROM mello_users";
		$result = $this->conn->query($sql);
	
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
//				print_r($row);
				echo "id: " . $row["id"]. " - First name: " . $row["firstname"]. " Last name: " . $row["lastname"]. "\n";
			}
		} else {
			echo "0 results";
		}
	}
	
	function get_sub_contests() {
		$sql = "SELECT name FROM mello_sub_contest";
		$result = $this->conn->query($sql);
	
		$arr = array();
		$i = 0;		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$arr[$i] = $row["name"];
				$i++;
			}
		}
		return $arr;
	}
	
	function add_sub_contest($name, $sub_count_system, $sub_nr_of_songs ) {
		$sql = "INSERT INTO mello_sub_contest (name, count_system)
     	VALUES ('".$name."', '".$sub_count_system."')";
		
		if ($this->conn->query($sql) === TRUE) {			
			// Create a table for competing songs
			$table_name = "mello_melodies_".$name;
			$sq2 = "CREATE TABLE ".$table_name." (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			artist  VARCHAR(60) NOT NULL default '',
			title   VARCHAR(60) NOT NULL default '',
			result  INTEGER     NOT NULL default 0)";				
			if ($this->conn->query($sq2) === FALSE) {
				echo "Error creating melodies table: " . $this->conn->error;
			}
			
			// Fill the table with nr of songs empty values
			for ($i = 0; $i < $sub_nr_of_songs; $i++) {
				$sq3 = "INSERT INTO ".$table_name." ( artist )
				VALUES ('')";
				if ($this->conn->query($sq3) === FALSE) {
					echo "Error: " . $sql . "<br>" . $this->conn->error;
				}
			}
			
			// Create a table for the competition, a table where
			// users put their guesses
			$table_name = "mello_competition_".$name;
			$sq4 = "CREATE TABLE ".$table_name." (
			user_id INTEGER NOT NULL default 0";
			for ($i = 1; $i <= $sub_nr_of_songs; $i++) {
				$sq4 .= ", guess$i INTEGER NOT NULL default 0";
			}
			$sq4 .= ")";
			if ($this->conn->query($sq4) === FALSE) {
				echo "Error creating competition table: " . $this->conn->error;
			}
			
		} else {
			echo "Error: " . $sql . "<br>" . $this->conn->error;
		}		
	}

	function update_melody_nr($nr, $subcontest, $artist, $title, $result) {
		$table_name = "mello_melodies_".$subcontest;
		$sql = "UPDATE ".$table_name." SET
		artist='".$artist."', 
		title='".$title."', 
		result='".$result."' WHERE id='".$nr."'";		
		if ($this->conn->query($sql) === FALSE) {
		    echo "Error updating record: " . $this->conn->error;
		}
	}
	
	function get_melodies($subcontest) {
		$table_name = "mello_melodies_".$subcontest;
		$sql = "SELECT * FROM ".$table_name;
		$result = $this->conn->query($sql);
		return $result->fetch_all();
	}
	
	function get_user_id($fname, $lname) {
		$sq1 = "select id from mello_users where firstname='$fname' AND lastname='$lname'";
		$result = $this->conn->query($sq1);
		if ($result->num_rows > 0) {
			return $result->fetch_row()[0];
		}
		return 0; // User doesn't exist in database
	}
	
	function get_guesses_for_id($subcontest, $user_id) {
		$table_name = "mello_competition_".$subcontest;		
		$sq1 = "select * from $table_name where user_id='$user_id'";
		$result = $this->conn->query($sq1);
		if ($result->num_rows > 0) {
			return $result->fetch_row();
		}
		return 0; // User doesn't exist in database
	}
	
	function add_user_to_competition($subcontest, $user_id) {
		$table_name = "mello_competition_".$subcontest;
		$sql = "INSERT INTO $table_name (user_id) VALUES ('$user_id')";
		
		if ($this->conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $this->conn->error;
		}
	}
	
	function set_guesses_for_id($user_id, $subcontest, $newguesses) {
		$table_name = "mello_competition_".$subcontest;
		$sql =  "UPDATE $table_name SET ";
		for ($i = 0; $i < count($newguesses); $i++) {
			if($i > 0) {
				$sql .= ",";
			}
			$nr = $i + 1; 
			$sql .= "guess$nr='$newguesses[$i]'";
		}
		$sql .= " WHERE user_id='$user_id'";		
		if ($this->conn->query($sql) === FALSE) {
		    echo "Error updating record: " . $this->conn->error;
		}		
	}
	
	function get_compeditors($subcontest) {
		$table_name = "mello_competition_".$subcontest;
		$sql = "SELECT * FROM ".$table_name;
		$result = $this->conn->query($sql);
		return $result->fetch_all();
	}
	
	function get_user_with_id($id) {
		$sq1 = "select firstname, lastname from mello_users where id='$id'";
		$result = $this->conn->query($sq1);
		if ($result->num_rows > 0) {
			return $result->fetch_row();
		}
		return 0; // User doesn't exist in database
	}
	
	function set_is_locked($subcontest, $is_locked) {
		$sql =  "UPDATE mello_sub_contest SET is_locked=$is_locked where name='$subcontest'";
		$this->conn->query($sql);
	}
	
	function get_is_locked($subcontest) {
		$sql =  "select is_locked from mello_sub_contest where name='$subcontest'";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			return $result->fetch_row()[0];
		}
		return 0; // Don't know, but better be open than locked
	}
	
	function get_count_system($subcontest) {
		$sql =  "select count_system from mello_sub_contest where name='$subcontest'";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			return $result->fetch_row()[0];
		}
		return 0; // Don't know
	}
	
	function get_users_table() {
		$sql = "SELECT id, firstname, lastname FROM mello_users";
		$result = $this->conn->query($sql);
		return $result->fetch_all();
	}
	
}
?>
