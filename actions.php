<?php
require_once('./basic.php');
require_once('./database.php');


if(!isset($_SESSION['id']))
	header("Location:index.php");


if(isset($_SESSION['id']) && isset($_POST['buysell'])){												//IF LOGGED IN
	

$success = 1;
$bidfound = 0;

	try{																																				//ALL CODE BEGINS FROM HERE

		if(!isset($_POST['buysell']))																							//FORM NOT USED
			throw new Exception("Access Denied!");

//---------------------------- CODE FOR DATA VALIDATION GOES HERE ------------------------------------------
		$postuserid = $_SESSION['id'];
		$postcompid = validate($_POST['compid']);
		$postquantity = validate($_POST['quantity']);
		$postprice = validate($_POST['price']);
		$posttype = validate($_POST['type']);



//------------------------------- CODE FOR ERRORS GOES HERE ------------------------------------------------
		if($postquantity == null || $postprice == null || $postcompid == null || ($posttype != 1 && $posttype != 0) ){		
			throw new Exception("All parameters not filled in");										//ALL PARAMETERS NOT FILLED IN
		}

		
		$sqlresult = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$postcompid);	//THE COMPANY DOES NOT EXIST IN THE TABLE
		while($row = mysqli_fetch_assoc($sqlresult)){
			$compprice = $row['price'];
		}
		if(!mysqli_num_rows($sqlresult)){
			throw new Exception("The company does not exist");
		}

		if(!$posttype && $_SESSION['amount'] < ($postprice * $postquantity) ){									//USER DOESNT HAVE ENOUGH MONEY IN ACCOUNT
			throw new Exception("You don't have enough money in your account");
		}


		if($compprice == 0){
			throw new Exception("Company price has been set to zero!");
		}

		if(isset($compprice) && $postquantity < (2000/$compprice) ){							//QUANTITY NOT IN LIMITS | THE QUANTITY SHOULD BE SUCH THAT THE TOTAL PRICE IS MORE THAN 2000
			throw new Exception("Quantity not in limits");
		}


		if(isset($compprice) && ($postprice > (1.015 * $compprice) || $postprice < (0.985 * $compprice))){
			throw new Exception("Price should be within 1.5% change only");					//ONLY 1.5% CHANGE ALLOWED
		}


		$sqlresult = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid);
		$total = 0;
		while($row = mysqli_fetch_assoc($sqlresult)){															//USER DOESNT OWN THE STOCKS
			$total += $row['quantity'];
		}
		if($posttype && ($postquantity > $total)){
			throw new Exception("You don't own the stocks");
		}




//----------------------------- CODE FOR TRANSACTION GOES HERE ---------------------------------------------

		if(!$posttype){																														//USER BUYING


			$tempquantity=$postquantity;
			$sqlresult = mysqli_query($conn, "SELECT * FROM buysell WHERE userid <> ".$postuserid." AND compid = ".$postcompid." AND type <> ".$posttype." AND  price = ".$postprice);
			while($row = mysqli_fetch_assoc($sqlresult)){														//AN OPPOSITE BID EXISTS

				
				if( $_SESSION['amount'] < ($postprice * $tempquantity) ){					//USER DOES NOT HAVE ENOUGH MONEY
					break;
				}

				$bidfound = 1;
				$sb = 0;

				if($tempquantity >= $row['quantity']){																//THE OPPOSITE BID IS LESSER THAN THE USER BID

					//test stock table
					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $row['quantity'])
						continue;


					//delete the row which offered the stocks
					runMyQuery("DELETE FROM buysell WHERE id=".$row['id']);

					//update log table
					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $postuserid .",
						". $row['userid'] .",
						". $postcompid .",
						". $row['quantity'] .",
						". $postprice ."
					) ");


					//noss = no of stocks sold
					$noss = $row['quantity'];
					$sb += $row['quantity'];

					//take the stocks from the seller
					$curr=mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($noss >= $cs['quantity']){

							runMyQuery("DELETE FROM current WHERE id=".$cs['id']);

							$noss -= $cs['quantity'];
						}
						else{

							runMyQuery("UPDATE current SET quantity = ". ( $cs['quantity'] - $noss ) ." WHERE id=".$cs['id']);
							$noss = 0;
						}

						if($noss == 0)
							break;


					}

					//update the amount in sellers account
					updateAmount($row['userid'], ($postprice * $row['quantity']) );


					//update the amount in user's (buyer) account
					updateAmount($postuserid, -($postprice * $row['quantity']) );




					$tempquantity -= $row['quantity'];

				}else{			
																																			//OPPOSITE BID IS GREATER THAN USER BID;

					//noss = no of stocks sold
					$noss = $tempquantity;
					$sb += $tempquantity;

					//test stock table
					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $tempquantity){
						continue;
					}


					//update the row which offers the stocks
					runMyQuery("UPDATE buysell SET quantity=".( $row['quantity'] - $noss )." WHERE id=".$row['id']);


					//update log table
					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $postuserid .",
						". $row['userid'] .",
						". $postcompid .",
						". $tempquantity .",
						". $postprice ."
					) ");
