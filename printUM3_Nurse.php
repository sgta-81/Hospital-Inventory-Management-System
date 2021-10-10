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

if(!isset($_POST['verify']) && !isset($_POST['report'])){
$formid=$_GET['formid'];
$_SESSION['formid']=$formid;
}
$formid=$_SESSION['formid'];
$nameEmp = $_SESSION['eName'];
$formidum=$formid."_UM";

ob_start();

$arr_remarks = [];
$arr_qty_supplied = [];
$ondex = 0;

function getRemark($remI){
    if(isset($_POST[$remI])){
        return $_POST[$remI];
    }
    else return " ";
}

function getQtySupplied($qtyI){
    if(isset($_POST[$qtyI])){
        return $_POST[$qtyI];
    }
    else return 0;
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
<form action="printUM3_Nurse.php" method="post">  
<nav class="navbar navbar-dark bg-dark">
<span class="navbar-text">
    <?php echo $nameEmp; ?> 
</span>
    <a class="btn btn-dark" href="formHis1.php" style="color:white; background-color:#00BFFF">Go to Form History</a>
    <a href="lgsi.php" style="color:white">Logout</a>
</nav>                      

<div class="container">
            <div class="row">
                <div class="col">
                    <button onclick="location.href = '';" class="btn btn-success text-center" style = "background-color: #ff1a1a" type="submit" name="report">Report issue to UM</button>
                </div>
                <div class="col">
                    <button onclick="location.href = '';" class="btn btn-success text-center" style = "background-color:DodgerBlue" type="submit" name="verify">Mark as Verified</button>
                </div>
            </div>
</div>
<div class="container">
	<div id="bh">
    <table class="table">
        <tr scope="row">
            <th>S.No.</th>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Specification</th>
            <th>Brand(s)/Company(s)</th>
            <th>Quantity required</th>
            <th>Quantity supplied by UM</th>
            <th>Remarks by UM</th>
            <th>Status</th>
            <th>Quantity Received</th>
            <th>Remarks by Nurse</th>
            			
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
		        array_push($arr_qty_supplied, $rowsv['qty']);
                array_push($arr_remarks, $rowsv['remark']);
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
                $bni = 0;
                $arr_qty_recv = [];
                $arr_rmrk_recv = [];

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
                            <td><?php echo $brand;?></td>

                            <td><?php echo $arre_qty_req[$index]; 
                            $index += 1;
                            $kjh += 1; ?></td>

                            <td><?php echo $arr_qty_supplied[$ondex];?></td>
                            <td><?php echo $arr_remarks[$ondex]; $ondex+=1;?></td>
                            
                            <?php
                                if ($arre_qty_req[$index-1] == $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen; border: 1px solid black;">
                                    <?php echo "Sufficient";
                                }
                                else if ($arre_qty_req[$index-1] > $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php echo "Deficit"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php  echo "Excess";
                                 }
                            ?></td>

                            <td> 
                            <div class="col-1" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input style="width:50px" type="number" step="1" min="0" max="1000" value="<?php echo $arr_qty_supplied[$ondex-1];?>" name = "<?php echo "q_r".$bni;?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_qty_recv, getQtySupplied('q_r'.$bni)); ?>></div>
                            </td>
                            <td>
                            <div class="col-2" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input type="text" style="width:200px" value = "" name = "<?php echo "r_r".$bni; ?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_rmrk_recv, getRemark('r_r'.$bni)); $bni += 1; ?>></div>
                            </div> 
                            </td>

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
                            <td><?php echo $brand;?></td>

                            <td><?php echo $arre_qty_req[$index]; 
                            $index += 1;
                            $kjh += 1; ?></td>

                            <td><?php echo $arr_qty_supplied[$ondex];?></td>
                            <td><?php echo $arr_remarks[$ondex]; $ondex+=1;?></td>
                            
                            <?php
                                if ($arre_qty_req[$index-1] == $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen;border: 1px solid black;">
                                    <?php echo "Sufficient";
                                }
                                else if ($arre_qty_req[$index-1] > $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php echo "Deficit"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php  echo "Excess";
                                 }
                            ?></td>
                            
                            <td> 
                            <div class="col-1" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input style="width:50px" type="number" step="1" min="0" max="1000" value="<?php echo $arr_qty_supplied[$ondex-1];?>" name = "<?php echo "q_r".$bni;?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_qty_recv, getQtySupplied('q_r'.$bni)); ?>></div>
                            </td>
                            <td>
                            <div class="col-2" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input type="text" style="width:200px" value = "" name = "<?php echo "r_r".$bni; ?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_rmrk_recv, getRemark('r_r'.$bni)); $bni += 1; ?>></div>
                            </div> 
                            </td>
                            
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
                            <td><?php echo $brand;?></td>

                            <td><?php echo $arre_qty_req[$index]; 
                            $index += 1;
                            $kjh += 1; ?></td>

                            <td><?php echo $arr_qty_supplied[$ondex];?></td>
                            <td><?php echo $arr_remarks[$ondex]; $ondex+=1;?></td>
                            
                            <?php
                                if ($arre_qty_req[$index-1] == $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen;border: 1px solid black;">
                                    <?php echo "Sufficient";
                                }
                                else if ($arre_qty_req[$index-1] > $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php echo "Deficit"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php  echo "Excess";
                                 }
                            ?></td>
                            
                            <td> 
                            <div class="col-1" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input style="width:50px" type="number" step="1" min="0" max="1000" value="<?php echo $arr_qty_supplied[$ondex-1];?>" name = "<?php echo "q_r".$bni;?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_qty_recv, getQtySupplied('q_r'.$bni)); ?>></div>
                            </td>
                            <td>
                            <div class="col-2" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input type="text" style="width:200px" value = "" name = "<?php echo "r_r".$bni; ?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_rmrk_recv, getRemark('r_r'.$bni)); $bni += 1; ?>></div>
                            </div> 
                            </td>
                            
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
                            <td><?php echo $brand;?></td>

                            <td><?php echo $arre_qty_req[$index]; 
                            $index += 1;
                            $kjh += 1; ?></td>

                            <td><?php echo $arr_qty_supplied[$ondex];?></td>
                            <td><?php echo $arr_remarks[$ondex]; $ondex+=1;?></td>
                            
                            <?php
                                if ($arre_qty_req[$index-1] == $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen;border: 1px solid black;">
                                    <?php echo "Sufficient";
                                }
                                else if ($arre_qty_req[$index-1] > $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php echo "Deficit"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php  echo "Excess";
                                 }
                            ?></td>
                            
                            <td> 
                            <div class="col-1" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input style="width:50px" type="number" step="1" min="0" max="1000" value="<?php echo $arr_qty_supplied[$ondex-1];?>" name = "<?php echo "q_r".$bni;?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_qty_recv, getQtySupplied('q_r'.$bni)); ?>></div>
                            </td>
                            <td>
                            <div class="col-2" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input type="text" style="width:200px" value = "" name = "<?php echo "r_r".$bni; ?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_rmrk_recv, getRemark('r_r'.$bni)); $bni += 1; ?>></div>
                            </div> 
                            </td>
                            
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
                            <td><?php echo $brand;?></td>

                            <td><?php echo $arre_qty_req[$index]; 
                            $index += 1;
                            $kjh += 1; ?></td>

                            <td><?php echo $arr_qty_supplied[$ondex];?></td>
                            <td><?php echo $arr_remarks[$ondex]; $ondex+=1;?></td>
                            
                            <?php
                                if ($arre_qty_req[$index-1] == $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: lightgreen;border: 1px solid black;">
                                    <?php echo "Sufficient";
                                }
                                else if ($arre_qty_req[$index-1] > $arr_qty_supplied[$ondex-1])
                                { ?>
                                    <td style="background-color: LightSalmon;border: 1px solid black;">
                                    <?php echo "Deficit"; 
                                }
                                else{ ?>
                                    <td style="background-color: Khaki;border: 1px solid black;">
                                    <?php  echo "Excess";
                                 }
                            ?></td>
                            
                            <td> 
                            <div class="col-1" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input style="width:50px" type="number" step="1" min="0" max="1000" value="<?php echo $arr_qty_supplied[$ondex-1];?>" name = "<?php echo "q_r".$bni;?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_qty_recv, getQtySupplied('q_r'.$bni)); ?>></div>
                            </td>
                            <td>
                            <div class="col-2" style="margin-top: 5px;">
                                <div style="overflow:hidden"></div><input type="text" style="width:200px" value = "" name = "<?php echo "r_r".$bni; ?>"
                                <?php if(isset($_POST['verify']) || isset($_POST['report'])) array_push($arr_rmrk_recv, getRemark('r_r'.$bni)); $bni += 1; ?>></div>
                            </div> 
                            </td>
                            
                            </tr>
                            <?php } } } }
