<?php

require_once("./basic.php");
require_once("./login.php");
require_once("./logout.php");
require_once("./database.php");
require_once("./action.php");

if(isset($_SESSION['id']) && $_SESSION['id'] == 1){

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
      function regUser() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            document.getElementById("formerr").innerHTML = xhttp.responseText;
          }
        };
        xhttp.open("POST", "actions.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        name=document.getElementById('name').value;
        password=document.getElementById('password').value;
        repassword=document.getElementById('re-password').value;
        xhttp.send("reguser=1&name="+name+"&password="+password+"&re-password="+repassword);


        document.getElementById('name').value="";
        document.getElementById('password').value="";
        document.getElementById('re-password').value="";
      }

      function regComp() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (xhttp.readyState == 4 && xhttp.status == 200) {
            document.getElementById("formerr").innerHTML = xhttp.responseText;
          }
        };
        xhttp.open("POST", "actions.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        name=document.getElementById('name').value;
        price=document.getElementById('price').value;
        quantity=document.getElementById('quantity').value;
        xhttp.send("regcomp=1&name="+name+"&price="+price+"&quantity="+quantity);

        document.getElementById('name').value="";
        document.getElementById('price').value="";
        document.getElementById('quantity').value="";
      }
		</script>

		</style>
    
  </head>

  <body>

  	<div id="user">
      <form action="./index.php" method="post" >
        <div id="logout">
          <input id="logoutbutton" type="submit" name="logout" value="Logout" /><br/>
        </div>
      </form>
      
  		<b><?php echo $_SESSION['name'] ?><br/></b>
      <a href="/index.php">Home</a>
  	</div>

    <div class="heading">
      <h1>Wolf of Wallstreet</h1>
    </div>

    <div class="form">

    	<div class="mytab2<?php if(!(isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab']=="users")){ echo("-active"); } ?>" onclick="window.location='?tab=users';">Register Users</div>
      <div class="mytab2<?php if(isset($_GET['tab']) && $_GET['tab']=="comps"){ echo("-active"); } ?>" onclick="window.location='?tab=comps';">Register Companies</div>


      <div class="tab-contents">
        
        <?php if(!(isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab']=="users")){ ?>
        <div id="users">  
          <h1>Registering new user:</h1>
          <h4 class="formerr" id="formerr"><br/></h4>
          <center>

            <div class="field-wrap">
              <label>
                Name<span class="req">*</span>
              </label>
              <input type="text" id="name" required autocomplete="off"/>
            </div>
            
            <div class="field-wrap">
              <label>
                Password<span class="req">*</span>
              </label>
              <input type="password" id="password" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <label>
                Retype Password<span class="req">*</span>
              </label>
              <input type="password" id="re-password" required autocomplete="off"/>
            </div>

            <button onclick="regUser()" class="button button-block"/>Register User</button>
            
          </center>
        </div>
        <?php } ?>



        <?php if(isset($_GET['tab']) && $_GET['tab']=="comps"){ ?>
        <div id="users">   
          <h1>Registering new company:</h1>
          <h4 class="formerr" id="formerr"><br/></h4>
          <center>

            <div class="field-wrap">
              <label>
                Company Name<span class="req">*</span>
              </label>
              <input type="text" id="name" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <label>
                Initial Offering Price<span class="req">*</span>
              </label>
              <input type="number" id="price" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
              <label>
                Initial Number Stocks to be Floated<span class="req">*</span>
              </label>
              <input type="number" id="quantity" required autocomplete="off"/>
            </div>

          <button onclick="regComp()" class="button button-block"/>Register Company</button>
            
          </center>
        </div>
        <?php } ?>
      
      </div>

        
    </div>
    <script src="js/first.js"></script>   
    <script src="js/index.js"></script>
  </body>
</html>

<?php }else{ ?>
Access Denied!
<?php } ?>