;

					//take the stocks from the seller
					$curr=mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($noss >= $cs['quantity']){

							runMyQuery("DELETE FROM current WHERE id=".$cs['id']);
							$noss -= $cs['quantity'];
						}
						else{

							runMyQuery("UPDATE current SET quantity = ". ( $cs['quantity'] - $noss ) ." WHERE id=".$cs['id']);
							$noss = 0;
						}

						if($noss == 0)
							break;

					}


					//update the amount in sellers account
					updateAmount($row['userid'], ($postprice * $tempquantity) );


					//update the amount in user's (buyer) account
					updateAmount($postuserid, - ($postprice * $tempquantity) );



					$tempquantity = 0;
					
				}



				//give stocks to the current user (buyer)
				$found = 0;
				$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$postcompid." AND price=".$postprice);
				while($cs = mysqli_fetch_assoc($curr)){

					runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] + $sb)." WHERE id=".$cs['id'] );
					$found=1;
				}
				if(!$found){
					runMyQuery("INSERT INTO current (userid, compid, price, quantity) VALUES (
						".$postuserid.",
						".$postcompid.",
						".$postprice.",
						".$sb."
					)");
				}
			}











































		}else{																																		//USER SELLING


			$tempquantity = $postquantity;
			$sqlresult = mysqli_query($conn, "SELECT * FROM buysell WHERE userid <> ".$postuserid." AND compid = ".$postcompid." AND type <> ".$posttype." AND  price = ".$postprice);
			while($row = mysqli_fetch_assoc($sqlresult)){														//AN OPPOSITE BID EXISTS


				if( getAmount($row['userid']) < ($postprice * $postquantity) ){				//BUYER DOES NOT HAVE ENOUGH MONEY
					continue;
				}

				$bidfound = 1;
				$sb = 0;

				if($tempquantity >= $row['quantity']){																//THE CURRENT BID QUANTITY IS MORE THAN THAT IN THE DATABASE


					//test stock table
					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $row['quantity'])
						continue;


					//delete the row which wanted the stocks
					runMyQuery("DELETE FROM buysell WHERE id=".$row['id']);

					//update log table
					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $row['userid'] .",
						". $postuserid .",
						". $postcompid .",
						". $row['quantity'] .",
						". $postprice ."
					) ");


					//nosb = no of stocks bought
					$nosb = $row['quantity'];
					$sb += $row['quantity'];

					//take the stocks from the user (seller)
					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($cs['quantity'] > $nosb){

							runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] - $nosb )." WHERE id=".$cs['id'] );
							$nosb=0;
						}
						else{

							runMyQuery("UPDATE current SET quantity=".( $nosb - $cs['quantity'] )." WHERE id=".$cs['id'] );
							$nosb -= $cs['quantity'];
						}
						if(!$nosb){

							break;
						}

					}
					if($nosb){
						throw new Exception("Code bug at place 2");
					}

					//give stocks to the buyer
					$found=0;
					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid." AND price=".$postprice);
					while($cs = mysqli_fetch_assoc($curr)){

						runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] + $row['quantity'])." WHERE id=".$cs['id'] );
						$found=1;
					}
					if(!$found){

						runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$row['userid'].",
							".$postcompid.",
							".$row['quantity'].",
							".$postprice."
						)");
					}



					//update the amount in buyers account
					updateAmount($row['userid'], - ($postprice * $row['quantity']) );


					//update the amount in sellers account
					updateAmount($postuserid, + ($postprice * $row['quantity']) );
					$tempquantity -= $row['quantity'];



				}else{
					//tempquantity is less than the bid quantity


					//test stock table
					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $tempquantity)
						continue;


					//update the row which offers the stocks
					runMyQuery("UPDATE buysell SET quantity=".( $row['quantity'] - $tempquantity )." WHERE id=".$row['id']);

					//update log table
					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $row['userid'] .",
						". $postuserid .",
						". $postcompid .",
						". $tempquantity .",
						". $postprice ."
					) ");



					//take the stocks from the user (seller)
					$nosb = $tempquantity;
					$sb += $tempquantity;
					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($cs['quantity'] > $nosb){

							runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] - $nosb )." WHERE id=".$cs['id'] );
							$nosb = 0;
						}
						else{

							runMyQuery("DELETE FROM current WHERE id=".$cs['id'] );
							$nosb -= $cs['quantity'];
						}
					}
					if($nosb){
						throw new Exception("Code bug at place 4");
					}

					//give stocks to the buyer
					$found=0;

					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid." AND price=".$postprice);
					while($cs = mysqli_fetch_assoc($curr)){

						runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] + $tempquantity)." WHERE id=".$cs['id'] );
						$found=1;
					}
					if(!$found){

						runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$row['userid'].",
							".$postcompid.",
							".$tempquantity.",
							".$postprice."
						)");
					}

					//update the amount in buyers account
					updateAmount($row['userid'], - ($postprice * $tempquantity) );


					//update the amount in sellers account
					updateAmount($postuserid, + ($postprice * $tempquantity) );
					$tempquantity = 0;





					if($tempquantity == 0)
						break;
				}
			}


		}

























		

		if($bidfound){
			$pprice = 0;																															//SET NEW COMPANY PRICE

			$compres = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$postcompid);
			while($price = mysqli_fetch_assoc($compres)){
				$pprice = $price['price'];
			}

			if(!$pprice){
				throw new Exception("Code bug at place 3");
			}else{
				runMyQuery("UPDATE comp SET price=".$postprice." , pprice=".$pprice." WHERE id=".$postcompid);
			}
		}

		if($tempquantity){																												//STILL SOME STOCKS WHERE LEFT WITHOUT OPPOSITE BID

			//create a new bid entry for the remaining quantity
			runMyQuery("INSERT INTO buysell (userid, compid, price, quantity, type)
				VALUES (
					".$postuserid.",
					".$postcompid.",
					".$postprice.",
					".$tempquantity.",
					".$posttype."
				)");
		}





































	}catch(Exception $e){
		echo $e->getMessage();																										//DISPLAY THE ERROR MESSAGE
		$success = 0;
	}

	if($success){
		echo "Your order was successful!";
	}



}








