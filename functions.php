<?php

	require("../../config.php");

	//see fail peab olema kõigil lehtedel kus tahan kasutada SESSION muutujat
	session_start();

	//SIGNUP
	
	
	function signUp ($email, $password) {
		
		$database = "if16_karlkruu";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt=$mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		
		echo $mysqli->error; 
		
		$stmt->bind_param("ss", $email, $password);
		
		if($stmt->execute()) {
			
			echo "salvestamine õnnestus";
		
		}else{
			echo "ERROR ".$stmt->error;
		}
	
		$stmt->close();
		$mysqli->close();
		
		
		
	}
	
	
	function login($email, $password){
		
		$error= "";
		
		$database = "if16_karlkruu";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt=$mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email=?");
		
		echo $mysqli->error; 
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran väärtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist või mitte
		//on tõene kui on vähemalt 1 vaste
		if($stmt->fetch()){
			
			//oli sellise meiliga kasutaja
			
			//password millega kasutaja tahab sisse logida
			$hash=hash("sha512", $password);
			if($hash==$passwordFromDb){
				echo "Kasutaja logis sisse ".$id;
			
				//määran sessiooni muutujad millele saan ligi teistelt lehtedelt
				$_SESSION["userId"]=$id;
				$_SESSION["userEmail"]=$emailFromDb;
				
				$_SESSION["message"]="<h1>Tere tulemast!</h1>";
				
				
				header("Location: data.php");
				exit();
			
			}else{
				$error="vale parool";
			}
			
			
			
		}else{
			//ei leidnud kasutajat selle meiliga
			$error="ei ole sellist e-maili";
		}
		
		return $error;
		
	}
	
	
	function saveCar ($plate, $color) {
		
		$error= "";
		
		$database = "if16_karlkruu";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt=$mysqli->prepare("INSERT INTO cars_and_colors (plate, color) VALUES (?, ?)");
		
		echo $mysqli->error; 
		
		$stmt->bind_param("ss", $plate, $color);
		
		if($stmt->execute()) {
			
			echo "salvestamine õnnestus";
		
		}else{
			echo "ERROR ".$stmt->error;
		}
	
		$stmt->close();
		$mysqli->close();
		
	}

	function getAllCars() {
		$database = "if16_karlkruu";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt=$mysqli->prepare("
		
			SELECT id, plate, color
			FROM cars_and_colors
			WHERE deleted IS NULL
		
		");
		
		$stmt->bind_result($id, $plate, $color);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result=array();
		
		//tee seda seni, kuni on rida andmeid
		//mis vastab select lausele
		while($stmt->fetch()) {
			
			//tekitan objekti
			$car=new StdClass();
			$car->id=$id;
			$car->plate=$plate;
			$car->color=$color;
			
			
			echo $plate."<br>";
			//iga kord massiivi lisan juuurde numbrimärgi
			array_push($result, $car);
		}
		
		
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
		
	}
	
	
	function cleanInput($input){
		
		//Tõkestame sisestusel pahatahtlike käskude rakendumist.
		$input=trim($input);
		$input=htmlspecialchars($input);
		$input=stripslashes($input);
		
		return $input;
		
	}
	
	function saveInterest ($interest) {
		
		
 		
 		$database = "if16_karlkruu";
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
 
 		$stmt = $mysqli->prepare("INSERT INTO interests (interest) VALUES (?)");
 	
 		echo $mysqli->error;
 		
 		$stmt->bind_param("s", $interest);
 		
 		if($stmt->execute()) {
 			echo "salvestamine õnnestus";
 		} else {
 		 	echo "ERROR ".$stmt->error;
 		}
 		
 		$stmt->close();
 		$mysqli->close();
 		
 	}
	
	function getAllInterests() {
		
		$database = "if16_karlkruu";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT id, interest FROM interests
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	
	function getUserAllInterests() {
		
		$database = "if16_karlkruu";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("
			SELECT interest FROM interests
			JOIN user_interests ON interests.id=user_interests.interest_id
			WHERE user_interests.user_id=?
		");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function saveUserInterest ($interest) {
 		
 		$database = "if16_karlkruu";
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
 
		$stmt=$mysqli->prepare("SELECT id FROM user_interests WHERE user_id=? AND interest_id=?");
		$stmt->bind_param("ii", $_SESSION["userId"], $interest);
		$stmt->bind_result($id);
		$stmt->execute();
		if($stmt->fetch()){
			//oli olemas juba selline rida
			echo "juba olemas";
			return;
			//pärast returni midagi edasi ei tehta funktsioonis
			
		}
		
		$stmt->close();
		
		//kui ei olnud siis sisestan
 
 		$stmt=$mysqli->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");
		
		$stmt->bind_param("ii", $_SESSION["userId"], $interest);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
 	}
	

	
	?>