<?php
	$server="localhost";
	$username="root";
	$password="pgi";
	$con=mysqli_connect($server,$username,$password);
	if(!$con){
		die("connection to this database failed due to".mysqli_connect_error());
	}
	//echo "Success connecting to the database<br>";

	$sql;
	$signIn=false;
	$logIn=false;
	//$checkS=true;
	$logInF=false;
	$signInF=false;
	$signInSuccess=false;
	//$logInSucc=false;
	////
    if(isset($_POST['eIdL']) && isset($_POST['ePL']) && isset($_POST['buttonL'])) { 
        $logIn=true;
    } 
    if(isset($_POST['eIdS']) && isset($_POST['ePS']) && isset($_POST['buttonS']) && isset($_POST['eNS']) && isset($_POST['eType']) ) { 
        $signIn=true;
    } 
    ////	
	if($logIn){
		$baidl=$_POST['eIdL'];
		$bapl=$_POST['ePL'];
		//echo "Done <br>";
		$bapl_protected = password_hash($bapl, PASSWORD_DEFAULT);
		$sql="SELECT `eid`, `password`,`ename`,`etype` FROM `dep_project`.`employees` as B WHERE B.eid='$baidl';";
		// and B.password='$bapl_protected'
		$res=$con->query($sql);
		if($res->num_rows==1){
			//echo "Successfully Inserted<br>";

			//verify password
			$pwRet;
			$nameEmp;
			while($rowsEl=$res->fetch_assoc()){
				$pwRet=$rowsEl['password'];
				$nameEmp=$rowsEl['ename'];
				$etype=$rowsEl['etype'];
			}
			//echo $pwRet;	
			if(password_verify($bapl,$pwRet)){			
				//$logInSucc=true;
				session_start(); 
    			$_SESSION['eName'] = $nameEmp;
				$_SESSION['etype'] = $etype;
				if($etype=="Unit Manager") header('Refresh:0.5; URL= formHis_um.php');
				else header('Refresh:0.5; URL= formHis.php');
			}
			else $logInF=true;			 
		}
		//else echo "Error<br> $con->error";
		else $logInF=true;
	}
	if($signIn){	
		$baids=$_POST['eIdS'];
		$baps=$_POST['ePS'];
		$bans=$_POST['eNS'];
		$etype=$_POST['eType'];
		$checkS=true;
	
		if($checkS){
		//('eid', 'ename', 'etype', 'password')
			$sql="SELECT * FROM `dep_project`.`employees` as B WHERE B.eid='$baids';";
			$res=$con->query($sql);
			if($res->num_rows>=1) $signInF=true;
			else{
				$baps_protected = password_hash($baps, PASSWORD_DEFAULT);
				$sql="INSERT INTO `dep_project`.`employees`(`eid`, `ename`, `etype`, `password`) VALUES ('$baids','$bans','$etype','$baps_protected');";
				if($con->query($sql)){
					$signInSuccess=true;
				}
				//else echo "Error<br> $con->error";
				else $signInF=true;
			}
		}
	}
	$con->close();

?>

<html>
<head>
<title>Inventory Management System</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<!--img class="bg" src="train3.jpg" alt="trainImage"-->
<nav class="navbar navbar-dark bg-dark">
  <!--a class="navbar-brand" href="#"-->
    <!--img src="logo.jpg" width="30" height="30" alt=""-->
  <!--/a-->
	<a data-hover="" class="navbar-brand" target="_self" rel="" href="https://pgimer.edu.in/PGIMER_PORTAL/PGIMERPORTAL/home.jsp">PGIMER, Chandigarh</a>
	<!--a href="https://www.google.com" id="wt">PGIMER, Chandigarh</a>-->
</nav>
<div class="container">
	
	<div id="c1">
		<h1>Welcome to the Inventory Management System</h1><br>
	</div>
	<div id="ic1">
		<center><button class="btn btn-dark" onclick="showL()">Log In</button>
		<!--button class="btn btn-dark" onclick="showS()">New User? Sign In</button></center-->
	</div>
	<div id="ic2">
		<form action="lgsi.php" method="post">
  		<div class="form-group">
  			<label for="eIdL">Employee ID</label>
    			<input type="text" class="form-control" id="eIdL" name="eIdL" required>
  		</div>
  		<div class="form-group">
    			<label for="ePL">Password</label>
    			<input type="password" name="ePL" class="form-control" id="ePL" required>
  		</div>
		<center><button type="submit" class="btn btn-dark" name="buttonL">Submit</button></center>
		</form>
	</div>
	<div id="ic3">
		<form action="lgsi.php" method="post">
  		<div class="form-group">
  			<label for="eIdS">Employee ID</label>
    			<input type="text" class="form-control" name="eIdS" id="eIdS" required>
  		</div>
  		<div class="form-group">
    			<label for="ePS">Password</label>
    			<input type="password" class="form-control" id="ePS" name="ePS" required>
  		</div>
		<div class="form-group">
  			<label for="eNS">Employee Name</label>
    			<input type="text" class="form-control" id="eNS" name="eNS" required>
  		</div>
		<div class="form-group">
			<label for="eType">Employee Type</label>
			<select name="eType" class="form-control" id="c24" required>
  				<option value="Doctor">Doctor</option>
  				<option value="Nurse">Nurse</option>
				<option value="Perfusionist">Perfusionist</option>
				<option value="Technician">Technician</option>
				<option value="Unit Manager">Unit Manager</option>
			</select>
		</div>
  		<center><button type="submit" name="buttonS" class="btn btn-dark">Submit</button></center>
	
		</form>
	</div>
	<?php
			if($logInF){
				 echo "<h2 id='error'>Either username or password is incorrect.</h2>";
				header("Refresh:4");
			}
			if($signInF){
				 echo "<h2 id='error'>The username already exists.</h2>";
				header("Refresh:4");
			}
			if($signInSuccess){
				 echo "<h2 id='succ'>Successfully registered as new employee.</h2>";
				header("Refresh:4");
			}
			/*if($logInSucc){
				 echo "<h2 id='succ'>Logged In Successfully.</h2>";
				header("Refresh:4");
			}*/
		?>
</div>
<script src="dp1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