//------------------------------------------------------------ Admin panel registeration code here ---------------------------

if(isset($_SESSION['id'])){

	try{


//---------------------------------------------------	REGISTERING NEW USER ------------------------------
		if(isset($_POST['reguser'])){

			if(!isset($_SESSION['id']) || $_SESSION['id']!=1){
				throw new Exception("You don't have the access");
			}

			//VALIDATING DATA----------------------------
			$postname = validate($_POST['name']);
			$postpass = validate($_POST['password']);
			$postrepass = validate($_POST['re-password']);
			$postamount = 2000000;		//setting 20lac as the dafault amount

			if($postname == NULL || $postpass == NULL || $postrepass == NULL){
				throw new Exception("All Parameters must be filled");
			}

			$regS = 1;
			try{

				if($postpass != $postrepass){
					throw new Exception("Retyped password doesn't match");
				}

			}catch(Exception $error){
				echo $error -> getMessage();
				$regS = 0;
			}

			if($regS){
				
				runMyQuery("INSERT INTO users (name, password, amount) VALUES(
					'".$postname."',
					'".$postpass."',
					".$postamount."
				)");

				$result = mysqli_query($conn, "SELECT id FROM users WHERE name='".$postname."' AND password='".$postpass."'");
        while($row = mysqli_fetch_assoc($result)){
        	throw new Exception("Registeration successful! ID of new user: <b>".$row['id']."</b>");
        }
			}
		}



//--------------------------------------------------- REGISTERING NEW COMPANY-----------------------------------
		if(isset($_POST['regcomp'])){

			if(!isset($_SESSION['id']) || $_SESSION['id']!=1){
				throw new Exception("You don't have the access");
			}

			//VALIDATING DATA----------------------------
			$postname = validate($_POST['name']);
			$postprice = validate($_POST['price']);
			$postquantity = validate($_POST['quantity']);

			if($postname == NULL || $postprice == NULL || $postquantity == NULL){
				throw new Exception("All Parameters must be filled");
			}


			runMyQuery("INSERT INTO comp ( name, price, pprice ) VALUES(
				'".$postname."',
				".$postprice.",
				".$postprice."
			);");

			$id = 0;
			$result = mysqli_query($conn, "SELECT * FROM comp WHERE name='".$postname."' AND price=".$postprice);
      while($row = mysqli_fetch_assoc($result)){
      	$id = $row['id'];
      }

      runMyQuery("INSERT INTO buysell (userid, compid, quantity, price, type) VALUES(
				1,
				".$id.",
				".$postquantity.",
				".$postprice.",
				1
			);");

      echo("Registeration successful! ID of new company: <b>".$id."</b>");
		}
	}catch(Exception $e){
		echo $e->getMessage();																										//DISPLAY THE ERROR MESSAGE
	}
}


