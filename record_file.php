<?php

$server="localhost";
$username="root";
$password="pgi";
$db = "dep_project";
$conn = mysqli_connect($server,$username,$password,$db);
if(!$conn){
    die("connection to this database failed due to".mysqli_connect_error());
}

if(!isset($_POST['buttonSub1'])){
    $formid=$_GET['formid'];
    $_SESSION['formid']=$formid;
}
$formid=$_SESSION['formid'];
$formidum = $formid.'_UM';

$arr_qty_consumed = [];
$arr_qty_supplied = [];

$ondex = 0;

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
$crno=getPatientId($formid,$conn);
$sqlQ="SELECT * FROM `dep_project`.`patient_details` WHERE cr_no='$crno' and formid='$formid'";
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
<form action="record_file.php" method="post"> 
<p></p>                   
<div class="container">
    <div class="patientDetails">
    <center><h4>UTILIZATION CERTIFICATE</h4></center>
    <center><p>Department of Cardiovascular and Thoracic Surgery</p></center>
    <center><p>PGIMER, Chandigarh</p></center>
    <!-- <center><h4>Congenital Heart Disease</h4></center> -->
    <p style= 'font-size:16px'>&nbsp; &nbsp; &nbsp;&emsp; Form ID: <strong><?php echo $formid;?></strong><br>
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
            <th>Specification</th>
            <th>Brand(s)/Company(s)</th>
            <th>Quantity Received</th>
            <th>Quantity Consumed</th>
            <th>Status</th>
            			
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

            $sqln = "SELECT * from `dep_project`.`$formid` ORDER BY section";    // add new_form name here
            $resultn = $conn->query($sqln);
            $num_rows = $resultn ->num_rows;
            while($rowsv = $resultn -> fetch_assoc()){
		        array_push($arr1, $rowsv['section']);
                array_push($arr2, $rowsv['srno']);
		        array_push($arr3, $rowsv['selected']);
		        array_push($arr4, $rowsv['qty']);
		        array_push($arr5, $rowsv['brand']);
		        //array_push($arr6, $rowsv['remark']);
	        }

            $sqlnv = "SELECT * from `dep_project`.`$formidum`";    // add form_id of existing form her
            $resultnv = $conn->query($sqlnv);
            $num_rows = $resultnv ->num_rows;
            while($rowsv = $resultnv -> fetch_assoc()){
		        array_push($arr_qty_supplied, $rowsv['qty_recv']);
                array_push($arr_qty_consumed, $rowsv['qty_c']);
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
                $arr_specificatn = [];
	            $arre_qty_req = [];

                # section A

                for($i=0; $i < $co1; $i++){         // for reading spec, qty separately
                    if ($arr3[$i]==1){  # item is selected 
                        $v1 = $arr2[$i];
                        $v = $arr4[$i];
                        $l = strlen($v);
                        
                        if ($l==1){ 
                            array_push($arr_specificatn, $arra4[$v1-1]);
                            array_push($arre_qty_req, $v);
                        }
                        else{
                            $desc = $arra4[$v1-1];
                            $desc_arr = explode (", ", $desc);      // length = $l

                            for ($j = 0; $j < $l; $j++) {
                                $c = $v[$j];
                                if ($c != '0'){
                                array_push($arr_specificatn, $desc_arr[$j]);
                                array_push($arre_qty_req, $v[$j]);
                                }
                            }
                        } 
                    } }
                    //echo print_r($arr_specificatn);
                    $index = 0;
                    $remain=0;

                for($i=0; $i < $co1; $i++){     // for printing
                        if ($arr3[$i]==1){  # item is selected 
                            $v1 = $arr2[$i];
                            $v = $arr4[$i];
                            $l = strlen($v);
                            
                            $item_id = "1-".$arra2[$v1-1];
                            $item_name = $arra3[$v1-1];

                            $brand = "";
                            $vv = $arr5[$i];
                            $ll = strlen($vv);
                            if ($ll==1){
                                $brand = $arra5[$v1-1];
                            }
                            else{
                                $desc1 = $arra5[$v1-1];
                                $desc_arr1 = explode (", ", $desc1);

                            for ($jj = 0; $jj < $ll; $jj++) {
                                $cc = $vv[$jj];
                                if ($cc!='0'){
                                     $brand = $brand.$desc_arr1[$jj].", ";
                                    }
                                }
                            }   


                            for ($j = 0; $j < $l; $j++) {
                                if ($l==1 OR $v[$j] != '0'){
                ?>

                            <tr scope ="row">
 
                            <td><?php echo $kjh; ?></td>
                            <td><?php echo $item_id;?></td> 
                            <td><?php echo $item_name;?></td>

                            <td><?php echo $arr_specificatn[$index]; ?></td>
                            <td><?php echo $brand;
                            $index += 1;
                            $kjh += 1; ?></td>  

                            
                            <td><?php echo $arr_qty_supplied[$ondex];?></td>
                            <td><?php echo $arr_qty_consumed[$ondex];
                            $ondex += 1;?></td>
                            
                            <?php
                                if ($arr_qty_supplied[$ondex-1] == $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen; border: 1px solid black;">
                                    <?php echo "None left";
                                }
                                else if ($arr_qty_supplied[$ondex-1] > $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php $remain = $arr_qty_supplied[$ondex-1] - $arr_qty_consumed[$ondex-1]; 
                                    echo $remain." Units of this are left"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php $remain = $arr_qty_consumed[$ondex-1] - $arr_qty_supplied[$ondex-1];  
                                    echo $remain." more Units consumed";
                                 }
                            ?></td>
                            
                            </tr>
                            <?php } } } }
                            unset($arr_specificatn);
                            unset($arre_qty_req);
                            $arr_specificatn = [];
	                        $arre_qty_req = [];
                
                # section B

                for($i=$co1; $i < $co1+$co2; $i++){         // for reading spec, qty separately
                    if ($arr3[$i]==1){  # item is selected 
                        $v1 = $arr2[$i];
                        $v = $arr4[$i];
                        $l = strlen($v);
                        
                        if ($l==1){ 
                            array_push($arr_specificatn, $arrb4[$v1-1]);
                            array_push($arre_qty_req, $v);
                        }
                        else{
                            $desc = $arrb4[$v1-1];
                            $desc_arr = explode (", ", $desc);      // length = $l

                            for ($j = 0; $j < $l; $j++) {
                                $c = $v[$j];
                                if ($c != '0'){
                                array_push($arr_specificatn, $desc_arr[$j]);
                                array_push($arre_qty_req, $v[$j]);
                                }
                            }
                        } 
                    } }
                    
                    $index = 0;

                for($i=$co1; $i < $co1+$co2; $i++){     // for printing
                        if ($arr3[$i]==1){  # item is selected 
                            $v1 = $arr2[$i];
                            $v = $arr4[$i];
                            $l = strlen($v);
                            
                            $item_id = "2-".$arrb2[$v1-1];
                            $item_name = $arrb3[$v1-1];

                            $brand = "";
                            $vv = $arr5[$i];
                            $ll = strlen($vv);
                            if ($ll==1){
                                $brand = $arrb5[$v1-1];
                            }
                            else{
                                $desc1 = $arrb5[$v1-1];
                                $desc_arr1 = explode (", ", $desc1);

                            for ($jj = 0; $jj < $ll; $jj++) {
                                $cc = $vv[$jj];
                                if ($cc!='0'){
                                     $brand = $brand.$desc_arr1[$jj].", ";
                                    }
                                }
                            }   


                            for ($j = 0; $j < $l; $j++) {
                                if ($l==1 OR $v[$j] != '0'){
                ?>

<tr scope ="row">
 
 <td><?php echo $kjh; ?></td>
 <td><?php echo $item_id;?></td> 
 <td><?php echo $item_name;?></td>

 <td><?php echo $arr_specificatn[$index]; ?></td>
 <td><?php echo $brand;
 $index += 1;
 $kjh += 1; ?></td>  

 
 <td><?php echo $arr_qty_supplied[$ondex];?></td>
 <td><?php echo $arr_qty_consumed[$ondex];
 $ondex += 1;?></td>
 
 <?php
                                if ($arr_qty_supplied[$ondex-1] == $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen; border: 1px solid black;">
                                    <?php echo "None left";
                                }
                                else if ($arr_qty_supplied[$ondex-1] > $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php $remain = $arr_qty_supplied[$ondex-1] - $arr_qty_consumed[$ondex-1]; 
                                    echo $remain." Units of this are left"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php $remain = $arr_qty_consumed[$ondex-1] - $arr_qty_supplied[$ondex-1];  
                                    echo $remain." more Units consumed";
                                 }
                            ?></td>

 </tr>

                            <?php } } } }
                            unset($arr_specificatn);
                            unset($arre_qty_req);
                            $arr_specificatn = [];
	                        $arre_qty_req = [];
                
                
                # section C

                for($i=$co1+$co2; $i < $co1+$co2+$co3; $i++){         // for reading spec, qty separately
                    if ($arr3[$i]==1){  # item is selected 
                        $v1 = $arr2[$i];
                        $v = $arr4[$i];
                        $l = strlen($v);
                        
                        if ($l==1){ 
                            array_push($arr_specificatn, $arrc4[$v1-1]);
                            array_push($arre_qty_req, $v);
                        }
                        else{
                            $desc = $arrc4[$v1-1];
                            $desc_arr = explode (", ", $desc);      // length = $l

                            for ($j = 0; $j < $l; $j++) {
                                $c = $v[$j];
                                if ($c != '0'){
                                array_push($arr_specificatn, $desc_arr[$j]);
                                array_push($arre_qty_req, $v[$j]);
                                }
                            }
                        } 
                    } }
                    
                    $index = 0;

                for($i=$co1+$co2; $i < $co1+$co2+$co3; $i++){     // for printing
                        if ($arr3[$i]==1){  # item is selected 
                            $v1 = $arr2[$i];
                            $v = $arr4[$i];
                            $l = strlen($v);
                            
                            $item_id = "3-".$arrc2[$v1-1];
                            $item_name = $arrc3[$v1-1];

                            $brand = "";
                            $vv = $arr5[$i];
                            $ll = strlen($vv);
                            if ($ll==1){
                                $brand = $arrc5[$v1-1];
                            }
                            else{
                                $desc1 = $arrc5[$v1-1];
                                $desc_arr1 = explode (", ", $desc1);

                            for ($jj = 0; $jj < $ll; $jj++) {
                                $cc = $vv[$jj];
                                if ($cc!='0'){
                                     $brand = $brand.$desc_arr1[$jj].", ";
                                    }
                                }
                            }   


                            for ($j = 0; $j < $l; $j++) {
                                if ($l==1 OR $v[$j] != '0'){
                ?>

<tr scope ="row">
 
 <td><?php echo $kjh; ?></td>
 <td><?php echo $item_id;?></td> 
 <td><?php echo $item_name;?></td>

 <td><?php echo $arr_specificatn[$index]; ?></td>
 <td><?php echo $brand;
 $index += 1;
 $kjh += 1; ?></td>  

 
 <td><?php echo $arr_qty_supplied[$ondex];?></td>
 <td><?php echo $arr_qty_consumed[$ondex];
 $ondex += 1;?></td>
 
 <?php
                                if ($arr_qty_supplied[$ondex-1] == $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen; border: 1px solid black;">
                                    <?php echo "None left";
                                }
                                else if ($arr_qty_supplied[$ondex-1] > $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php $remain = $arr_qty_supplied[$ondex-1] - $arr_qty_consumed[$ondex-1]; 
                                    echo $remain." Units of this are left"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php $remain = $arr_qty_consumed[$ondex-1] - $arr_qty_supplied[$ondex-1];  
                                    echo $remain." more Units consumed";
                                 }
                            ?></td>

 </tr>

                            <?php } } } }
                            unset($arr_specificatn);
                            unset($arre_qty_req);
                            $arr_specificatn = [];
	                        $arre_qty_req = [];
                
                # section D

                for($i=$co1+$co2+$co3; $i < $co1+$co2+$co3+$co4; $i++){         // for reading spec, qty separately
                    if ($arr3[$i]==1){  # item is selected 
                        $v1 = $arr2[$i];
                        $v = $arr4[$i];
                        $l = strlen($v);
                        
                        if ($l==1){ 
                            array_push($arr_specificatn, $arrd4[$v1-1]);
                            array_push($arre_qty_req, $v);
                        }
                        else{
                            $desc = $arrd4[$v1-1];
                            $desc_arr = explode (", ", $desc);      // length = $l

                            for ($j = 0; $j < $l; $j++) {
                                $c = $v[$j];
                                if ($c != '0'){
                                array_push($arr_specificatn, $desc_arr[$j]);
                                array_push($arre_qty_req, $v[$j]);
                                }
                            }
                        } 
                    } }
                    
                    $index = 0;

                for($i=$co1+$co2+$co3; $i < $co1+$co2+$co3+$co4; $i++){     // for printing
                        if ($arr3[$i]==1){  # item is selected 
                            $v1 = $arr2[$i];
                            $v = $arr4[$i];
                            $l = strlen($v);
                            
                            $item_id = "4-".$arrd2[$v1-1];
                            $item_name = $arrd3[$v1-1];

                            $brand = "";
                            $vv = $arr5[$i];
                            $ll = strlen($vv);
                            if ($ll==1){
                                $brand = $arrd5[$v1-1];
                            }
                            else{
                                $desc1 = $arrd5[$v1-1];
                                $desc_arr1 = explode (", ", $desc1);

                            for ($jj = 0; $jj < $ll; $jj++) {
                                $cc = $vv[$jj];
                                if ($cc!='0'){
                                     $brand = $brand.$desc_arr1[$jj].", ";
                                    }
                                }
                            }   



                            for ($j = 0; $j < $l; $j++) {
                                if ($l==1 OR $v[$j] != '0'){
                ?>

<tr scope ="row">
 
 <td><?php echo $kjh; ?></td>
 <td><?php echo $item_id;?></td> 
 <td><?php echo $item_name;?></td>

 <td><?php echo $arr_specificatn[$index]; ?></td>
 <td><?php echo $brand;
 $index += 1;
 $kjh += 1; ?></td>  

 
 <td><?php echo $arr_qty_supplied[$ondex];?></td>
 <td><?php echo $arr_qty_consumed[$ondex];
 $ondex += 1;?></td>
 
 <?php
                                if ($arr_qty_supplied[$ondex-1] == $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen; border: 1px solid black;">
                                    <?php echo "None left";
                                }
                                else if ($arr_qty_supplied[$ondex-1] > $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php $remain = $arr_qty_supplied[$ondex-1] - $arr_qty_consumed[$ondex-1]; 
                                    echo $remain." Units of this are left"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php $remain = $arr_qty_consumed[$ondex-1] - $arr_qty_supplied[$ondex-1];  
                                    echo $remain." more Units consumed";
                                 }
                            ?></td>

 </tr>

                            <?php } } } }
                            unset($arr_specificatn);
                            unset($arre_qty_req);
                            $arr_specificatn = [];
	                        $arre_qty_req = [];
                

                # section E

                for($i=$co1+$co2+$co3+$co4; $i < $co1+$co2+$co3+$co4+$co5; $i++){         // for reading spec, qty separately
                    if ($arr3[$i]==1){  # item is selected 
                        $v1 = $arr2[$i];
                        $v = $arr4[$i];
                        $l = strlen($v);
                        
                        if ($l==1){ 
                            array_push($arr_specificatn, $arre4[$v1-1]);
                            array_push($arre_qty_req, $v);
                        }
                        else{
                            $desc = $arre4[$v1-1];
                            $desc_arr = explode (", ", $desc);      // length = $l

                            for ($j = 0; $j < $l; $j++) {
                                $c = $v[$j];
                                if ($c != '0'){
                                array_push($arr_specificatn, $desc_arr[$j]);
                                array_push($arre_qty_req, $v[$j]);
                                }
                            }
                        } 
                    } }
                    
                    $index = 0;

                for($i=$co1+$co2+$co3+$co4; $i < $co1+$co2+$co3+$co4+$co5; $i++){     // for printing
                        if ($arr3[$i]==1){  # item is selected 
                            $v1 = $arr2[$i];
                            $v = $arr4[$i];
                            $l = strlen($v);
                            
                            $item_id = "5-".$arre2[$v1-1];
                            $item_name = $arre3[$v1-1];

                            $brand = "";
                            $vv = $arr5[$i];
                            $ll = strlen($vv);
                            if ($ll==1){
                                $brand = $arre5[$v1-1];
                            }
                            else{
                                $desc1 = $arre5[$v1-1];
                                $desc_arr1 = explode (", ", $desc1);

                            for ($jj = 0; $jj < $ll; $jj++) {
                                $cc = $vv[$jj];
                                if ($cc!='0'){
                                     $brand = $brand.$desc_arr1[$jj].", ";
                                    }
                                }
                            }   


                            for ($j = 0; $j < $l; $j++) {
                                if ($l==1 OR $v[$j] != '0'){
                ?>

<tr scope ="row">
 
 <td><?php echo $kjh; ?></td>
 <td><?php echo $item_id;?></td> 
 <td><?php echo $item_name;?></td>

 <td><?php echo $arr_specificatn[$index]; ?></td>
 <td><?php echo $brand;
 $index += 1;
 $kjh += 1; ?></td>  

 
 <td><?php echo $arr_qty_supplied[$ondex];?></td>
 <td><?php echo $arr_qty_consumed[$ondex];
 $ondex += 1;?></td>
 
 <?php
                                if ($arr_qty_supplied[$ondex-1] == $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen; border: 1px solid black;">
                                    <?php echo "None left";
                                }
                                else if ($arr_qty_supplied[$ondex-1] > $arr_qty_consumed[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php $remain = $arr_qty_supplied[$ondex-1] - $arr_qty_consumed[$ondex-1]; 
                                    echo $remain." Units of this are left"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php $remain = $arr_qty_consumed[$ondex-1] - $arr_qty_supplied[$ondex-1];  
                                    echo $remain." more Units consumed";
                                 }
                            ?></td>

 </tr>

                            <?php } } } }
?>
<?php }
?>
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
</form>
<script src="dp1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

<?php


$conn->close();
?>