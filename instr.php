<?php

require_once("./basic.php");
require_once("./logout.php");


if(isset($_SESSION['id'])){
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
    <h1 style="font-size:50px;font-weight:bold;">Rules and Instructions</h1>
    <ul id="rules" type="square">
      <li>Each participant will be given a Wolf of Wallstreet login, password and a portfolio of Rs 2 lakh.</li>
      <li>Each participant will then be allowed to trade the stocks of the registered companies to earn profit.</li>
      <li>The event will continue for three days (5th, 6th &amp; 7th of February), begining on the 5th of February with an auction for the stocks of the registered companies.</li>
      <li>Fundamental ratios, Balance sheets and 1 year graphs will be provided for each company.</li>
      <li>News regarding the companies will be released on the website at regular intervals.</li>
      <li>Each trade will occur only at 1.5% of the last traded price.</li>
      <li>Any decisiont taken by the organizers will be final.</li>
    </ul>
    
	</div> <!-- /form -->
  
  
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>


<?php }else{ header("Location:index.php"); } ?>
