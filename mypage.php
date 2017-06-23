<?php

require_once("./basic.php");
require_once("./logout.php");


if(isset($_SESSION['id'])){
  $name = $_SESSION['name'];
  $id = $_SESSION['id'];
  $amount = $_SESSION['amount'];
?>


<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo $_SESSION['name']; ?>!</title>
    <link rel='stylesheet' href="css/font.css"  type='text/css'>
    
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/style.css">
    <script>

    function getNews() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          document.getElementById("news-content").innerHTML = xhttp.responseText;
        }
      };
      xhttp.open("GET", "actions.php?getnews", true);
      xhttp.send();
    }

    function hideBid(){
      document.getElementById("black-cover").style.display='none';
      document.getElementById("news-dialog").style.display='none';
    }
    function showNews(){
      document.getElementById("black-cover").style.display='inline';
      document.getElementById("news-dialog").style.display='inline';
      getNews();
    }
		</script>
    
  </head>

  <body>
  <div id="black-cover" onclick="hideBid();">&nbsp;</div>
  <div id="bid-dialog">
    <div style="float:right;padding:0px 5px;margin:0px;border:1px solid #fff;color:#fff;cursor:pointer"  onclick="hideBid()">X</div><br/>
    <div style="width:100%;text-align:center;padding:0px 0px 10px 0px;font-size:25px;color:#fff;">&nbsp; Place Bid</div>
    <div id="bid-error"></div>
    <div>
      <select name="type" id="type">
        <option value="0">Buy</option>
        <option value="1">Sell</option>
      </select><br/><br/>
      <input type="text" id="price" name="price" placeholder="Enter the price" /><br/>
      <input type="number" id="quantity" name="quantity" placeholder="Enter number of stock" /><br/>
      <input type="hidden" id="compid" name="compid" value="0" />
      <button class="bid-dialog-button" onclick="placeBid()">Place Bid</button>
    </div>
  </div>

  <div id="news-dialog">
    <div style="float:right;padding:0px 5px;margin:0px;border:1px solid #fff;color:#fff;cursor:pointer"  onclick="hideBid()">X</div><br/>
    <div style="width:100%;text-align:center;padding:0px 0px 10px 0px;font-size:25px;color:#fff;">&nbsp; News Board</div>
    <div id="news-content">
      <center style="font-size:20px;color:#fff"><br/><br/>Loading...<br/><br/></center>
    </div>
  </div>

	<div id="user">
		<div id="logout">
      <form action="./index.php" method="post" >
  			<input id="logoutbutton" type="submit" name="logout" value="Logout" /><br/>
      </form>
      <button id="news-button" onClick="showNews()">News</button>
		</div>

    <div>
  		<b><?php echo $_SESSION['name'] ?><br/></b>
	  	Balance: <b>Rs. <?php echo $_SESSION['amount'] ?>/-</b><br/>
      <a href="index.php">Home</a><br/>
	   	<?php if($_SESSION['id']==1){ ?><a href="/admin.php">Admin Panel</a><br/><?php } ?>
      <a href="instr.php">Instructions and Rules</a><br/>
    </div>

	</div><br/><br/><br/>

	<div class="heading">
	<h1>Wolf of Wallstreet</h1>
	</div>

  <div class="form">
  	<span style="font-size:40px;font-weight:bold;color:#fff;"><?php echo $name ?></span><br/>
    <span style="font-size:30px;font-weight:bold;color:#fff;">Rs. <?php echo $amount ?>/-</span><br/><br/><br/>

    <span style="font-size:25px;font-weight:bold;color:#fff;">Stocks I own:</span>
    <?php $res = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$id);  $total = 0;
      if (mysqli_num_rows($res) > 0) { ?>
    <center>
      <table id="own-stocks">
        <tr>
          <td><b>Company</b></td>
          <td><b>Quantity</b></td>
          <td><b>Price</b></td>
          <td><b>Total</b></td>
        </tr>
        <?php $result = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$id); $count = 0;
          while($stock = mysqli_fetch_assoc($result)){ $count++; $total += $stock['price'] * $stock['quantity']; ?>

        <tr <?php if($count%2 == 1){?>style="background-color:rgba(0,0,0,0.5)"<?php } ?>>
          <td><?php $compresult = mysqli_query($conn, "SELECT name FROM comp WHERE id=".$stock['compid']);
          while($comp = mysqli_fetch_assoc($compresult)){ echo $comp['name']; } ?></td>
          <td><?php echo $stock['quantity'] ?></td>
          <td><?php echo $stock['price'] ?></td>
          <td><?php echo $stock['quantity'] * $stock['price'] ?></td>
        </tr>

        <?php } ?>
      </table>
    </center>
    <?php }else{ ?>
      <br/>
      <span style="font-size:20px;color:#fff;">You don't own any stocks.</span>
    <?php } ?><br/><br/>


    <span style="font-size:25px;font-weight:bold;color:#fff;">My Bids:</span>
    <?php $res = mysqli_query($conn, "SELECT * FROM buysell WHERE userid=".$id);
      if (mysqli_num_rows($res) > 0) { ?>
    <center>
      <table id="own-bids">
        <tr>
          <td><b>Company</b></td>
          <td><b>Quantity</b></td>
          <td><b>Price</b></td>
          <td><b>Type</b></td>
        </tr>
        <?php $result = mysqli_query($conn, "SELECT * FROM buysell WHERE userid=".$id);$count = 0;
          while($bid = mysqli_fetch_assoc($result)){$count++; ?>
        <tr <?php if($count%2 == 1){?>style="background-color:rgba(0,0,0,0.5)"<?php } ?>>
          <td><?php $compresult = mysqli_query($conn, "SELECT name FROM comp WHERE id=".$bid['compid']);
          while($comp = mysqli_fetch_assoc($compresult)){ echo $comp['name']; } ?></td>
          <td><?php echo $bid['quantity'] ?></td>
          <td><?php echo $bid['price'] ?></td>
          <td><?php if($bid['type']) echo "Sell"; else echo "Buy"; ?></td>
        </tr>

        <?php } ?>
      </table>
    </center>
    <?php }else{ ?>
      <br/>
      <span style="font-size:20px;color:#fff;">You have no bids.</span>
    <?php } ?>

    <br/><br/><br/>

    <table id="profit-table">
      <tr><td>
      <?php $diff = ($amount - 200000); if($diff >= 0){ ?>
        Profit: </td><td>Rs. <?php echo $diff; ?>/-
      <?php }else{ ?>
        Loss: </td><td>Rs. <?php echo - $diff; ?>/-
      <?php } ?>
      </td></tr>
      <tr><td>Money in Stocks:</td>
      <td>Rs. <?php echo $total; ?>/-</td></tr>
    </table>

	</div> <!-- /form -->
  
  
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>


<?php }else{ header("Location:index.php"); } ?>
