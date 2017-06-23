<?php

if(isset($_POST['logout'])){
	unset($_SESSION['id']);
}

?>