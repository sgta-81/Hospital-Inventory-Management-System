<?php
	$formId=$_GET['formid'];
		
    //$sql;
	//$checkT=false;
	//$x=$_POST['baIdL'];
	//echo $x;
	//session_start(); 
    # $nameEmp = $_SESSION['eName'];
	//echo $myVar;
	//$checkTSuccess=true;
	//$checkTSucc=false;
	//date_default_timezone_set("Asia/Kolkata");
	//$currTime=date("Y-m-d")." - ".date("h:i:s");
    //echo $currTime;
	//$sqlbh="SELECT * FROM `dbmsproject`.`bookinghistory` AS B WHERE B.agent_id='$agentid' ";
	
	
	//$sqlv = "SELECT * FROM `tablea`";
	//$resv = $con->query($sqlv);
	//$num_t = $resv ->num_rows;
	$server="localhost";
	$username="root";
	$password="pgi";
    $db = "dep_project";
	$conn = mysqli_connect($server,$username,$password,$db);
	if(!$conn){
	    die("connection to this database failed due to".mysqli_connect_error());
	}
    function getPatientId($formId,$conn){
        $pid=-1;
        $sqlQ="SELECT * FROM `dep_project`.`formhistory` WHERE FormID='$formId';";
        $res=$conn->query($sqlQ);
        $numR=$res->num_rows;
        if($numR==1){
            while($rowData=$res->fetch_assoc()){
                $pid=$rowData['PatientID'];
                break;
            }
        }
        return $pid;
    }
    $crno=getPatientId($formId,$conn);
    $sqlQ="SELECT * FROM `dep_project`.`patient_details` WHERE cr_no='$crno' and formid='$formId'";
    $res=$conn->query($sqlQ);
    $numR=$res->num_rows;
    if($numR==1){
        while($rowData=$res->fetch_assoc()){
            $pName=$rowData['name'];
            $pAge=$rowData['age'];
            $pSex=$rowData['gender'];
            $pWt=$rowData['weight'];
            $pBSA=$rowData['bsa'];
            $pCon=$rowData['consultant_name'];
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
<div class="container">
    <div class="patientDetails">
    <center><h4>UTILIZATION CERTIFICATE</h4></center>
    <center><p>Department of Cardiovascular and Thoracic Surgery</p></center>
    <center><p>PGIMER, Chandigarh</p></center>
    <!-- <center><h4>Congenital Heart Disease</h4></center> -->
    <p style= 'font-size:16px'>&nbsp; &nbsp; &nbsp;&emsp; Form ID: <strong><?php echo $formId;?></strong><br>
    &nbsp; &nbsp; &nbsp;&emsp; CR Number: <strong><?php echo $crno;?></strong><br><br>
    &nbsp; &nbsp; &nbsp;&emsp; Name: <?php echo $pName;?>&nbsp; &nbsp; &nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; Age: <?php echo $pAge;?><br>
    &nbsp; &nbsp; &nbsp;&emsp; Sex: <?php echo $pSex;?>&nbsp; &nbsp; &nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp;Weight (in kg): <?php echo $pWt;?><br>
    &nbsp; &nbsp; &nbsp;&emsp; Consultant Name: <?php echo $pCon;?>&nbsp; &nbsp; &nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; BSA: <?php echo $pBSA;?></p>
    <br>
    </div>
	<div id="bh">
    <table class="table">
        <tr scope="row">
            <th>S.No.</th>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Specification(s)</th>
            <th>Brand/Company</th>
            <th>Quantity required</th>
            <th>Remarks</th>			
  		</tr>
          <?php
            $sql1 = "SELECT * from seca_items";
            $result1 = $conn->query($sql1);
            $sql2 = "SELECT * from secb_items";
            $result2 = $conn->query($sql2);
            $sql3 = "SELECT * from secc_items";
            $result3 = $conn->query($sql3);
            $sql4 = "SELECT * from secd_items";
            $result4 = $conn->query($sql4);
            $sql5 = "SELECT * from sece_items";
            $result5 = $conn->query($sql5);    
            
            $num_rows1 = $result1 ->num_rows;
            $num_rows2 = $result2 ->num_rows;
            $num_rows3 = $result3 ->num_rows;
            $num_rows4 = $result4 ->num_rows;
            $num_rows5 = $result5 ->num_rows;
            
            $arra1=[];
	        $arra2=[];
	        $arra3=[];
	        $arra4=[];
	        $arra5=[];
	        
            $arrb1=[];
	        $arrb2=[];
	        $arrb3=[];
	        $arrb4=[];
	        $arrb5=[];
	        
            $arrc1=[];
	        $arrc2=[];
	        $arrc3=[];
	        $arrc4=[];
	        $arrc5=[];
	        
            $arrd1=[];
	        $arrd2=[];
	        $arrd3=[];
	        $arrd4=[];
	        $arrd5=[];
	        
            $arre1=[];
	        $arre2=[];
	        $arre3=[];
	        $arre4=[];
	        $arre5=[];

            $arr1=[];
	        $arr2=[];
	        $arr3=[];
	        $arr4=[];
	        $arr5=[];
            $arr6=[];
	        
            while($rowsv = $result1 -> fetch_assoc()){
		        array_push($arra1, $rowsv['srno']);
                array_push($arra2, $rowsv['item_id']);
		        array_push($arra3, $rowsv['name']);
		        array_push($arra4, $rowsv['spec']);
		        array_push($arra5, $rowsv['brand']);
		        
                //array_push($arr5, $rowsv['']);
		        //array_push($arr6, $rowsv['createdby']);
	        }

            while($rowsv = $result2 -> fetch_assoc()){
		        array_push($arrb1, $rowsv['srno']);
                array_push($arrb2, $rowsv['item_id']);
		        array_push($arrb3, $rowsv['name']);
		        array_push($arrb4, $rowsv['spec']);
		        array_push($arrb5, $rowsv['brand']);
		        
                //array_push($arr5, $rowsv['']);
		        //array_push($arr6, $rowsv['createdby']);
	        }
            while($rowsv = $result3 -> fetch_assoc()){
		        array_push($arrc1, $rowsv['srno']);
                array_push($arrc2, $rowsv['item_id']);
		        array_push($arrc3, $rowsv['name']);
		        array_push($arrc4, $rowsv['spec']);
		        array_push($arrc5, $rowsv['brand']);
		        
                //array_push($arr5, $rowsv['']);
		        //array_push($arr6, $rowsv['createdby']);
	        }
            while($rowsv = $result4 -> fetch_assoc()){
		        array_push($arrd1, $rowsv['srno']);
                array_push($arrd2, $rowsv['item_id']);
		        array_push($arrd3, $rowsv['name']);
		        array_push($arrd4, $rowsv['spec']);
		        array_push($arrd5, $rowsv['brand']);
		        
                //array_push($arr5, $rowsv['']);
		        //array_push($arr6, $rowsv['createdby']);
	        }
            while($rowsv = $result5 -> fetch_assoc()){
		        array_push($arre1, $rowsv['srno']);
                array_push($arre2, $rowsv['item_id']);
		        array_push($arre3, $rowsv['name']);
		        array_push($arre4, $rowsv['spec']);
		        array_push($arre5, $rowsv['brand']);
		        
                //array_push($arr5, $rowsv['']);
		        //array_push($arr6, $rowsv['createdby']);
	        }

            $sqln = "SELECT * from `$formId` ORDER BY section";    // add new_form name here
            $resultn = $conn->query($sqln);
            $num_rows = $resultn ->num_rows;
            while($rowsv = $resultn -> fetch_assoc()){
		        array_push($arr1, $rowsv['section']);
                array_push($arr2, $rowsv['srno']);
		        array_push($arr3, $rowsv['selected']);
		        array_push($arr4, $rowsv['qty']);
		        array_push($arr5, $rowsv['brand']);
		        array_push($arr6, $rowsv['remark']);
	        }
            
            $co1 = count(array_keys($arr1, 1));
            $co2 = count(array_keys($arr1, 2));
            $co3 = count(array_keys($arr1, 3));
            $co4 = count(array_keys($arr1, 4));
            $co5 = count(array_keys($arr1, 5));

            if($num_rows <= 0){
                echo "<h2 id='Error'>This form has no items added. Please add an item to this form</h2>";
                }
            else{
                $kjh = 1;

                # section A
                for($i=0; $i < $co1; $i++){
                    if ($arr3[$i]==1){    
                ?>

                <tr scope ="row">
 
                <td><?php 
                echo $kjh; $kjh += 1;
                ?></td>

                <td><?php 
                $v1 = $arr2[$i];
                echo "1-".$arra2[$v1-1];?></td> 
                <td><?php echo $arra3[$v1-1];?></td>

                <td><?php 
                $v = $arr4[$i];
                $l = strlen($v);
                if ($l==1){
                    echo $arra4[$v1-1];
                }
                else{
                    $desc = $arra4[$v1-1];
                    $desc_arr = explode (", ", $desc);

                for ($j = 0; $j < $l; $j++) {
                    $c = $v[$j];
                    if ($c!='0'){
                       echo ": ".$desc_arr[$j];
                       echo "</br>"; 
                    }
                }
            }
                ?></td>

                <td><?php 
                $vv = $arr5[$i];
                $ll = strlen($vv);
                if ($ll==1){
                    echo $arra5[$v1-1];
                }
                else{
                    $desc = $arra5[$v1-1];
                    $desc_arr = explode (", ", $desc);

                for ($j = 0; $j < $ll; $j++) {
                    $c = $vv[$j];
                    if ($c!='0'){
                       echo "- ".$desc_arr[$j];
                       echo "</br>";
                       
                    }
                }
            }   
                ?></td>

                <td><?php 
                if ($l==1){ echo ": ".$v;}
                else{
                for ($j = 0; $j < $l; $j++) {
                    $c = $v[$j];
                    if ($c!='0'){
                        echo ": ".$c;
                        echo "</br>";} 
                }}
                ?></td>

                <td><?php echo $arr6[$i];?></td>
  		    </tr>
        <?php } }
        
            # section B
            for($i = $co1; $i < $co1 + $co2; $i++){
                if ($arr3[$i]==1){    
            ?>
    
            <tr scope ="row">
    
            <td><?php echo $kjh; $kjh += 1;?></td>
            <td><?php 
            $v1 = $arr2[$i];
            echo "2-".$arrb2[$v1-1];?></td> 
            <td><?php echo $arrb3[$v1-1];?></td>
    
            <td><?php 
            $v = $arr4[$i];
            $l = strlen($v);
            if ($l==1){
                echo $arrb4[$v1-1];
            }
            else{
                $desc = $arrb4[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $l; $j++) {
                $c = $v[$j];
                if ($c!='0'){
                   echo ": ".$desc_arr[$j];
                   echo "</br>"; 
                }
            }
        }
            ?></td>
    
            <td><?php 
            $vv = $arr5[$i];
            $ll = strlen($vv);
            if ($ll==1){
                echo $arrb5[$v1-1];
            }
            else{
                $desc = $arrb5[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $ll; $j++) {
                $c = $vv[$j];
                if ($c!='0'){
                   echo "- ".$desc_arr[$j];
                   echo "</br>";
                   
                }
            }
        }   
            ?></td>
    
            <td><?php 
            if ($l==1){ echo ": ".$v;}
            else{
            for ($j = 0; $j < $l; $j++) {
                $c = $v[$j];
                if ($c!='0'){
                    echo ": ".$c;
                    echo "</br>";} 
            }}
            ?></td>
    
            <td><?php echo $arr6[$i];?></td>
          </tr>
            <?php } }
    


            # section C
            for($i = $co1 + $co2; $i < $co1 + $co2 + $co3; $i++){
                if ($arr3[$i]==1){    
            ?>
    
            <tr scope ="row">
    
            <td><?php echo $kjh; $kjh += 1;?></td>
            <td><?php 
            $v1 = $arr2[$i];
            echo "3-".$arrc2[$v1-1];?></td> 
            <td><?php echo $arrc3[$v1-1];?></td>
    
            <td><?php 
            $v = $arr4[$i];
            $l = strlen($v);
            if ($l==1){
                echo $arrc4[$v1-1];
            }
            else{
                $desc = $arrc4[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $l; $j++) {
                $c = $v[$j];
                if ($c!='0'){
                   echo ": ".$desc_arr[$j];
                   echo "</br>"; 
                }
            }
        }
            ?></td>
    
            <td><?php 
            $vv = $arr5[$i];
            $ll = strlen($vv);
            if ($ll==1){
                echo $arrc5[$v1-1];
            }
            else{
                $desc = $arrc5[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $ll; $j++) {
                $c = $vv[$j];
                if ($c!='0'){
                   echo "- ".$desc_arr[$j];
                   echo "</br>";
                   
                }
            }
        }   
            ?></td>
    
            <td><?php 
            if ($l==1){ echo ": ".$v;}
            else{
            for ($j = 0; $j < $l; $j++) {
                $c = $v[$j];
                if ($c!='0'){
                    echo ": ".$c;
                    echo "</br>";} 
            }}
            ?></td>
    
            <td><?php echo $arr6[$i];?></td>
          </tr>
            <?php } }

            # section D
            for($i = $co1 + $co2 + $co3; $i < $co1 + $co2 + $co3 + $co4; $i++){
                if ($arr3[$i]==1){    
            ?>
    
            <tr scope ="row">
    
            <td><?php echo $kjh; $kjh += 1;?></td>
            <td><?php 
            $v1 = $arr2[$i];
            echo "4-".$arrd2[$v1-1];?></td> 
            <td><?php echo $arrd3[$v1-1];?></td>
    
            <td><?php echo $arrd4[$v1-1]; ?></td>
    
            <td><?php 
            $vv = $arr5[$i];
            $ll = strlen($vv);
            if ($ll==1){
                echo $arrd5[$v1-1];
            }
            else{
                $desc = $arrd5[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $ll; $j++) {
                $c = $vv[$j];
                if ($c!='0'){
                   echo "- ".$desc_arr[$j];
                   echo "</br>";
                   
                }
            }
        }   
            ?></td>
    
            <td><?php 
            $v = $arr4[$i];
            echo ": ".$v; ?></td>
    
            <td><?php echo $arr6[$i];?></td>
          </tr>
            <?php } }

            # section E
            for($i = $co1 + $co2 + $co3 + $co4; $i < $co1 + $co2 + $co3 + $co4 + $co5; $i++){
                if ($arr3[$i]==1){    
            ?>
    
            <tr scope ="row">
    
            <td><?php echo $kjh; $kjh += 1;?></td>
            <td><?php 
            $v1 = $arr2[$i];
            echo "5-".$arre2[$v1-1];?></td> 
            <td><?php echo $arre3[$v1-1];?></td>
    
            <td><?php 
            $v = $arr4[$i];
            $l = strlen($v);
            if ($l==1){
                echo $arre4[$v1-1];
            }
            else{
                $desc = $arre4[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $l; $j++) {
                $c = $v[$j];
                if ($c!='0'){
                   echo ": ".$desc_arr[$j];
                   echo "</br>"; 
                }
            }
        }
            ?></td>
    
            <td><?php 
            $vv = $arr5[$i];
            $ll = strlen($vv);
            if ($ll==1){
                echo $arre5[$v1-1];
            }
            else{
                $desc = $arre5[$v1-1];
                $desc_arr = explode (", ", $desc);
    
            for ($j = 0; $j < $ll; $j++) {
                $c = $vv[$j];
                if ($c!='0'){
                   echo "- ".$desc_arr[$j];
                   echo "</br>";
                   
                }
            }
        }   
            ?></td>
    
            <td><?php 
            if ($l==1){ echo ": ".$v;}
            else{
            for ($j = 0; $j < $l; $j++) {
                $c = $v[$j];
                if ($c!='0'){
                    echo ": ".$c;
                    echo "</br>";} 
            }}
            ?></td>
    
            <td><?php echo $arr6[$i];?></td>
          </tr>
            <?php } }




        ?>
  		<?php }
          $conn->close(); ?>
        </table>
	</div>
    <div id="sign">
    <br>
    <table align="left" id="t0" class="table table-borderless">
    <tr>
    <td>Signed By:</td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    <tr>
    <td>Resident In Charge:</td>
    <td>Perfusionist:</td>
    <td>OT Technician:</td>
    <td>OT Nursing Officer:</td>
    </tr>
    </table>
    </div>
</div>
<script src="dp1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>