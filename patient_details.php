<?php

$server="localhost";
$username="root";
$password="pgi";
$con=mysqli_connect($server,$username,$password);
if(!$con){
	die("connection to this database failed due to".mysqli_connect_error());
}
session_start();
$nameEmp = $_SESSION['eName'];
$searchFailure=false;
$numR=0;
$fidarr=[];
$pnamearr=[];
$wtarr=[];
$bsaarr=[];
$sexarr=[];
$agearr=[];
$datearr=[];
$consultantarr=[];
if(!isset($_SESSION['currentRows']))$_SESSION['currentRows']=0; 
if(isset($_POST['buttonPD'])){
    $crn=$_POST['crn'];
    $_SESSION['crno']=$crn;
    $sql="SELECT * FROM `dep_project`.`patient_details` as B WHERE B.cr_no='$crn';";
    $res=$con->query($sql);
    $numR=$res->num_rows;
    if($numR==0){
        $searchFailure=true;
    }
    else{
        $_SESSION['currentRows']=$numR;
        while($row=$res->fetch_assoc()){
            array_push($fidarr,$row['formid']);
            $formid=$row['formid'];
            array_push($pnamearr,$row['name']);
            array_push($wtarr,$row['weight']);
            array_push($bsaarr,$row['bsa']);
            array_push($sexarr,$row['gender']);
            array_push($agearr,$row['age']);
            array_push($consultantarr,$row['consultant_name']);
            $sql1="SELECT * FROM `dep_project`.`formhistory` as B WHERE B.FormID='$formid';";
            $res1=$con->query($sql1);
            //$numd=$res1->num_rows;
            while($row1=$res1->fetch_assoc()){
                $date=$row1['DateRequired'];
            }
            array_push($datearr,$date);
        }
    }

}
for($i=1;$i<=$_SESSION['currentRows'];$i++){
$changeDateI="changeDate".$i;
if(isset($_POST[$changeDateI])){
    $crn=$_SESSION['crno'];
    $sql="SELECT * FROM `dep_project`.`patient_details` as B WHERE B.cr_no='$crn';";
    $res=$con->query($sql);
    $numR=$res->num_rows;
    if($numR==0){
        $searchFailure=true;
    }
    else{
        while($row=$res->fetch_assoc()){
            array_push($fidarr,$row['formid']);
            $formid=$row['formid'];
            array_push($pnamearr,$row['name']);
            array_push($wtarr,$row['weight']);
            array_push($bsaarr,$row['bsa']);
            array_push($sexarr,$row['gender']);
            array_push($agearr,$row['age']);
            array_push($consultantarr,$row['consultant_name']);
            $sql1="SELECT * FROM `dep_project`.`formhistory` as B WHERE B.FormID='$formid';";
            $res1=$con->query($sql1);
            //$numd=$res1->num_rows;
            while($row1=$res1->fetch_assoc()){
                $date=$row1['DateRequired'];
            }
            array_push($datearr,$date);
        }
            $dateI="date".$i;
            $newDate=$_POST[$dateI];
            $idx = $i-1;
            $sql2 = "UPDATE `dep_project`.`formhistory` SET DateRequired='$newDate' WHERE FormID='$fidarr[$idx]';";
            $res2=$con->query($sql2);
        
    }
}
} 

?>
<html>
<head>
<title>Inventory Management System</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
<span class="navbar-text">
    <?php echo $nameEmp;?> 
</span>
	<a class="btn btn-dark" style = 'background-color:#00BFFF' href="formHis.php">Go Back to Existing Forms</a>
	<a href="lgsi.php" style="color:white">Logout</a>
</nav>

<!-- <nav class="navbar navbar-dark bg-dark">
	<a data-hover="" class="navbar-brand" href="formHis.php">Go back to Form History</a>
    <a href="lgsi.php" style="color:white">Logout</a>
</nav> -->
<div class="container">
	
	<div id="pd1">
		<h2>Enter CR Number of the patient</h2><br>
	</div>
    <form action="patient_details.php" method="post">
	<div id="pd2">
		
  		<div class="form-group">
    			<label for="crn">CR Number</label>
    			<input type="number" name="crn" class="form-control">
  		</div>
		<center><button type="submit" class="btn btn-dark" style= 'background-color:dodgerblue' name="buttonPD">Search</button></center>
		<!--/form-->
	</div>
    <p></p>
    <p></p>
    <p></p>
    <div>
        <table class="table">
            <tr>
                <th>Sr No</th>
                <th>Form ID</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Weight</th>
                <th>BSA</th>
                <th>Date of operation</th>
                <th>Consultant</th>
            </tr>
            <?php for($i=1;$i<=$numR;$i++){?>
  		    <tr scope="row">
  		  	<td><?php echo $i;?></td>
  			<td><?php echo $fidarr[$i-1];?></td>
			<td><?php echo $pnamearr[$i-1];?></td>
            <td><?php echo $agearr[$i-1];?></td>
  	  		<td><?php echo $sexarr[$i-1];?></td>
			<td><?php echo $wtarr[$i-1];?></td>
			<td><?php echo $bsaarr[$i-1];?></td>
            <td><input type="date" name="<?php echo "date".$i; ?>" value="<?php echo $datearr[$i-1];?>"></td>
            <td><?php echo $consultantarr[$i-1];?></td>
            <td><button  onclick="return confirm('Are you sure?');" name="<?php echo "changeDate".$i;?>" class="btn btn-success text-center" style = "background-color:DodgerBlue;color:white;">Change Date</button></td>
            </tr>
            <?php } ?>
        </table>
    </div>	
    </form>
</div>
<!-- <div class="container">
                <div class="row">
                    <div class="col">
					<div class="text-right"></div><a href = 'patient_details.php' onclick="return confirm('Are you sure?');" class="btn btn-success text-center" style = "background-color:black;color:white;">Change Date</a></div>  
                </div>
</div> -->


<?php
    if($searchFailure) echo "<h2 id='error'>The input CR Number does not exist.</h2>";
?>

<script src="dp1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>