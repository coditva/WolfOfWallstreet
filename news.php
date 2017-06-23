<?php

require_once("./basic.php");
require_once("./logout.php");


if(isset($_SESSION['id'])){
  if(isset($_GET['id'])){
    $id = validate($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM news WHERE id=".$id);
    while($row = mysqli_fetch_assoc($result)){
      $compid = $row['compid'];
      $content = $row['content'];
      $time = $row['time'];

      $compresult = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$compid);
      while($comp = mysqli_fetch_assoc($compresult)){
        $compname = $comp['name'];
        $compprice = $comp['price'];
        $comppprice = $comp['pprice'];
      }
    }
  }
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
      <a href="mypage.php">My Page</a><br/>
	   	<?php if($_SESSION['id']==1){ ?><a href="/admin.php">Admin Panel</a><?php } ?><br/>
      <a href="instr.php">Instructions and Rules</a><br/>
    </div>

	</div><br/><br/><br/>

	<div class="heading">
	<h1>Wolf of Wallstreet</h1>
	</div>

  <div class="form">
  	<div id="stockcontent">
      <?php if(isset($compid)){ ?>
        
        <span style="text-align:left;font-size:35px;font-weight:bold;color:#fff"  onClick="window.location='company.php?id=<?php echo $compid ?>';"><?php echo $compname; ?></span><br/>
        <span style="font-size:25px;" class="price<?php if($comp['pprice']<$comp['price']){ echo "inc";}else if($comppprice>$compprice){echo "dec";}else{ echo "sta"; } ?>" >
          <?php echo $compprice; ?>
        </span><br/><br/>
        <span style="font-size:25px;color:#fff;"><?php echo $content ?></span><br/><br/>
        <div style="float:right;color:#fff;"><?php echo $time; ?></div>

      <?php }else{ ?>
        
        <?php 
          $result = mysqli_query($conn, "SELECT * FROM comp");
          while($comp = mysqli_fetch_assoc($result)){
            $cname[$comp['id']] = $comp['name'];
            $cprice[$comp['id']] = $comp['price'];
            $cpprice[$comp['id']] = $comp['pprice'];
          }

          $result = mysqli_query($conn, "SELECT * FROM news ORDER BY id");
          while($row = mysqli_fetch_assoc($result)){ ?>

            <span style="text-align:left;font-size:35px;font-weight:bold;color:#fff"><?php echo $cname[$row['compid']]; ?></span><br/>
            <span style="font-size:25px;" class="price<?php if($cpprice[$row['compid']]<$cprice[$row['compid']]){ echo "inc";}else if($cpprice[$row['compid']]>$cprice[$row['compid']]){echo "dec";}else{ echo "sta"; } ?>" >
              <?php echo $cprice[$row['compid']]; ?>
            </span><br/><br/>
            <span style="font-size:25px;color:#fff;"><?php echo $row['content'] ?></span><br/><br/>
            <div style="float:right;color:#fff;"><?php echo $row['time']; ?></div><br/><br/>
            <hr/><br/>

        <?php } ?>

      <?php } ?>
    </div>
	</div> <!-- /form -->
  
  
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>


<?php }else{ header("Location:index.php"); } ?>