?>
<?php }
?>
</table>
</div>
</div>

<div class="container">
            <div class="row">
                <div class="col">
                    <button onclick="location.href = '';" class="btn btn-success text-center" style = "background-color: #ff1a1a" type="submit" name="report">Report issue to UM</button>
                </div>
                <div class="col">
                    <button onclick="location.href = '';" class="btn btn-success text-center" style = "background-color:DodgerBlue" type="submit" name="verify">Mark as Verified</button>
                </div>
            </div>
</div>
</form>
<script src="dp1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

<?php

function updateFormStatus($fid,$status,$con){ //nurse staff view
    $sq="UPDATE `dep_project`.`formhistory` SET `Status` = '$status' WHERE `formID` = '$fid';";
    $res=$con->query($sq);
    if(!$res) echo $con->error;
}

function updateFormStatus3($fid,$status,$con){ // UM view
    $sq="UPDATE `dep_project`.`formhistory` SET `StatusUM` = '$status' WHERE `formID` = '$fid';";
    $res=$con->query($sq);
    if(!$res) echo $con->error;
}


if(isset($_POST['verify'])){
    
    $num_items = sizeof($arr_qty_supplied);
    
    for($w=0;$w<$num_items;$w++){
        $wnew=$w + 1;
        
        $sql_c ="UPDATE `dep_project`.`$formidum` SET qty_recv= '$arr_qty_recv[$w]' WHERE srno = '$wnew';";
        $sql_c2 ="UPDATE `dep_project`.`$formidum` SET remark_recv= '$arr_rmrk_recv[$w]' WHERE srno = '$wnew';";
        $res = $conn->query($sql_c);
        $res2 = $conn->query($sql_c2);
        if(!$res) echo $conn->error;
        if(!$res2) echo $conn->error;
    }
    
    
    updateFormStatus($formid,"Stock Verified and fwd",$conn); //nurse staff view
    updateFormStatus3($formid,"Stock Verified by Hospital",$conn); //um view
    //header('Refresh:0.5; URL= formHis_um.php');
    //echo "<h2 id='succ'>Successfully completed.</h2>";
    echo '<script>alert("Stock verified and confirmation is sent to UM!.. You can enter the consumed items in the same tab, post operation.")</script>';
    header('refresh:0.5;url=formHis1.php');
}

if(isset($_POST['report'])){
    
    $num_items = sizeof($arr_qty_supplied);
    
    for($w=0;$w<$num_items;$w++){
        $wnew=$w + 1;
        
        $sql_c ="UPDATE `dep_project`.`$formidum` SET qty_recv= '$arr_qty_recv[$w]' WHERE srno = '$wnew';";
        $sql_c2 ="UPDATE `dep_project`.`$formidum` SET remark_recv= '$arr_rmrk_recv[$w]' WHERE srno = '$wnew';";
        $res = $conn->query($sql_c);
        $res2 = $conn->query($sql_c2);
        if(!$res) echo $conn->error;
        if(!$res2) echo $conn->error;
    }
    
    
    updateFormStatus($formid,"Issue in rcvd items - reported to UM",$conn); //nurse staff view
    updateFormStatus3($formid,"Issue reported in supply",$conn); //um view
    //header('Refresh:0.5; URL= formHis_um.php');
    //echo "<h2 id='succ'>Successfully completed.</h2>";
    echo '<script>alert("Issue reported to UM!.. You will be alerted once the UM resends the items.")</script>';
    header('refresh:0.5;url=formHis1.php');
}

ob_end_flush();

$conn->close();
?>