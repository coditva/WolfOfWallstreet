<?php 

require_once("./database.php");
$company = "SELECT * FROM comp";
$compresult = mysqli_query($conn, $company);


echo ('<table>
							<tr>
								<td><b>Company name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								<td><b>Price</b></td>
							</tr>');
while($comp = mysqli_fetch_assoc($compresult)){ 
	
	$tcompany[$comp['id']]=$comp['name'];
	echo('
<tr>
	<td>'.$comp['name'].'</td>
	<td class="price');
	if($comp['pprice']<$comp['price']){ 
		echo "inc";
	}else if($comp['pprice']>$comp['price']){
		echo "dec";
	}else{ 
		echo "sta";
	}
	echo('">');
	echo $comp['price'];
	echo '</td>
</tr>';	
 }
 echo ('</table>');




  ?>