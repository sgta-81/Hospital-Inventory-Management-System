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
	$checkT=false;
	//$x=$_POST['baIdL'];
	//echo $x;
	session_start(); 
    $nameEmp = $_SESSION['eName'];
	//echo $myVar;
	$checkTSuccess=true;
	$checkTSucc=false;
	date_default_timezone_set("Asia/Kolkata");
	$currTime=date("Y-m-d")." - ".date("h:i:s");
    //echo $currTime;
	//$sqlbh="SELECT * FROM `dbmsproject`.`bookinghistory` AS B WHERE B.agent_id='$agentid' ";
	
	
	
	// history
	$sqlv="SELECT * FROM `dep_project`.`formhistory` as F where F.Status='In Progress' or F.Status='Approved by Consultant' or F.Status='Sent for Approval';";
	$resv=$con->query($sqlv);
	$num_t=$resv->num_rows;
	$fidarr=[];
	$pidarr=[];
	$pnamearr=[];
	$drarr=[];
	$dcarr=[];
	$cbarr=[];
	$statusarr=[];
	$i=0;
	while($rowsv=$resv->fetch_assoc()){
		array_push($fidarr,$rowsv['FormID']);
		array_push($pidarr,$rowsv['PatientID']);
		$sqlpname="SELECT * FROM `dep_project`.`patient_details` as N WHERE N.cr_no='$pidarr[$i]';";
		$respname=$con->query($sqlpname);
		$rowpname = $respname->fetch_assoc();
		$temp=$rowpname['name'];
		array_push($pnamearr,$temp);
		array_push($drarr,$rowsv['DateRequired']);
		array_push($dcarr,$rowsv['DateCreated']);
		if($_SESSION['etype']=="Doctor"){
			array_push($statusarr,$rowsv['StatusCon']);
		}
		else{
			array_push($statusarr,$rowsv['Status']);
		}
		array_push($cbarr,$rowsv['createdby']);
		$i++;
	}
    $SortFormId=false;
    $SortDateRequired=false;
    $SortDateCreated=false;
	$SortStatus=false;
    $SortPatientId=false;
	if(isset($_POST['buttons1'])){
        $SortFormId=true;
        $SortDateRequired=false;
        $SortDateCreated=false;
        $SortStatus=false;
        $SortPatientId=false;
		$sqlh1="SELECT * FROM `dep_project`.`formhistory` as F where F.Status='In Progress' or F.Status='Approved by Consultant' or F.Status='Sent for Approval' ORDER BY FormID";
		$resh1=$con->query($sqlh1);
		$ii=0;
		while($rowsh1=$resh1->fetch_assoc()){
			$fidarr[$ii]= $rowsh1['FormID'];
			$pidarr[$ii]= $rowsh1['PatientID'];
			$sqlpname="SELECT * FROM `dep_project`.`patient_details` as N WHERE N.cr_no='$pidarr[$ii]';";
			$respname=$con->query($sqlpname);
			$rowpname = $respname->fetch_assoc();
			$temp=$rowpname['name'];
			$pnamearr[$ii]= $temp;
			$drarr[$ii]= $rowsh1['DateRequired'];
			$dcarr[$ii]= $rowsh1['DateCreated'];
			if($_SESSION['etype']=="Doctor"){
				$statusarr[$ii]= $rowsh1['StatusCon'];
			}
			else{
				$statusarr[$ii]= $rowsh1['Status'];
			}
			$cbarr[$ii]= $rowsh1['createdby'];
			$ii++ ;
		
		}	
	}

    if(isset($_POST['buttons2'])){
        $SortFormId=false;
        $SortDateRequired=true;
        $SortDateCreated=false;
        $SortStatus=false;
        $SortPatientId=false;
		$sqlh2="SELECT * FROM `dep_project`.`formhistory` as F where F.Status='In Progress' or F.Status='Approved by Consultant' or F.Status='Sent for Approval' or F.Status ORDER BY DateRequired";
		$resh2=$con->query($sqlh2);
		$iii=0;
		while($rowsh2=$resh2->fetch_assoc()){
			$fidarr[$iii]= $rowsh2['FormID'];
			$pidarr[$iii]= $rowsh2['PatientID'];
			$sqlpname="SELECT * FROM `dep_project`.`patient_details` as N WHERE N.cr_no='$pidarr[$iii]';";
	$respname=$con->query($sqlpname);
	$rowpname = $respname->fetch_assoc();
	$temp=$rowpname['name'];
			$pnamearr[$iii]= $temp;
			$drarr[$iii]= $rowsh2['DateRequired'];
			$dcarr[$iii]= $rowsh2['DateCreated'];
			if($_SESSION['etype']=="Doctor"){
				$statusarr[$iii]= $rowsh2['StatusCon'];
			}
			else{
				$statusarr[$iii]= $rowsh2['Status'];
			}
			$cbarr[$iii]= $rowsh2['createdby'];
			$iii++ ;
		
		}	
	}
    if(isset($_POST['buttons3'])){
        $SortFormId=false;
        $SortDateRequired=false;
        $SortDateCreated=true;
        $SortStatus=false;
        $SortPatientId=false;
		$sqlh3="SELECT * FROM `dep_project`.`formhistory` as F where F.Status='In Progress' or F.Status='Approved by Consultant' or F.Status='Sent for Approval' ORDER BY DateCreated";
		$resh3=$con->query($sqlh3);
		$iiii=0;
		while($rowsh3=$resh3->fetch_assoc()){
			$fidarr[$iiii]= $rowsh3['FormID'];
			$pidarr[$iiii]= $rowsh3['PatientID'];
			$sqlpname="SELECT * FROM `dep_project`.`patient_details` as N WHERE N.cr_no='$pidarr[$iiii]';";
			$respname=$con->query($sqlpname);
			$rowpname = $respname->fetch_assoc();
			$temp=$rowpname['name'];
			$pnamearr[$iiii]= $temp;
			$drarr[$iiii]= $rowsh3['DateRequired'];
			$dcarr[$iiii]= $rowsh3['DateCreated'];
			//$statusarr[$iiii]= $rowsh3['Status'];
			if($_SESSION['etype']=="Doctor"){
				$statusarr[$iiii]= $rowsh3['StatusCon'];
			}
			else{
				$statusarr[$iiii]= $rowsh3['Status'];
			}
			$cbarr[$iiii]= $rowsh3['createdby'];
			$iiii++ ;
		
		}	
	}
    if(isset($_POST['buttons4'])){
        $SortFormId=false;
        $SortDateRequired=false;
        $SortDateCreated=false;
        $SortStatus=true;
        $SortPatientId=false;
		$sqlh4="SELECT * FROM `dep_project`.`formhistory` as F where F.Status='In Progress' or F.Status='Approved by Consultant' or F.Status='Sent for Approval' ORDER BY  Status";
		$resh4=$con->query($sqlh4);
		$iiiii=0;
		while($rowsh4=$resh4->fetch_assoc()){
			$fidarr[$iiiii]= $rowsh4['FormID'];
			$pidarr[$iiiii]= $rowsh4['PatientID'];
			$sqlpname="SELECT * FROM `dep_project`.`patient_details` as N WHERE N.cr_no='$pidarr[$iiiii]';";
			$respname=$con->query($sqlpname);
			$rowpname = $respname->fetch_assoc();
			$temp=$rowpname['name'];
			$pnamearr[$iiiii]= $temp;
			$drarr[$iiiii]= $rowsh4['DateRequired'];
			$dcarr[$iiiii]= $rowsh4['DateCreated'];
			//$statusarr[$iiiii]= $rowsh4['Status'];
			if($_SESSION['etype']=="Doctor"){
				$statusarr[$iiiii]= $rowsh4['StatusCon'];
			}
			else{
				$statusarr[$iiiii]= $rowsh4['Status'];
			}
			$cbarr[$iiiii]= $rowsh4['createdby'];
			$iiiii++ ;
		
		}	
	}
    if(isset($_POST['buttons5'])){
        $SortFormId=false;
        $SortDateRequired=false;
        $SortDateCreated=false;
        $SortStatus=false;
        $SortPatientId=true;
		$sqlh5="SELECT * FROM `dep_project`.`formhistory` as F where F.Status='In Progress' or F.Status='Approved by Consultant' or F.Status='Sent for Approval' ORDER BY  PatientID";
		$resh5=$con->query($sqlh5);
		$iiiiii=0;
		while($rowsh5=$resh5->fetch_assoc()){
			$fidarr[$iiiiii]= $rowsh5['FormID'];
			$pidarr[$iiiiii]= $rowsh5['PatientID'];
			$sqlpname="SELECT * FROM `dep_project`.`patient_details` as N WHERE N.cr_no='$pidarr[$iiiiii]';";
			$respname=$con->query($sqlpname);
			$rowpname = $respname->fetch_assoc();
			$temp=$rowpname['name'];
			$pnamearr[$iiiiii]= $temp;
			$drarr[$iiiiii]= $rowsh5['DateRequired'];
			$dcarr[$iiiiii]= $rowsh5['DateCreated'];
			//$statusarr[$iiiiii]= $rowsh5['Status'];
			if($_SESSION['etype']=="Doctor"){
				$statusarr[$iiiiii]= $rowsh5['StatusCon'];
			}
			else{
				$statusarr[$iiiiii]= $rowsh5['Status'];
			}
			$cbarr[$iiiiii]= $rowsh5['createdby'];
			$iiiiii++ ;
		
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
<nav class="navbar navbar-dark bg-dark">
<span class="navbar-text">
    <?php echo $nameEmp;?> 
</span>
	<a class="btn btn-dark" style = 'background-color:#00BFFF' href="index.php">Create Form</a>
	<a class="btn btn-dark" style = 'background-color:rgb(195 15 118)' href="formHis.php" id="fh">View Incomplete Forms</a>
	<a class="btn btn-dark" style = 'background-color:#00BFFF' href="formHis1.php">View Dispatched Forms</a>
	<a class="btn btn-dark" style = 'background-color:#00BFFF' href="formHis2.php">Completed Forms</a>
	<a href="lgsi.php" style="color:white">Logout</a>
	<!-- <span class="navbar-text">
    		<?php echo $nameEmp;?> 
	</span> -->
</nav>

<div class="container">
	<?php if($_SESSION['etype']=="Doctor"){ ?>
                <div class="row">
                    <div class="col">
					<div class="text-right"></div><a href = 'patient_details.php' class="btn btn-success text-center" style = "background-color:black;color:white;">Edit patient details</a></div>  
                </div>
	<?php } ?>
</div>

<div class="container">
	<div id="bh">
		<table class="table">
  		<tr scope="row">
    			<th>S.No.</th>
                <th><form action="formHis.php" method="post">
  	<input type="submit" name="buttons1" class="btn btn-success text-center" style = "background-color:DodgerBlue"  value="Form Id">
	</form></th>
    <th><form action="formHis.php" method="post">
  	<input type="submit" name="buttons5" class="btn btn-success text-center" style = "background-color:DodgerBlue"  value="CR Number">
	</form></th>

	<th>Patient Name</th>
			
            <th><form action="formHis.php" method="post">
  	<input type="submit" name="buttons2" class="btn btn-success text-center" style = "background-color:DodgerBlue"  value="Date of Operation">
	</form></th>
            <th><form action="formHis.php" method="post">
  	<input type="submit" name="buttons3" class="btn btn-success text-center" style = "background-color:DodgerBlue" value="Date Created">
	</form></th>
			
    
			
			<th>Created By</th>
            <th><form action="formHis.php" method="post">
  	<input type="submit" name="buttons4" class="btn btn-success text-center" style = "background-color:DodgerBlue"  value="Status">
	</form></th>
			
    			<th></th>
			
  		</tr>
		<?php   
	
			if($num_t==0){
				 echo "<h2 id='error'>No forms found.</h2>";
				header("Refresh:4");
			}
			else{
			for($i=$num_t-1;$i>=0;$i--){?>
  		<tr scope="row">
  		  	<td><?php echo $num_t-$i;?></td>
  			<td><?php echo $fidarr[$i];?></td>
			<td><?php echo $pidarr[$i];?></td>
			<td><?php echo $pnamearr[$i];?></td>
  	  		<td><?php echo $drarr[$i];?></td>
			<td><?php echo $dcarr[$i];?></td>
			<td><?php echo $cbarr[$i];?></td>

  	  		<?php if ($statusarr[$i] == 'In Progress'){ ?>
				<td style="background-color: Khaki;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  	  	
			if ($statusarr[$i] == 'Approval Pending'){ ?>
				<td style="background-color: LightSalmon;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  	
			if ($statusarr[$i] == 'Approved'){ ?>
				<td style="background-color: lightgreen;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  	
			if ($statusarr[$i] == 'Sent for Approval'){ ?>
				<td style="background-color: LightSalmon;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  
			if ($statusarr[$i] == 'Stock Rcvd'){ ?>
				<td style="background-color: #e3c58f;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  
			if ($statusarr[$i] == 'Stock Rcvd from Unit Manager'){ ?>
				<td style="background-color: #e3ff8f;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  
			if ($statusarr[$i] == 'Stock Verified and fwd'){ ?>
				<td style="background-color: #e3ffff;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  
			if ($statusarr[$i] == 'Post Operation'){ ?>
				<td style="background-color: #fff000;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  
			if ($statusarr[$i] == 'Completed'){ ?>
				<td style="background-color: #aaff8d;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }
			if ($statusarr[$i] == 'Ready for Dispatch'){ ?>
				<td style="background-color: #FFF0F5;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }
			if ($statusarr[$i] == 'Dispatched to Pharma'){ ?>
				<td style="background-color: #aaff8d;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php }  	
			if ($statusarr[$i] == 'Approved by Consultant'){ ?>
				<td style="background-color: #F0FFFF;border: 1px solid black;">
				<?php echo $statusarr[$i];?></td>
			<?php } ?>  	

			<td><a href="printFormNurse.php?formid=<?php echo $fidarr[$i]; ?>" target="_blank" rel="noopener noreferrer">View</a></td>    
			<td><a href="editForm.php?formid=<?php echo $fidarr[$i]; ?>">Edit</a></td>
  		</tr>
  		<?php }	} ?>
        </table>
	</div>
</div>
<script src="dp1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>