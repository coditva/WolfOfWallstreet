<?php
require_once("./database.php");
$loginerr="<br/>";
if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['login'])){

	$sql = "SELECT id, name, password, amount FROM users";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_assoc($result)){
		if($_POST['user']==$row["id"] && $_POST['password']==$row["password"]){
			$_SESSION['id']=$row["id"];
			$_SESSION['name']=$row["name"];
			$_SESSION['amount']=$row["amount"];
		}
		else{
			$loginerr="Invalid UserID/Password combination";
		}
	}
}

?>