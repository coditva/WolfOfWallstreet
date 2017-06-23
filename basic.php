<?php

session_start();

require_once("./database.php");

if(isset($_SESSION['id'])){
	$sqlresult = mysqli_query($conn, "SELECT name,amount FROM users WHERE id=".$_SESSION['id']);
	while($row = mysqli_fetch_assoc($sqlresult)){
		$_SESSION['name'] = $row['name'];
		$_SESSION['amount'] = $row['amount'];
	}
}


//----------------------------------------------------- THE FUNCTIONS ARE HERE ----------------------------------------------

function validate($data) {																										//VALIDATES INPUT DATA
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function RunMyQuery($sql){																										//RUNS THE MYSQL QUERY
	if(!mysqli_query($GLOBALS['conn'], $sql)){
		throw new Exception("The following query did not succeed: ".$sql."\n Because:".$GLOBALS['conn']->error."\n");
	}
}

function updateAmount($id,$amount){
	$sqlresult = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE id=".$id);
	while($row = mysqli_fetch_assoc($sqlresult)){
		$amount += $row['amount'];
	}

	if($amount < 0){
		throw new Exception("The amount is going negative! Bug in code!");
	}

	runMyQuery("UPDATE users set amount=".$amount." WHERE id=".$id);
}

function getAmount($id){
	$amount = 0;
	$sqlresult = mysqli_query($GLOBALS['conn'], "SELECT amount FROM users WHERE id=".$id);
	while($row = mysqli_fetch_assoc($sqlresult)){
		return $row['amount'];
	}
	throw new Exception("Bug in code at place 1");
}


?>