<?php

	
	require("functions.php");
	
	//kui on juba sisse loginud, suunan data lehele
	if(isset ($_SESSION["userId"])){
	
		//suunan sisselogimise lehele 
		header("Location: data.php");
		exit();
		
	}
	
	//echo hash("sha512", "karl");

	//GET ja POSTI muutujad
	// var_dump ($_GET);
	// echo "<br>";
	// var_dump ($_POST);
	
	// MUUTUJAD
	
	$loginEmail="";
	$loginEmailError="";
	$loginPasswordError="";

	
	$signupEmailError="";
	$signupPasswordError="";
	$signupFirstNameError="";
	$signupLastNameError="";
	$signupDateError="";
	$termsAgreementError="";
	$signupEmail="";
	$signupSex="";
	
	$signupFirstName="";
	$signupLastName="";
	
	
	if(isset($_POST["loginEmail"])){
		if(empty($_POST["loginEmail"])){
			$loginEmailError="E-mail on sisestamata";
		}else{
			$loginEmail=$_POST["loginEmail"];
		}
	}
	
	if(isset($_POST["loginPassword"])){
		if(empty($_POST["loginPassword"])){
			$loginPasswordError="Parool on sisestamata";
		}else{
			$loginPassword=$_POST["loginPassword"];
		}
	}
	
	
	// on üldse olemas selline muutuja
	if(isset($_POST["signupEmail"])){
		
		//jah on olemas
		//kas on tühi
		if(empty($_POST["signupEmail"])){
			
			$signupEmailError= "E-postiaadress on sisestamata";
		}else{

			//email olemas
			$signupEmail=$_POST["signupEmail"];
		}
		
	}
	
	if(isset($_POST["signupPassword"])){
		
		if(empty($_POST["signupPassword"])){
			
			$signupPasswordError= "Parool on kohustuslik";
		}else{
			//kui parool oli olemas -isset
			//parool ei olnud tühi -empty
			
			if(strlen($_POST["signupPassword"])<8){
				
				$signupPasswordError="Parool peab olema vähemalt 8 tähemärki pikk";
			}
		}
	}

	if(isset($_POST["signupFirstName"])){
		
		if(empty($_POST["signupFirstName"])){
			
			$signupFirstNameError="Eesnime sisestamine on kohustuslik";
		}else{
			$signupFirstName=$_POST["signupFirstName"];
		}
	}
	if(isset($_POST["signupLastName"])){
		
		if(empty($_POST["signupLastName"])){
			
			$signupLastNameError="Perenime sisestamine on kohustuslik";
		}else{
			$signupLastName=$_POST["signupLastName"];
		}
	}
	
	if( isset( $_POST["signupSex"] ) ){
		
		if(!empty( $_POST["signupSex"] ) ){
		
			$signupSex = $_POST["signupSex"];
			
		}
		
	} 
	
	// peab olema email ja parool
	// ühtegi errorit
	
	if($signupEmailError == "" && //kontroll, et errorid on tühjad (loogiliselt võiks olla errorid pärast POSTe)
		empty ($signupPasswordError) && empty ($signupFirstNameError) && empty ($signupLastNameError) &&
		isset($_POST["signupEmail"])	&&
		isset($_POST["signupPassword"]) &&
		isset($_POST["signupFirstName"]) &&
		isset($_POST["signupLastName"])
			){
			
		//salvestame ab'i
		
		echo "Salvestan... <br>";
		echo "email: ".$signupEmail."<br>";
		echo "password: ".$_POST["signupPassword"]."<br>";
		
		$password = hash("sha512", $_POST["signupPassword"]);
		
		echo "password hashed: ".$password. "<br>";

		//echo $serverUsername;
		
		//Kasutan funktsiooni
		signUp($signupEmail, $password);
		
	}
	
	$error="";
	if(isset($_POST["loginEmail"]) && isset ($_POST["loginPassword"]) &&
		!empty($_POST["loginEmail"]) && !empty($_POST["loginPassword"])
		){
			$error=login($_POST["loginEmail"], $_POST["loginPassword"]);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Logi sisse või loo kasutaja</title>
</head>
<body style="background-color:white;"> <!--Taustavärv-->

	<h1>Logi sisse</h1>
	<form method="POST"><!--Refreshimisel küsib kinnitust; andmed ei jääks URL-i-->
		<p style="color:red;"><?=$error;?></p>
		<label>E-post</label><br>
		<input name="loginEmail" type="text" value="<?=$loginEmail;?>"> <?php echo $loginEmailError;?> <br><br>
		
		<input name="loginPassword" placeholder="Parool" type="password"> <?php echo $loginPasswordError; ?><br><br>
		
		<input type="submit" value="Logi sisse">
		
	<h1>Loo kasutaja</h1>
	<form method="POST">
		
		<label>Nimi</label><br>
		<input name="signupFirstName" placeholder="Eesnimi" type="text" value="<?=$signupFirstName;?>"> <?php echo $signupFirstNameError; ?><br>
		<input name="signupLastName" placeholder="Perenimi" type="text" value="<?=$signupLastName;?>"> <?php echo $signupLastNameError; ?><br><br>
		
		<label>Sünnipäev</label><br>
		<input type="date" name="signupDate"><br><br>
		
		<label>E-post</label><br>
		<input name="signupEmail" type="text" value="<?=$signupEmail;?>">  <?php echo $signupEmailError; ?> <!--value jätab emaili sisestatuks, siin echo lühendina-->
		<br><br>
		
		<input name="signupPassword" placeholder="Parool" type="password"> <?php echo $signupPasswordError; ?>
		<br><br>
		
		
		<?php if($signupSex == "Mees") { ?>
			<input name="signupSex" type="radio" value="Mees" checked> Mees
		<?php }else{ ?>
			<input name="signupSex" type="radio" value="Mees"> Mees
		<?php } ?>
		
	
		<?php if($signupSex == "Naine") { ?>
			<input name="signupSex" type="radio" value="Naine" checked> Naine
		<?php }else{ ?>
			<input name="signupSex" type="radio" value="Naine"> Naine
		<?php } ?>
		
		
		<br><br>
		<input type="checkbox" name="newsLetter" checked> Soovin uudiskirja
		<br><br>
		<input type="submit" value="Loo kasutaja">
		
		
	</form>

</body>
</html>