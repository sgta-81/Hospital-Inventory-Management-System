<?php
    $server="localhost";
	$username="root";
	$password="pgi";
	$con=mysqli_connect($server,$username,$password);
	if(!$con){
		die("connection to this database failed due to".mysqli_connect_error());
	}
    session_start();
    //$empName="Gurpreet";
    $empName=$_SESSION['eName'];
    $isValid=false;
    $failure=false;
    //echo "here1";
    function getFormID(){ // create new form ID
        $id=time();
        //$id='new_form';
        //$id=$_SESSION['formid'];
        return $id;
    }
    if(isset($_POST['buttonNext']) && isset($_POST['i1']) && isset($_POST['i2']) && isset($_POST['i3']) && isset($_POST['i4']) && isset($_POST['i5']) && isset($_POST['i6']) && isset($_POST['i7']) && isset($_POST['i8'])){
        //if(isset($_POST['i1']) && isset($_POST['i2']) && isset($_POST['i3']) && isset($_POST['i4']) && isset($_POST['i5']) && isset($_POST['i6']) && isset($_POST['i7'])){
        //    $isValid=true;
            //echo "here";
        //}
        
        $isValid=true;
        if($isValid){

            $_SESSION['dateR']=$_POST['i7'];
            $pname=$_POST['i1'];
            $crno=$_POST['i2'];
            $cname=$_POST['i3'];
            $age=$_POST['i4'];
            $wt=$_POST['i5'];
            $bsa=$_POST['i6'];
            $gen=$_POST['i8'];
            $formid=getFormID();
            $sql="INSERT INTO `dep_project`.`patient_details`(`name`, `cr_no`, `consultant_name`, `age`, `gender`, `weight`, `bsa`,`formid`) 
            VALUES ('$pname','$crno','$cname','$age','$gen','$wt','$bsa','$formid');";
            $res=$con->query($sql);
            if($res){
                $_SESSION['patientID']=$crno;
                $_SESSION['formid']=$formid;
                header('Refresh:0.5; URL= form.php');
            }
            else{
                echo $con->error;
                $failure=true;
            }
        }   
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-md bg-dark">
        <div class="container-fluid"><a class="navbar-brand" href="#">Patient Details</a>
            <nav class="navbar navbar-light navbar-expand-md">
                <div class="container-fluid"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-2"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navcol-2">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"><a class="nav-link active" href="formHis.php" style = 'background-color:#00BFFF'>View Existing Forms</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-light navbar-expand-md">
                <div class="container-fluid"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navcol-1">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"></li>
                            <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><?php echo $empName;?>&nbsp;</a>
                                <div class="dropdown-menu"><a class="dropdown-item" href="lgsi.php">Logout</a></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
    </nav>
    <!--h2 class="text-left" style="font-size: 16;">&nbsp; &nbsp;Create New Form</h2-->
    <br><center><p style='font-size:20px'>&nbsp; &nbsp; &nbsp; Fill in the patient details first and then click Next.</p></center>
    <?php
            if($failure){
				 echo "<h2 id='error'>Failed to create new form. Please try again.</h2>";
				header("Refresh:4");
			}
        ?>
    <div class="container">
        <form action="index.php" method="post">
            <div class="form-group"><label>Patient Name</label><input class="form-control" type="text" name="i1" required></div>
            <div class="form-group"><label>CR Number</label><input class="form-control" type="text" name="i2" inputmode="numeric" maxlength="12" minlength="12" required></div>
            <div class="form-group"><label>Date Required</label><input class="form-control" type="date" name="i7" required></div>
            <div class="form-group"><label>Consultant</label><input class="form-control" type="text" name="i3" required></div>
            <br>
            <div class="form-group"><label>Age</label>
                <input class="form-control" type="number" name="i4" id="modifiedInput" style ='width:200px' required>
                <span> </span>
                <span>      
                    <input type="radio" name="i8" class="radioInput" value="M"> Male
                    <input type="radio" name="i8" class="radioInput" value="F"> Female
                    <input type="radio" name="i8" class="radioInput" value="O"> Other
                </span>
            </div><br>
            <div class="form-group">
                <label>Weight (in kg)<input class="form-control" id="modifiedInput" type="text" name="i5" style ='width:300px'>
                <label>BSA</label><input class="form-control" id="modifiedInput" type="text" name="i6" style ='width:300px'>
            </div><br>
            <div class="form-group">
            <center><button type="submit" class="btn btn-dark" id="modInp" name="buttonNext">Next</button></center>
            </div>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>