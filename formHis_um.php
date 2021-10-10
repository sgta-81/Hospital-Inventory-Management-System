<?php
	
	$server="localhost";
	$username="root";
	$password="pgi";
    $db = "dep_project";
	$conn = mysqli_connect($server,$username,$password,$db);
	if(!$conn){
		die("connection to this database failed due to".mysqli_connect_error());
	}
	
    session_start(); 
    $nameEmp = $_SESSION['eName'];

	//$checkTSuccess=true;
	//$checkTSucc=false;
	date_default_timezone_set("Asia/Kolkata");
	$currTime=date("Y-m-d")." - ".date("h:i:s");

    function updateFormStatus($fid,$status,$con){ //nurse staff view
        $sq="UPDATE `dep_project`.`formhistory` SET `Status` = '$status' WHERE `formID` = '$fid';";
        $res=$con->query($sq);
        if(!$res) echo $con->error;
    }
    function updateFormStatus2($fid,$status,$con){ // consultant view
        $sq="UPDATE `dep_project`.`formhistory` SET `StatusCon` = '$status' WHERE `formID` = '$fid';";
        $res=$con->query($sq);
        if(!$res) echo $con->error;
    }
    function updateFormStatus3($fid,$status,$con){ // UM view
        $sq="UPDATE `dep_project`.`formhistory` SET `StatusUM` = '$status' WHERE `formID` = '$fid';";
        $res=$con->query($sq);
        if(!$res) echo $con->error;
    }
    function tableExists($tname,$conn){
        $sq="SELECT * FROM `dep_project`.`$tname` LIMIT 1";
        $res=$conn->query($sq);
        if(!$res){
            return false;
        }
        else return true;
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
<form action="formHis_um.php" method="post">
<nav class="navbar navbar-dark bg-dark">
<span class="navbar-text">
    <?php echo $nameEmp;?> 
</span>
	<button class="btn btn-dark" style = 'background-color:#00BFFF' id="navb1" name="nav_b1" onclick="">Forms received from Hospital</button>
	<button class="btn btn-dark" style = 'background-color:#00BFFF' id="navb2" name="nav_b2" onclick="">Forms to be sent to Hospital</button>
    <button class="btn btn-dark" style = 'background-color:#00BFFF' id="navb3" name="nav_b3" onclick="">Completed Forms</button>
	<a href="lgsi.php" style="color:white">Logout</a>
</nav>
<script>
function setColor(btn, color) {
        var property = document.getElementById(btn);
        if (count == 0) {
            property.style.backgroundColor = "#FFFFFF"
            count = 1;        
        }
        else {
            property.style.backgroundColor = color;
            count = 0;
        }
    }
</script>
<?php
//$stockRecQuery=true;
$stockRecQuery=false;
if(isset($_SESSION['stockRecRows'])){
    for($xx=1;$xx<=$_SESSION['stockRecRows'];$xx++){
        $recvi="recv".$xx;
        if(isset($_POST[$recvi])){
            $stockRecQuery=true;
            break;
        }
    }
}

if(!isset($_POST['nav_b1']) && !isset($_POST['nav_b2']) && !isset($_POST['nav_b3']) && !$stockRecQuery) { 
    ?>
    <script>
    var count = 1;
    function setColor(btn, color) {
        var property = document.getElementById(btn);
        if (count == 0) {
            property.style.backgroundColor = "#FFFFFF"
            count = 1;        
        }
        else {
            property.style.backgroundColor = color;
            count = 0;
        }
    }
    setColor('navb1','rgb(195 15 118)');
    </script>
    <?php

    $sql1 = "SELECT * from formhistory as F where F.StatusUM='Ready for Dispatch' ";
    $result1 = $conn->query($sql1);

    $num_rows1 = $result1 ->num_rows;

    $arr1 = [];
    $arr2 = [];
    $arr3 = [];

    while($rowsv = $result1->fetch_assoc()){
            array_push($arr1,$rowsv['FormID']);
            array_push($arr2,$rowsv['DateRequired']);
            array_push($arr3,$rowsv['StatusUM']);
    }
    
    
    
?>
<div class="container">
	<div id="bh">
    <table class="table">
        <tr scope="row">
            <th>S.No.</th>
            <th>Form ID</th>
            <th>Date Required</th>
            <th>Status</th>            			
  		</tr>
    <?php
        for($i=0; $i<$num_rows1; $i++){?>
            <tr scope="row">
                <td><?php echo $i+1;?></td>
                <td><?php echo $arr1[$i];?></td>
                <td><?php echo $arr2[$i];?></td>
                <td><?php echo $arr3[$i];?></td>
                <td><a href="printUM1.php?formid=<?php echo $arr1[$i]; ?>" target="_blank" rel="noopener noreferrer">View items</a></td>
                <td><div class="text-right"></div><a class="btn btn-success text-center" type="submit" style = "color: white; background-color:DodgerBlue" 
                name="buttonDispatch" href="printUM1dispatch.php?formid=<?php echo $arr1[$i]; ?>">Dispatch to Supplier</a></td>
            </tr>
            <?php }







} ?>






<?php

if(isset($_POST['nav_b1'])) { 
    ?>
    <script>
    var count = 1;
    setColor('navb1','rgb(195 15 118)');
    </script>
    <?php
    $sql1 = "SELECT * from formhistory as F where F.StatusUM='Ready for Dispatch' ";
    $result1 = $conn->query($sql1);

    $num_rows1 = $result1 ->num_rows;

    $arr1 = [];
    $arr2 = [];
    $arr3 = [];

    while($rowsv = $result1->fetch_assoc()){
            array_push($arr1,$rowsv['FormID']);
            array_push($arr2,$rowsv['DateRequired']);
            array_push($arr3,$rowsv['StatusUM']);
    }

?>
<div class="container">
	<div id="bh">
    <table class="table">
        <tr scope="row">
            <th>S.No.</th>
            <th>Form ID</th>
            <th>Date Required</th>
            <th>Status</th>            			
  		</tr>
    <?php
        for($i=0; $i<$num_rows1; $i++){?>
            <tr scope="row">
                <td><?php echo $i+1;?></td>
                <td><?php echo $arr1[$i];?></td>
                <td><?php echo $arr2[$i];?></td>
                <td><?php echo $arr3[$i];?></td>
                <td><a href="printUM1.php?formid=<?php echo $arr1[$i]; ?>" target="_blank" rel="noopener noreferrer">View items</a></td>
                <td><div class="text-right"></div><a class="btn btn-success text-center" type="submit" style = "color: white; background-color:DodgerBlue" 
                name="buttonDispatch" href="printUM1dispatch.php?formid=<?php echo $arr1[$i]; ?>">Dispatch to Supplier</a></td>
            </tr>
            <?php } ?>
    </table>
    </div>
</div>
        <?php
}



if(isset($_POST['nav_b2']) || $stockRecQuery) {
    ?>
    <script>
    var count = 1;
    setColor('navb2','rgb(195 15 118)');
    </script>
    <?php
    
    $sql1 = "SELECT * from formhistory as F where F.StatusUM='Dispatched to Pharma' or F.StatusUM='Stock Rcvd' or F.StatusUM='Stock Verification Pending' or F.StatusUM='Issue reported in supply'";
    $result1 = $conn->query($sql1);

    $num_rows1 = $result1 ->num_rows;

    $_SESSION['stockRecRows']=$num_rows1;
    $arr1 = [];
    $arr2 = [];
    $arr3 = [];

    while($rowsv = $result1->fetch_assoc()){
        array_push($arr1,$rowsv['FormID']);
        array_push($arr2,$rowsv['DateRequired']);
        array_push($arr3,$rowsv['StatusUM']);
    }

    //if(isset(($_POST['updateStock']))){
        //echo "hello";
        for($i=1;$i<=$num_rows1;$i++){
            $recvi="recv".$i;
            if(isset($_POST[$recvi])){
                //echo "hello00";
                $fid=$arr1[$i-1];
                $arr3[$i-1]='Stock Rcvd';
                updateFormStatus3($fid,'Stock Rcvd',$conn);
                updateFormStatus($fid,'Stock Rcvd',$conn);
            }

        }

        

    //}
    ?>

    <div class="container">
        <!--form method="POST" action="formHis_um.php"-->
        <!-- <div style="margin-left: 60%;">
            <button class="btn btn-success" id="stockRecv" style = "color: white; background-color:DodgerBlue" name="updateStock">Update Stock status of selected forms</button>
        </div><br> -->
        <div id="bh">
        <table class="table">
            <tr scope="row">
                <th>S.No.</th>
                <th>Form ID</th>
                <th>Date Required</th>
                <th>Status</th> 
                <th>Stock status</th>           			
              </tr>
        <?php
            for($i=0; $i<$num_rows1; $i++){
                $j=$i+1;
                ?>
                <tr scope="row">
                    <td><?php echo $i+1;?></td>
                    <td><a href="printUM1.php?formid=<?php echo $arr1[$i]; ?>" target="_blank" rel="noopener noreferrer"><?php echo $arr1[$i];?></a></td>
                    <td><?php echo $arr2[$i];?></td>

                    <?php if ($arr3[$i]=='Stock Rcvd') { ?>
                        <td style="background-color: lightgreen;border: 1px solid black;">
                        <?php echo $arr3[$i];?></td>
                    <?php }
                    else if ($arr3[$i]=='Stock Verification Pending') { ?>
                        <td style="background-color: #4882c1;border: 1px solid black;">
                        <?php echo $arr3[$i];?></td>
                    <?php }
                    else if ($arr3[$i]=='Issue reported in supply') { ?>
                        <td style="background-color:LightSalmon ;border: 1px solid black;">
                        <?php echo $arr3[$i];?></td>
                    <?php }
                    else{ //Dispatched to Pharma?> 
                        <td style="background-color: #FFE4B5;border: 1px solid black;">
                        <?php echo $arr3[$i];?></td>
                    <?php }

                    ?>
                    <td><input type="checkbox" name="<?php echo "rec".$j; ?>" <?php if($arr3[$i]!='Dispatched to Pharma') echo "disabled"; ?> <?php if($arr3[$i]!='Dispatched to Pharma') echo "checked"; ?>></td>
                    <?php if($arr3[$i]=='Dispatched to Pharma'){ ?>
                    <td><button class="btn btn-success" id="stockRecv" style = "color: white; background-color: #4CAF50" name="<?php echo "recv".$j; ?>">Received</button></td>
                    <?php }
                    else { ?>
                    <td><button class="btn btn-success" id="stockRecv" style = "color: black; background-color: #A9A9A9;" disabled>Received</button></td>     
                    <?php }
                    ?>
                    <?php if($arr3[$i]=='Stock Rcvd'){ 
                        if(!tableExists($arr1[$i]."_UM",$conn)){?>
                            <td><a href="printUM2.php?formid=<?php echo $arr1[$i]; ?>">Supply items</a></td>
                        <?php } 
                        else{?>
                            <td><a href="editFormUM.php?formid=<?php echo $arr1[$i]; ?>">Change supplied items</a></td>
            
                            <td><div class="text-right"></div><a class="btn btn-success text-center"style = "color: white; background-color:DodgerBlue;width:80px;" 
                            href="printUM3.php?formid=<?php echo $arr1[$i]; ?>">Preview</a></td>
                    <?php } }
                    if($arr3[$i]=='Issue reported in supply'){?>
                        <td><a href="printUM_issue.php?formid=<?php echo $arr1[$i]; ?>" target="_blank" rel="noopener noreferrer">View issue</a></td>
                        <td><a href="editFormUM.php?formid=<?php echo $arr1[$i]; ?>">Change supplied items</a></td>
                        <td><div class="text-right"></div><a class="btn btn-success text-center"style = "color: white; background-color:DodgerBlue;width:80px;" 
                        href="printUM3.php?formid=<?php echo $arr1[$i]; ?>">Resend</a></td>
                    <?php } ?>
                </tr>
            <?php } ?> 
        </table>
        </div>
        <!--/form-->
    </div>
    <?php
}

if(isset($_POST['nav_b3'])) { 
    ?>
    <script>
    var count = 1;
    setColor('navb3','rgb(195 15 118)');
    </script>
    <?php

    $sql1 = "SELECT * from formhistory as F where F.StatusUM='Stock Rcvd and fwd' or F.StatusUM='Stock Verified by Hospital'";
    $result1 = $conn->query($sql1);

    $num_rows1 = $result1 ->num_rows;

    $arr1 = [];
    $arr2 = [];
    $arr3 = [];

    while($rowsv = $result1->fetch_assoc()){
            array_push($arr1,$rowsv['FormID']);
            array_push($arr2,$rowsv['DateRequired']);
            array_push($arr3,$rowsv['StatusUM']);
    }  
    
?>
    <div class="container">
        <div id="bh">
        <table class="table">
            <tr scope="row">
                <th>S.No.</th>
                <th>Form ID</th>
                <th>Date Required</th>
                <th>Status</th>            			
              </tr>
        <?php
            for($i=0; $i<$num_rows1; $i++){?>
                <tr scope="row">
                    <td><?php echo $i+1;?></td>
                    <td><?php echo $arr1[$i];?></td>
                    <td><?php echo $arr2[$i];?></td>
                    <td style="background-color: #ead4ab;border: 1px solid black;"><?php echo $arr3[$i];?></td>
                    <td><a href="recordfile_um.php?formid=<?php echo $arr1[$i]; ?>" target="_blank" rel="noopener noreferrer">View leftover items</a></td>
                </tr>
                <?php }

} 

$conn->close();
?>


</form>
<script src="dp1.js">
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>