if(isset($_SESSION['id'])){
	if(isset($_POST['delete'])){
		$id = validate($_POST['delete']);
		$result = mysqli_query($conn, "SELECT id FROM buysell WHERE id=".$id." AND userid=".$_SESSION['id']);
    if(mysqli_num_rows($result) > 0){
    	runMyQuery("DELETE FROM buysell WHERE id=".$id);
    }
	}
}



















//------------------------------------------- GET NEWS -----------------------------------------------------
if(isset($_GET['getnews'])){
	$result = mysqli_query($conn, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
  while($row = mysqli_fetch_assoc($result)){ 

	  echo "<a href=\"news.php?id=".$row['id']."\"><div class=\"news-item\">";
	  echo $row['content']."<br/>";
	  echo "<div style=\"float:right;font-size:12px;\">".$row['time']."</div><br/>";
	  echo "</div></a>";

  }

  $result = mysqli_query($conn, "SELECT * FROM news");
  if (mysqli_num_rows($result) > 5){
  	echo "<a href=\"news.php\"><div class=\"news-item\"><center>Show More</center></div></a>";
  } 
}



//------------------------------------- RUN QUERY -------------------------------------------------------------
if(isset($_POST['query']) && isset($_SESSION['id']) && $_SESSION['id'] == 1){
	try{
		$query = validate($_POST['query']);
		runMyQuery($query);
	}catch(Exception $e){
		echo $e->getMessage();
	}
}

?>