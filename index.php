<?php

require_once("./basic.php");
require_once("./login.php");
require_once("./logout.php");
require_once("./database.php");
require_once("./action.php");


if(isset($_SESSION['id'])){


//--------------------USER LOGGED IN!!

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

		function Ref() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          document.getElementById("stockcontent").innerHTML = xhttp.responseText;
        }
      };
      xhttp.open("GET", "offers.php", true);
      xhttp.send();
    }

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

    function placeBid() {

      compid=document.getElementById('compid').value;
      quantity=document.getElementById('quantity').value;
      price=document.getElementById('price').value;
      type=document.getElementById('type').value;

      if(compid == "" || quantity == "" || price == "" || type == ""){
        document.getElementById("bid-error").innerHTML = "All values not filled in!";
        return 1;
      }

      
      var regex = /^[0-9,.]+$/;
      if(!price.match(regex)){
        document.getElementById("bid-error").innerHTML = "Price must be a number only!";
        return 1;
      }
      var regex = /^[0-9]+$/;
      if(!quantity.match(regex)){
        document.getElementById("bid-error").innerHTML = "Quantity must be a number only!";
        return 1;
      }
      var regex = /^[0-1]+$/;
      if(!type.match(regex)){
        document.getElementById("bid-error").innerHTML = "Select from the list only";
        return 1;
      }
      var regex = /^[0-9]+$/;
      if(!compid.match(compid)){
        document.getElementById("bid-error").innerHTML = "Please refresh the page";
        return 1;
      }

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          document.getElementById("bid-error").innerHTML = xhttp.responseText;
        }
      };
      xhttp.open("POST", "actions.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("buysell=1&compid="+compid+"&quantity="+quantity+"&price="+price+"&type="+type);
    }

    function del(id) {

      //place code for confirming.

      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          //document.getElementById("bid-error").innerHTML = xhttp.responseText;
        }
      };
      xhttp.open("POST", "actions.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("delete="+id);
    }

    function showBid(id){
      document.getElementById("compid").value=id;
      document.getElementById("news-dialog").style.display='none';
      document.getElementById("bid-dialog").style.display='inline';
      document.getElementById("black-cover").style.display='inline';
    }

    function hideBid(){
      document.getElementById("compid").value=0;
      document.getElementById("black-cover").style.display='none';
      document.getElementById("bid-dialog").style.display='none';
      document.getElementById("bid-error").innerHTML="";
      document.getElementById("news-dialog").style.display='none';
    }
    function showNews(){
      document.getElementById("compid").value=0;
      document.getElementById("black-cover").style.display='inline';
      document.getElementById("bid-dialog").style.display='none';
      document.getElementById("bid-error").innerHTML="";
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
      <a href="mypage.php">My Page</a><br/>
	   	<?php if($_SESSION['id']==1){ ?><a href="/admin.php">Admin Panel</a><br/><?php } ?>
      <a href="instr.php">Instructions and Rules</a><br/>
    </div>

	</div><br/><br/><br/>

	<div class="heading">
	<h1>Wolf of Wallstreet</h1>
	</div>

  <div class="form">
    <center>
    	<div id="stockcontent">
        <span style="color:#fff;font-weight:20px;font-size:20px;">Loading...</span>
      </div>
      <script>setInterval(Ref, 1000);</script>
    </center>  
	</div> <!-- /form -->
  
  
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>


<?php }else{ 












































//---------------------USER NOT LOGGED IN!!
?>

<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Wolf of Wall Street : Login / Signup</title>
    <link rel='stylesheet' href="css/font.css"  type='text/css'>
    
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/style.css">

    
  </head>

  <body>

	<div class="heading">
	<h1>Wolf of Wallstreet</h1>
	</div>

    <div class="form">
      
      <!--<ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
      </ul>-->
        
        <div id="login">   
          <h1>Greed is Good!</h1>
          <h4 class="formerr"><?php echo $loginerr; ?></h4>
          
          <form action="/" method="post">
          
            <div class="field-wrap">
            <label>
              UserID<span class="req">*</span>
            </label>
            <input type="text" name="user" required autocomplete="off"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" name="password" required autocomplete="off"/>
          </div>
          <input type="hidden" name="login" value="Login" />
          <button class="button button-block"/>Log In</button>
          
          </form>

        </div>
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>

<?php
};
?>
