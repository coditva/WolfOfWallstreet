<?php
session_start();
require_once("./database.php");



if($_SESSION['id']==1){

?>
<html>
	<head>
		<title>
			Table view
		</title>
	</head>
	<body>
		<a href="./index.php">Home</a>
		<center>
		<form action="./tables.php" method="get">
			Select table:<br/>
			<input type="radio" name="tablename" value="users" id="users" />
			<label for="users">Users</label>
			<input type="radio" name="tablename" value="companies" id="comp" />
			<label for="comp">Companies</label>
			<input type="radio" name="tablename" value="balanceSheet" id="bal" />
			<label for="bal">Balance Sheet</label>
			<input type="radio" name="tablename" value="buysell" id="buysell" />
			<label for="buysell">Buy/Sell</label><br/><br/>
			<input type="submit" value="View Table" name="view">
			<input type="submit" value="Add Items" name="add">
		</form>
		<br/><br/><br/><br/><br/>
			<?php 
			if(isset($_GET['view'])){
			switch($_GET['tablename']){

				//---------------USERS------------------------------------------
					case "users":
					$sql = "SELECT id, name, password, amount FROM users";
					$result = mysqli_query($conn, $sql); 
					if (mysqli_num_rows($result) > 0) { 
						echo '<b style="font-size:25px">'.$_GET['tablename'].'</b><br/><br/>'; ?>
				<table>
					<tr>
						<td><b>ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Password &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					</tr>
				<?php	while($row = mysqli_fetch_assoc($result)) {
				  	echo "<tr><td>" . $row["id"]	. "</td><td>" . $row["name"]. "</td><td>" . $row["password"]. "</td><td>" . $row["amount"]. "</td></tr>";
				   } ?>
				</table>
				<?php }else{
					echo "0 results";
				};
				break; 

				//--------------COMPANIES-------------------------------------
				case "companies":
					$sql = "SELECT id, name, price, pprice FROM comp";
					$result = mysqli_query($conn, $sql); 
					if (mysqli_num_rows($result) > 0) { echo '<b style="font-size:25px">'.$_GET['tablename'].'</b><br/><br/>'; ?>
				<table>
					<tr>
						<td><b>ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Price &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Previous Price &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					</tr>
				<?php	while($row = mysqli_fetch_assoc($result)) {
				  	echo "<tr><td>" . $row["id"]	. "</td><td>" . $row["name"]. "</td><td>" . $row["price"]. "</td><td>" . $row["pprice"]. "</td></tr>";
				   } ?>
				</table>
				<?php }else{
					echo "0 results";
				};
				break;



				//--------------BUYSELL------------------------------------------
				case "buysell":
					$sql = "SELECT * FROM buysell";
					$result = mysqli_query($conn, $sql); 
					if (mysqli_num_rows($result) > 0) { echo '<b style="font-size:25px">'.$_GET['tablename'].'</b><br/><br/>'; ?>
				<table>
					<tr>
						<td><b>ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>UserID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Price &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Quantity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Companies &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					</tr>
				<?php	while($row = mysqli_fetch_assoc($result)) {
				  	echo "<tr><td>" . $row["id"]	. "</td><td>" . $row["userid"]. "</td><td>" . $row["price"]. "</td><td>" . $row["type"]. "</td><td>" . $row["quantity"]. "</td><td>" . $row["compid"]. "</td></tr>";
				   } ?>
				</table>
				<?php }else{
					echo "0 results";
				};
				break; 



				//-------------BALANCE SHEET---------------------------------
				case "balanceSheet":
					$sql = "SELECT id, userid, price, type, quantity FROM balsheet";
					$result = mysqli_query($conn, $sql); 
					if (mysqli_num_rows($result) > 0) { echo '<b style="font-size:25px">'.$_GET['tablename'].'</b><br/><br/>'; ?>
				<table>
					<tr>
						<td><b>ID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>UserID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Price &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Quantity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					</tr>
				<?php	while($row = mysqli_fetch_assoc($result)) {
				  	echo "<tr><td>" . $row["id"]	. "</td><td>" . $row["userid"]. "</td><td>" . $row["price"]. "</td><td>" . $row["type"]. "</td><td>" . $row["quantity"]. "</td></tr>";
				   } ?>
				</table>
				<?php }else{
					echo "0 results";
				};
				break; 
				

			}}else if(isset($_GET['add'])){ 
			switch($_GET['tablename']){

				case "users":
					header("Location:/reg.php");
				break;

				case "companies":
					header("Location:/regcomp.php");
				break;

				case "buysell":
					echo "You cant change that. It'll disturb the game!!";
				break;



			 }}; ?>
			
		</center>
	</body>
</html>

<?php }else{ ?>

<html>
	<head>
		<title>
			Table view
		</title>
	</head>
	<body>
		<center>
			You don't have authentication to view this page!
		</center>
	</body>
</html>

<?php } ?>