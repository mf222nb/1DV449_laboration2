<?php

/**
* Called from AJAX to add stuff to DB
*/
function addToDB($message, $user) {
	$db = null;
    $time = time();

    if(empty($message) || empty($user)){
        return false;
    }
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
	
	$q = "INSERT INTO messages (message, name, time) VALUES(?, ?, ?)";
    $params = array($message, $user, $time);
	
	try {
        $stm = $db->prepare($q);
        $stm->execute($params);
        $r = $stm->fetchAll();
        if(!$r) {
            return "Could not save the message";
        }
	}
	catch(PDOException $e) {}
	
	$q = "SELECT * FROM users WHERE username = ?";
    $params = array($user);

	try {
		$stm = $db->prepare($q);
		$stm->execute($params);
		$result = $stm->fetchAll();
		if(!$result) {
			return "Could not find the user";
		}
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	// Send the message back to the client
	echo "Message saved by user: " .json_encode($result);
	
}

