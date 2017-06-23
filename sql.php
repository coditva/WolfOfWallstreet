<?php

require_once("./basic.php");
if(isset($_SESSION['id']) && $_SESSION['id'] == 1){ ?>
	
<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo $_SESSION['name']; ?>!</title>
    <script>
    function runQ(){
    	var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          document.getElementById("result").innerHTML = xhttp.responseText;
        }
      };
      xhttp.open("POST", "actions.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      query=document.getElementById('query').value;
      xhttp.send("query="+query);
    }
    </script>
  </head>
  <body>
  	<center>
	  	<br/><br/>Run Query Here:<br/><br/>
	  	<div id="result"><br/></div><br/>
	   	<textarea name="query" id="query" placeholder="Enter Query to Run" style="height:100px;width:400px;"></textarea><br/><br/>
	   	<button style="padding:10px;width:400px;" onclick="runQ()">Run</button>
   	</center>
  </body>
</html>

<?php }else{
	echo "Access Denied.";
}

?>