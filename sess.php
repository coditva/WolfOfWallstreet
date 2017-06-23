<?php

session_start();

require_once("./database.php");
$sqlresult = mysqli_query($conn, "SELECT name,amount FROM users WHERE id=".$_SESSION['id']);
while($row = mysqli_fetch_assoc($sqlresult)){
	$_SESSION['name'] = $row['name'];
	$_SESSION['amount'] = $row['amount'];
}

?>