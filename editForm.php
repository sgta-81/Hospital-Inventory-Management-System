<?php
    $server="localhost";
	$username="root";
	$password="pgi";
	$con=mysqli_connect($server,$username,$password);
	if(!$con){
		die("connection to this database failed due to".mysqli_connect_error());
	}

    //form variables
    session_start();
    $newFormStatus="Pending";
    $ename=$_SESSION['eName'];
    //$ename="Gurpreet";
    //$pid=$_SESSION['patientID'];
    //$pid="1234567890";
    //$dateR=$_SESSION['dateR'];
    //$dateR="2021-04-10";
    date_default_timezone_set("Asia/Kolkata");
	$currTime=date("Y-m-d")." - ".date("h:i:s");
    $_SESSION['submitted']=false;
    ////////////////
    if(!isset($_POST['buttonSub'])){
        $formId=$_GET['formid'];
        $_SESSION['formid']=$formId;
    }
    $formId=$_SESSION['formid'];
    //$formId="1617641568";
    ///////employee type/////////
    if($_SESSION['etype']=="Nurse") $verifCb="vn";
    else if($_SESSION['etype']=="Doctor") $verifCb="vd";
    else if($_SESSION['etype']=="Technician") $verifCb="vt";
    else if($_SESSION['etype']=="Perfusionist") $verifCb="vp";
    //////////

    $num_items=259; // make that 256
    $ind_itemsA=51;
    $ind_itemsB=99;  //102
    $ind_itemsC=148; //153
    $ind_itemsD=202; //204
    $ind_itemsE=258; //255
    $secMap=array("1"=>"","2"=>"b","3"=>"c","4"=>"d","5"=>"e");
    $sec1spec=[];
    $sec1comp=[];
    $sec2spec=[];
    $sec2comp=[];
    $sec3spec=[];
    $sec3comp=[];
    $sec4spec=[];
    $sec4comp=[];
    $sec5spec=[];
    $sec5comp=[];

    function getItemInfo($i,&$secispec,&$secicomp,$con){ // secispec[j] = num of qty fields in section i at position j (0 based indexing), similar for secicomp (companies)
        $seci="sec".$i;
        $sql="SELECT * FROM `dep_project`.".$seci;
        $res=$con->query($sql);
        while($rows=$res->fetch_assoc()){
            array_push($secispec,$rows['specs']);
            array_push($secicomp,$rows['companies']);
        }
    }   
    function getSelectionId($sec,$srno,$secMap){ // returns s2,sb4, etc  
        if($sec==1) return "s".$srno;
        return "s".$secMap[$sec].$srno;
    }
    function getRemarkId($sec,$srno,$secMap){  // returns r2,rb4, etc
        if($sec==1) return "r".$srno;
        return "r".$secMap[$sec].$srno;
    }
    function markValues($secicomp,$secispec,$i,$qtyL,$brandL,$ci,$qi,$di,&$formVars){
        for($k=1;$k<=$secicomp[$i];$k++){
            if($secicomp[$i]==1){
                $c=substr($ci,0,-1);
            }
            else{
                $c=$ci.$k;
            }
            if($brandL[$k-1]==1){
                $formVars[$c]=1;
            }
        }
        for($k=1;$k<=$secispec[$i];$k++){
            if($secispec[$i]==1){
                $q=substr($qi,0,-1);
                $d=substr($di,0,-1);
            }
            else{
                $q=$qi.$k;
                $d=$di.$k;
            }
            if($qtyL[$k-1]>0){
                $formVars[$q]=$qtyL[$k-1];   //qty field
                $formVars[$d]=1;             //specification check box
            }
        }
    }
    function markSelCheckBox($id,$formVars){  // (load) fill selection and specifications check box for each item
        if(isset($formVars[$id])){
            echo "checked";
        }
    }
    function markCompCheckBox($id,$formVars){ // (load) fill companies check box for each item
        if(isset($formVars[$id])){
            echo "checked";
        }
    }
    function markSpecCheckBox($id,$formVars){
        if(isset($formVars[$id])){
            echo "checked";
        }
    }
    function markRemarkTextA($id,$formVars){  // (load) fill remark text area for each item
        echo (isset($formVars[$id]))?$formVars[$id]:'';
    }
    function markVerifCheckBox($id,$formVars){
        if(isset($formVars[$id]) && $formVars[$id]=="Y"){
            echo "checked";
        }
    }


    function getCreateTableQry($formId){    // create table for a new form
        $s="CREATE TABLE `dep_project`.`$formId` (
            section int(3),
            srno varchar(10),
            selected int(2),
            qty varchar(100),
            brand varchar(40),
            remark text(500)
        );";
        return $s;
    }
    function setSelectionCheckBoxIndex($i,&$srno,$ind_itemsA,$ind_itemsB,$ind_itemsC,$ind_itemsD,$ind_itemsE){ // 
        if($i<=$ind_itemsA){
            $j=$i+1;
            $srno=$j;
            return "s".$j;
        }
        else if($i<=$ind_itemsB){
            $j=$i-$ind_itemsA;
            $srno=$j;
            return "sb".$j;
        }
        else if($i<=$ind_itemsC){
            $j=$i-$ind_itemsB;
            $srno=$j;
            return "sc".$j;
        }
        else if($i<=$ind_itemsD){
            $j=$i-$ind_itemsC;
            $srno=$j;
            return "sd".$j;
        }
        else if($i<=$ind_itemsE){
            $j=$i-$ind_itemsD;
            $srno=$j;
            return "se".$j;
        }
    }

    function setRemarkIndex($i,$ind_itemsA,$ind_itemsB,$ind_itemsC,$ind_itemsD,$ind_itemsE){
        if($i<=$ind_itemsA){
            $j=$i+1;
            return "r".$j;
        }
        else if($i<=$ind_itemsB){
            $j=$i-$ind_itemsA;
            return "rb".$j;
        }
        else if($i<=$ind_itemsC){
            $j=$i-$ind_itemsB;           
            return "rc".$j;
        }
        else if($i<=$ind_itemsD){
            $j=$i-$ind_itemsC;          
            return "rd".$j;
        }
        else if($i<=$ind_itemsE){
            $j=$i-$ind_itemsD;
            return "re".$j;
        }
    }

    function getRemark($remI){ // (insert) get filled remark for inserting into db
        if(isset($_POST[$remI])){
            return $_POST[$remI];
        }
        else return " ";
    }

    function getValues($secicomp,$secispec,$i,&$qtyL,&$brandL,$ci,$qi){ //(insert)
    
        for($k=1;$k<=$secicomp[$i];$k++){
            if($secicomp[$i]==1){
                $c=substr($ci,0,-1);
            }
            else{
                $c=$ci.$k;
            }
            if(isset($_POST[$c])){
                $brandL=$brandL."1";
            }
            else{
                $brandL=$brandL."0";
            }
        }
        for($k=1;$k<=$secispec[$i];$k++){
            if($secispec[$i]==1){
                $sp=substr($qi,0,-1);
            }
            else{
                $sp=$qi.$k;
            }
            if(isset($_POST[$sp])){
                $qtyL=$qtyL.$_POST[$sp];
            }
            else{
                $qtyL=$qtyL."0";
            }
        }
    }

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


    getItemInfo(1,$sec1spec,$sec1comp,$con);
    getItemInfo(2,$sec2spec,$sec2comp,$con);
    getItemInfo(3,$sec3spec,$sec3comp,$con);
    getItemInfo(4,$sec4spec,$sec4comp,$con);
    getItemInfo(5,$sec5spec,$sec5comp,$con);
    //echo $sec1spec[0];
    
    //////////////////load form/////////

    //if(!isset($_POST['buttonSub'])){
    $sql="SELECT * FROM `dep_project`.`$formId`";  // import values from database corresponding to the given formId
	$res=$con->query($sql);
	$num_t=$res->num_rows;
	$secarr=[];
	$srnoarr=[];
	$qtyarr=[];
	$brandarr=[];
	$remarkarr=[];
    $formVars=[];
	while($rows=$res->fetch_assoc()){
		array_push($secarr,$rows['section']);
		array_push($srnoarr,$rows['srno']);
		array_push($qtyarr,$rows['qty']);
		array_push($brandarr,$rows['brand']);
		array_push($remarkarr,$rows['remark']);
	}
    
    for($i=0;$i<$num_t;$i++){    // fill values imported from database
        $srno=$srnoarr[$i];
        $j=$srno;
        $sec=$secarr[$i];
        $brandL=$brandarr[$i];
        $qtyL=$qtyarr[$i];
        $rem=$remarkarr[$i];
        //$localIndex=$j;
        $selId=getSelectionId($sec,$srno,$secMap);
        //$x=0;//dummy variable
        //$selId=setSelectionCheckBoxIndex($i,$x,$ind_itemsA,$ind_itemsB,$ind_itemsC,$ind_itemsD,$ind_itemsE);
        $remId=getRemarkId($sec,$srno,$secMap);
        $formVars[$selId]=1;
        $formVars[$remId]=$rem;
        $compId;
        $qtyId;
        //markValues($secicomp,$secispec,$i,$qtyL,$brandL,$ci,$qi,$di,&$formVars){
        if($sec==1){
            $c="c".$j."-";
            $sp="q".$j."-";
            $di="d".$j."-";
            markValues($sec1comp,$sec1spec,$srno-1,$qtyL,$brandL,$c,$sp,$di,$formVars);
        }
        else if($sec==2){
            //$j=$i-$ind_itemsA;
            $c="cb".$j."-";
            $sp="qb".$j."-";
            $di="db".$j."-";
            markValues($sec2comp,$sec2spec,$srno-1,$qtyL,$brandL,$c,$sp,$di,$formVars);  
        }
        else if($sec==3){
            //$j=$i-$ind_itemsB;
            $c="cc".$j."-";
            $sp="qc".$j."-";
            $di="dc".$j."-";
            markValues($sec3comp,$sec3spec,$srno-1,$qtyL,$brandL,$c,$sp,$di,$formVars);
        }
        else if($sec==4){
            //$j=$i-$ind_itemsC;
            $c="cd".$j."-";
            $sp="qd".$j."-";
            $di="dd".$j."-";
            markValues($sec4comp,$sec4spec,$srno-1,$qtyL,$brandL,$c,$sp,$di,$formVars);
        }
        else if($sec==5){
            //$j=$i-$ind_itemsD;
            $c="ce".$j."-";
            $sp="qe".$j."-";
            $di="de".$j."-";
            markValues($sec5comp,$sec5spec,$srno-1,$qtyL,$brandL,$c,$sp,$di,$formVars);
        }        
        
    }
    $sql1="SELECT * FROM `dep_project`.`verified_by` WHERE `formid` = '$formId';";
    $res1=$con->query($sql1);
    while($rowsEl=$res1->fetch_assoc()){
			$formVars['vn']=$rowsEl['nurse_send'];
			$formVars['vt']=$rowsEl['techn_send'];
			$formVars['vp']=$rowsEl['perf_send'];
            $formVars['vd']=$rowsEl['consultant_send'];
	}
    //}
    //echo $_POST['r1'];

    //////////////////Insert the edited form//////////////////

    if(isset($_POST['buttonSub'])){
        $sql="DROP TABLE `dep_project`.`$formId` ";
        $res=$con->query($sql);
        if(!$res) echo $con->error;
        $res=$con->query(getCreateTableQry($formId));
        if(!$res) echo $con->error;
        /////
        
        for($i=0;$i<$num_items;$i++){
            $j=$i+1;
            $srno=$j;
            $seli=setSelectionCheckBoxIndex($i,$srno,$ind_itemsA,$ind_itemsB,$ind_itemsC,$ind_itemsD,$ind_itemsE);
            $sec;
            $isSelected=0;
            $brandL='';
            $qtyL='';
            $rem='';
            //$localIndex=$j;
            if(isset($_POST[$seli])){
                $isSelected=1;
                if($i<=$ind_itemsA){
                    $sec='1';
                    $c="c".$j."-";
                    $sp="q".$j."-";
                    getValues($sec1comp,$sec1spec,$i,$qtyL,$brandL,$c,$sp);
                }
                else if($i<=$ind_itemsB){
                    $j=$i-$ind_itemsA;
                    $sec='2';
                    $c="cb".$j."-";
                    $sp="qb".$j."-";
                    getValues($sec2comp,$sec2spec,$i-$ind_itemsA-1,$qtyL,$brandL,$c,$sp);
                    $localIndex=$j;
                }
                else if($i<=$ind_itemsC){
                    $j=$i-$ind_itemsB;
                    $sec='3';
                    $c="cc".$j."-";
                    $sp="qc".$j."-";
                    getValues($sec3comp,$sec3spec,$i-$ind_itemsB-1,$qtyL,$brandL,$c,$sp);
                }
                else if($i<=$ind_itemsD){
                    $j=$i-$ind_itemsC;
                    $sec='4';
                    $c="cd".$j."-";
                    $sp="qd".$j."-";
                    getValues($sec4comp,$sec4spec,$i-$ind_itemsC-1,$qtyL,$brandL,$c,$sp);
                }
                else if($i<=$ind_itemsE){
                    $j=$i-$ind_itemsD;
                    $sec='5';
                    $c="ce".$j."-";
                    $sp="qe".$j."-";
                    getValues($sec5comp,$sec5spec,$i-$ind_itemsD-1,$qtyL,$brandL,$c,$sp);
                }
                $remIndex=setRemarkIndex($i,$ind_itemsA,$ind_itemsB,$ind_itemsC,$ind_itemsD,$ind_itemsE);
                $rem=getRemark($remIndex);
                //echo $sql;
                
                $sql="INSERT INTO `dep_project`.`$formId`(`section`,`srno`, `selected`, `qty`,`brand`,`remark`) VALUES ('$sec','$srno','$isSelected','$qtyL','$brandL','$rem')";
                $res=$con->query($sql);
                if(!$res) echo $con->error;
                
            }
    
        }
        //echo $_POST['r1'];
        //////////////verification///////////////////
        $changesMadeByStaff=false;
        if(isset($_POST[$verifCb])){
            if($_SESSION['etype']=="Nurse"){
                $sql="UPDATE `dep_project`.`verified_by` SET `nurse_send` = 'Y' WHERE `formid` = '$formId';";
                $changesMadeByStaff=true;
            }
            else if($_SESSION['etype']=="Doctor"){
                $sql="UPDATE `dep_project`.`verified_by` SET `consultant_send` = 'Y' WHERE `formid` = '$formId';";
                
            }
            else if($_SESSION['etype']=="Perfusionist"){
                $sql="UPDATE `dep_project`.`verified_by` SET `perf_send` = 'Y' WHERE `formid` = '$formId';";
                $changesMadeByStaff=true;
            }
            else if($_SESSION['etype']=="Technician"){
                $sql="UPDATE `dep_project`.`verified_by` SET `techn_send` = 'Y' WHERE `formid` = '$formId';";
                $changesMadeByStaff=true;
            }
            $res=$con->query($sql);
            if(!$res) echo $con->error;
        }
        else{
            if($_SESSION['etype']=="Nurse"){
                $sql="UPDATE `dep_project`.`verified_by` SET `nurse_send` = 'N' WHERE `formid` = '$formId';";
                $changesMadeByStaff=true;
            }
            else if($_SESSION['etype']=="Doctor"){
                $sql="UPDATE `dep_project`.`verified_by` SET `consultant_send` = 'N' WHERE `formid` = '$formId';";
            }
            else if($_SESSION['etype']=="Perfusionist"){
                $sql="UPDATE `dep_project`.`verified_by` SET `perf_send` = 'N' WHERE `formid` = '$formId';";
                $changesMadeByStaff=true;
            }
            else if($_SESSION['etype']=="Technician"){
                $sql="UPDATE `dep_project`.`verified_by` SET `techn_send` = 'N' WHERE `formid` = '$formId';";
                $changesMadeByStaff=true;
            }
            $res=$con->query($sql);
            if(!$res) echo $con->error;
        }

            //////////Status update//////////////////
            $sql1="SELECT * FROM `dep_project`.`verified_by` WHERE `formid` = '$formId';";
            $res1=$con->query($sql1);
            while($rowsEl=$res1->fetch_assoc()){
				$nApproved=$rowsEl['nurse_send'];
				$tApproved=$rowsEl['techn_send'];
				$pApproved=$rowsEl['perf_send'];
                $cApproved=$rowsEl['consultant_send'];
                $uApproved=$rowsEl['um_disp'];
			}
            if($changesMadeByStaff==true && $cApproved=='Y'){
                updateFormStatus($formId,"Sent for Approval",$con);
                updateFormStatus2($formId,"Approval Pending",$con);
                updateFormStatus3($formId,"NA",$con); //um view
            }
            else if($nApproved=='Y' && $tApproved=='Y' && $pApproved=='Y' && $cApproved=='Y' && $uApproved='N'){
                updateFormStatus($formId,"Approved by Consultant",$con); //nurse staff view
                updateFormStatus2($formId,"Approved",$con); //consultant view
                updateFormStatus3($formId,"Ready for Dispatch",$con); //um view
            }
            else if($nApproved=='Y' && $tApproved=='Y' && $pApproved=='Y' && $cApproved=='N' && $uApproved='N'){
                updateFormStatus($formId,"Sent for Approval",$con);
                updateFormStatus2($formId,"Approval Pending",$con);
            }
            //else if($nApproved=='Y' && $tApproved=='Y' && $pApproved=='Y' && $cApproved=='Y' && $uApproved='Y'){ to be implemented in um's part
            //    updateFormStatus($formId,"Dispatched",$con);
            //}

        $_SESSION['submitted']=true;
    }

?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-dark navbar-expand-md bg-dark">
        <div class="container-fluid"><a class="navbar-brand" href="#">Fill New Form</a>
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
                            <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><?php echo $ename;?>&nbsp;</a>
                                <div class="dropdown-menu"><a class="dropdown-item" href="lgsi.php">Logout</a></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
    </nav>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-1">Section-A</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-2">Section-B</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-3">Section-C</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-4">Section-D</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-5">Section-E</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link active" role="tab" data-toggle="tab" href="#tab-6">Verification</a></li>
        </ul>
        <form action="editForm.php" method="post">
        <div class="tab-content">
                <?php
                    if($_SESSION['submitted']){ ?>
                        <!-- <h2 id="succ">Form Updated Successfully</h2> -->
                        <?php
                        $_SESSION['submitted']=false;
                        echo '<script>alert("Form Updated Successfully!.. Form is in \"Incomplete Forms\" Tab")</script>';
                        header('Refresh:0.1; URL= formHis.php');
                    }
                    
                ?>
            <div class="tab-pane active" role="tabpanel" id="tab-1">
                <p></p>
                <h2 class="text-left" style="font-size: 26;">&nbsp; &nbsp;Anaesthetic Items</h2>
                <p>&nbsp; &nbsp; &nbsp; Mark the <strong>Anaesthetic Items</strong> Required and go to next Tab for next section.</p>
                <div class="container">
                    <div class="row">
                        <div class="col-1"><label class="col-form-label"><strong>Sr.No</strong></label></div>
                        <div class="col-2"><label class="col-form-label"><strong>Item Name</strong></label></div>
                        <div class="col-3"><label class="col-form-label"><strong>Description</strong></label></div>
                        <div class="col-3"><label class="col-form-label"><strong>Company/Brand Name Preferred&nbsp;</strong></label></div>
                        <div class="col-1"><label class="col-form-label"><strong>Qty Required&nbsp;</strong></label></div>
                        <div class="col-2"><label class="col-form-label"><strong>Remarks</strong><br></label></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-850" name="s1" <?php markSelCheckBox('s1',$formVars); ?>><label class="form-check-label" for="formCheck-23">1</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">ACT Tubes</label></div>
                        <div class="col-3"><label class="col-form-label">Compatible to our machine</label></div>
                        <div class="col-3"><label class="col-form-label">Helena Lab, Beaumount Texas</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q1" value="<?php echo (isset($formVars['q1']))?$formVars['q1']:0; ?>"></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r1" value="<?php echo (isset($formVars['r1']))?$formVars['r1']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-855" name="s2" <?php markSelCheckBox('s2',$formVars); ?>><label class="form-check-label" for="formCheck-24">2</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Arterial line cannula</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-856" name="d2-1" <?php markSelCheckBox('d2-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">18g</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-857" name="d2-2" <?php markSelCheckBox('d2-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">20g</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-858" name="d2-3" <?php markSelCheckBox('d2-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">22g</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-859" name="c2-1" <?php markCompCheckBox('c2-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Becton Dickinson (BD)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-860" name="c2-2" <?php markCompCheckBox('c2-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Vygon</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q2-1" value="<?php echo (isset($formVars['q2-1']))?$formVars['q2-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q2-2" value="<?php echo (isset($formVars['q2-2']))?$formVars['q2-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q2-3"  value="<?php echo (isset($formVars['q2-3']))?$formVars['q2-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="r2"><?php markRemarkTextA('r2',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-861" name="s3" <?php markSelCheckBox('s3',$formVars); ?>><label class="form-check-label" for="formCheck-26">3A</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Blood Glucose Strip</label></div>
                        <div class="col-3"><label class="col-form-label">Compatible to our machine</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-862" name="c3-1" <?php markCompCheckBox('c3-1',$formVars); ?>><label class="form-check-label" for="formCheck-39">Arkay Factory</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-863" name="c3-2" <?php markCompCheckBox('c3-2',$formVars); ?>><label class="form-check-label" for="formCheck-40">Nipro</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-864" name="c3-3" <?php markCompCheckBox('c3-3',$formVars); ?>><label class="form-check-label" for="formCheck-41">Optium</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q3"  value="<?php echo (isset($formVars['q3']))?$formVars['q3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="r3"><?php markRemarkTextA('r3',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-865" name="s4" <?php markSelCheckBox('s4',$formVars); ?>><label class="form-check-label" for="formCheck-27">3B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Blood Transfusion Set</label></div>
                        <div class="col-3"><label class="col-form-label">With Leur Lock</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-866" name="c4-1" <?php markCompCheckBox('c4-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">BD</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-867" name="c4-2" <?php markCompCheckBox('c4-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Romson</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q4"  value="<?php echo (isset($formVars['q4']))?$formVars['q4']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r4" value="<?php echo (isset($formVars['r4']))?$formVars['r4']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-868" name="s5" <?php markSelCheckBox('s5',$formVars); ?>><label class="form-check-label" for="formCheck-28">4</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">BlS Sensor</label></div>
                        <div class="col-3"><label class="col-form-label">Adult Pediatric</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Medtronic</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q5"  value="<?php echo (isset($formVars['q5']))?$formVars['q5']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r5" value="<?php echo (isset($formVars['r5']))?$formVars['r5']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-869" name="s6" <?php markSelCheckBox('s6',$formVars); ?>><label class="form-check-label" for="formCheck-29">5</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Bronchial Blocker</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-870" name="d6-1" <?php markSelCheckBox('d6-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">5F&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-871" name="d6-2" <?php markSelCheckBox('d6-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">7F</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-872" name="c6-1" <?php markCompCheckBox('c6-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-873" name="c6-2" <?php markCompCheckBox('c6-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Rusch</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q6-1"  value="<?php echo (isset($formVars['q6-1']))?$formVars['q6-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q6-2"  value="<?php echo (isset($formVars['q6-2']))?$formVars['q6-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r6" value="<?php echo (isset($formVars['r6']))?$formVars['r6']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-874" name="s7" <?php markSelCheckBox('s7',$formVars); ?>><label class="form-check-label" for="formCheck-30">6</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Camera Cover&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-875" name="c7-1" <?php markCompCheckBox('c7-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">Surgiwear</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-876" name="c7-2" <?php markCompCheckBox('c7-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Teleflex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-877" name="c7-3" <?php markCompCheckBox('c7-3',$formVars); ?>><label class="form-check-label" for="formCheck-48">Niko</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q7"  value="<?php echo (isset($formVars['q7']))?$formVars['q7']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r7" value="<?php echo (isset($formVars['r7']))?$formVars['r7']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-878" name="s8" <?php markSelCheckBox('s8',$formVars); ?>><label class="form-check-label" for="formCheck-31">7</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Central Venuos Line</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-879" name="d8-1" <?php markSelCheckBox('d8-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">Triple Lumen&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-880" name="d8-2" <?php markSelCheckBox('d8-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">Four Lumen</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-881" name="c8-1" <?php markCompCheckBox('c8-1',$formVars); ?>><label class="form-checklabel" for="formCheck-49">Arrow</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-882" name="c8-2" <?php markCompCheckBox('c8-2',$formVars); ?>><label class="form-check-label" for="formCheck-50">Edward Lifesciences</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-883" name="c8-3" <?php markCompCheckBox('c8-3',$formVars); ?>><label class="form-check-label" for="formCheck-52">Vygon</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-884" name="c8-4" <?php markCompCheckBox('c8-4',$formVars); ?>><label class="form-check-label" for="formCheck-51">3M</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q8-1"  value="<?php echo (isset($formVars['q8-1']))?$formVars['q8-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q8-2"  value="<?php echo (isset($formVars['q8-2']))?$formVars['q8-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="r8"><?php markRemarkTextA('r8',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-885" name="s9" <?php markSelCheckBox('s9',$formVars); ?>><label class="form-check-label" for="formCheck-32">8</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Dial FLow&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-886" name="c9-1" <?php markCompCheckBox('c9-1',$formVars); ?>><label class="form-check-label" for="formCheck-53">Leventon</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-887" name="c9-2" <?php markCompCheckBox('c9-2',$formVars); ?>><label class="form-check-label" for="formCheck-54">Romson</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q9"  value="<?php echo (isset($formVars['q9']))?$formVars['q9']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r9" value="<?php echo (isset($formVars['r9']))?$formVars['r9']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-888" name="s10" <?php markSelCheckBox('s10',$formVars); ?>><label class="form-check-label" for="formCheck-33">9A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Disposable Syringe with Leur Lock</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-889" name="d10-1" <?php markSelCheckBox('d10-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">2ml&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-890" name="d10-2" <?php markSelCheckBox('d10-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">5ml</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-891" name="d10-3" <?php markSelCheckBox('d10-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">10ml</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-892" name="d10-4" <?php markSelCheckBox('d10-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">20ml</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-893" name="d10-5" <?php markSelCheckBox('d10-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">50ml</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-894" name="c10-1" <?php markCompCheckBox('c10-1',$formVars); ?>><label class="form-check-label" for="formCheck-55">Dispovan</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-895" name="c10-2" <?php markCompCheckBox('c10-2',$formVars); ?>><label class="form-check-label" for="formCheck-56">BD</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q10-1" value="<?php echo (isset($formVars['q10-1']))?$formVars['q10-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q10-2" value="<?php echo (isset($formVars['q10-2']))?$formVars['q10-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q10-3" value="<?php echo (isset($formVars['q10-3']))?$formVars['q10-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q10-4" value="<?php echo (isset($formVars['q10-4']))?$formVars['q10-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q10-5" value="<?php echo (isset($formVars['q10-5']))?$formVars['q10-5']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="r10"><?php markRemarkTextA('r10',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-896" name="s11" <?php markSelCheckBox('s11',$formVars); ?>><label class="form-check-label" for="formCheck-34">9B</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Disposable Syringe without Leur Lock</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-897" name="d11-1" <?php markSelCheckBox('d11-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">1ml&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-898" name="d11-2" <?php markSelCheckBox('d11-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">2ml</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-899" name="d11-3" <?php markSelCheckBox('d11-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">5ml</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-900" name="d11-4" <?php markSelCheckBox('d11-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">20ml</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-901" name="d11-5" <?php markSelCheckBox('d11-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">50ml</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-902" name="c11-1" <?php markCompCheckBox('c11-1',$formVars); ?>><label class="form-check-label" for="formCheck-57">Dispovan</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-903" name="c11-2" <?php markCompCheckBox('c11-2',$formVars); ?>><label class="form-check-label" for="formCheck-58">BD</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q11-1" value="<?php echo (isset($formVars['q11-1']))?$formVars['q11-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q11-2" value="<?php echo (isset($formVars['q11-2']))?$formVars['q11-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q11-3" value="<?php echo (isset($formVars['q11-3']))?$formVars['q11-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q11-4" value="<?php echo (isset($formVars['q11-4']))?$formVars['q11-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q11-5" value="<?php echo (isset($formVars['q11-5']))?$formVars['q11-5']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="r11"><?php markRemarkTextA('r11',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-904" name="s12" <?php markSelCheckBox('s12',$formVars); ?>><label class="form-check-label" for="formCheck-35">10A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Disposable Ventilator tubing, Adult
</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-905" name="d12-1" <?php markSelCheckBox('d12-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">with water trap&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-906" name="d12-2" <?php markSelCheckBox('d12-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">without water trap&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-907" name="c12-1" <?php markCompCheckBox('c12-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-908" name="c12-2" <?php markCompCheckBox('c12-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Intersurgical</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-909" name="c12-3" <?php markCompCheckBox('c12-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Life Line</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q12-1" value="<?php echo (isset($formVars['q12-1']))?$formVars['q12-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q12-2" value="<?php echo (isset($formVars['q12-2']))?$formVars['q12-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r12" value="<?php echo (isset($formVars['r12']))?$formVars['r12']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-910" name="s13" <?php markSelCheckBox('s13',$formVars); ?>><label class="form-check-label" for="formCheck-36">10B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Disposable Ventilator Tubing, Pediatric&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-911" name="c13-1" <?php markCompCheckBox('c13-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-912" name="c13-2" <?php markCompCheckBox('c13-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Intersurgical</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-913" name="c13-3" <?php markCompCheckBox('c13-3',$formVars); ?>><label class="form-check-label" for="formCheck-64">Life Line</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q13" value="<?php echo (isset($formVars['q13']))?$formVars['q13']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="r13"><?php markRemarkTextA('r13',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-914" name="s14" <?php markSelCheckBox('s14',$formVars); ?>><label class="form-check-label" for="formCheck-36">11A</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Disposable Nasal Prong(Adult)&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-915" name="c14-1" <?php markCompCheckBox('c14-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-916" name="c14-2" <?php markCompCheckBox('c14-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Medisafe</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q14" value="<?php echo (isset($formVars['q14']))?$formVars['q14']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r14" value="<?php echo (isset($formVars['r14']))?$formVars['r14']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-917" name="s15" <?php markSelCheckBox('s15',$formVars); ?>><label class="form-check-label" for="formCheck-36">11B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Disposable Nasal Prong(Pediatric)&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-918" name="c15-1" <?php markCompCheckBox('c15-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-919" name="c15-2" <?php markCompCheckBox('c15-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Medisafe</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q15" value="<?php echo (isset($formVars['q15']))?$formVars['q15']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r15" value="<?php echo (isset($formVars['r15']))?$formVars['r15']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-920" name="s16" <?php markSelCheckBox('s16',$formVars); ?>><label class="form-check-label" for="formCheck-28">12</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Disposable IV Canula Fixation Dressing</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Primapore</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q16" value="<?php echo (isset($formVars['q16']))?$formVars['q16']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r16" value="<?php echo (isset($formVars['r16']))?$formVars['r16']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-921" name="s17" <?php markSelCheckBox('s17',$formVars); ?>><label class="form-check-label" for="formCheck-28">13</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Durapore</label></div>
                        <div class="col-3"><label class="col-form-label">4 inch</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">3M</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q17" value="<?php echo (isset($formVars['q17']))?$formVars['q17']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r17" value="<?php echo (isset($formVars['r17']))?$formVars['r17']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-922" name="s18" <?php markSelCheckBox('s18',$formVars); ?>><label class="form-check-label" for="formCheck-36">14A</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">ECG Electrodes(Adult)</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-923" name="c18-1" <?php markCompCheckBox('c18-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">3M</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-924" name="c18-2" <?php markCompCheckBox('c18-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Arbo</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-925" name="c18-3" <?php markCompCheckBox('c18-3',$formVars); ?>><label class="form-check-label" for="formCheck-78">Niko</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-926" name="c18-4" <?php markCompCheckBox('c18-4',$formVars); ?>><label class="form-check-label" for="formCheck-77">Kenny</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-927" name="c18-5" <?php markCompCheckBox('c18-5',$formVars); ?>><label class="form-check-label" for="formCheck-76">Swaromed</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q18" value="<?php echo (isset($formVars['q18']))?$formVars['q18']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="r18"><?php markRemarkTextA('r18',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-928" name="s19" <?php markSelCheckBox('s19',$formVars); ?>><label class="form-check-label" for="formCheck-36">14B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">ECG Electrodes(Pediatric)</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-929" name="c19-1" <?php markCompCheckBox('c19-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">3M</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-930" name="c19-2" <?php markCompCheckBox('c19-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Arbo</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-931" name="c19-3" <?php markCompCheckBox('c19-3',$formVars); ?>><label class="form-check-label" for="formCheck-78">Niko</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-932" name="c19-4" <?php markCompCheckBox('c19-4',$formVars); ?>><label class="form-check-label" for="formCheck-77">Kenny</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-933" name="c19-5" <?php markCompCheckBox('c19-5',$formVars); ?>><label class="form-check-label" for="formCheck-76">Swaromed</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q19" value="<?php echo (isset($formVars['q19']))?$formVars['q19']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="r19"><?php markRemarkTextA('r19',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-934" name="s20" <?php markSelCheckBox('s20',$formVars); ?>><label class="form-check-label" for="formCheck-36">15</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Elastoplast (Dynaplast)</label></div>
                        <div class="col-3"><label class="col-form-label">4inch</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-935" name="c20-1" <?php markCompCheckBox('c20-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">3M</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-936" name="c20-2" <?php markCompCheckBox('c20-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Johnson&amp;Johnson</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q20" value="<?php echo (isset($formVars['q20']))?$formVars['q20']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r20" value="<?php echo (isset($formVars['r20']))?$formVars['r20']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-937" name="s21" <?php markSelCheckBox('s21',$formVars); ?>><label class="form-check-label" for="formCheck-35">16A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Endotrachreal Tube with Cuffed
</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-938" name="d21-1" <?php markSelCheckBox('d21-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">4.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-939" name="d21-2" <?php markSelCheckBox('d21-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-940" name="d21-3" <?php markSelCheckBox('d21-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">5.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-941" name="d21-4" <?php markSelCheckBox('d21-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">6mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-942" name="d21-5" <?php markSelCheckBox('d21-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">6.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-943" name="d21-6" <?php markSelCheckBox('d21-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">7mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-944" name="d21-7" <?php markSelCheckBox('d21-7',$formVars); ?>><label class="form-check-label" for="formCheck-1">7.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-945" name="d21-8" <?php markSelCheckBox('d21-8',$formVars); ?>><label class="form-check-label" for="formCheck-1">8mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-946" name="d21-9" <?php markSelCheckBox('d21-9',$formVars); ?>><label class="form-check-label" for="formCheck-1">8.5mm</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-947" name="c21-1" <?php markCompCheckBox('c21-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-948" name="c21-2" <?php markCompCheckBox('c21-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Rusch</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-949" name="c21-3" <?php markCompCheckBox('c21-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Vygon</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-1" value="<?php echo (isset($formVars['q21-1']))?$formVars['q21-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-2" value="<?php echo (isset($formVars['q21-2']))?$formVars['q21-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-3" value="<?php echo (isset($formVars['q21-3']))?$formVars['q21-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-4" value="<?php echo (isset($formVars['q21-4']))?$formVars['q21-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-5" value="<?php echo (isset($formVars['q21-5']))?$formVars['q21-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-6" value="<?php echo (isset($formVars['q21-6']))?$formVars['q21-6']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-7" value="<?php echo (isset($formVars['q21-7']))?$formVars['q21-7']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-8" value="<?php echo (isset($formVars['q21-8']))?$formVars['q21-8']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q21-9" value="<?php echo (isset($formVars['q21-9']))?$formVars['q21-9']:0; ?>" /></div></div>
                        <div class="col-2"
                            style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="6" name="r21"><?php markRemarkTextA('r21',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-950" name="s22" <?php markSelCheckBox('s22',$formVars); ?>><label class="form-check-label" for="formCheck-35">16B</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Endotrachreal Tube with Uncuffed
</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-951" name="d22-1" <?php markSelCheckBox('d22-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">4.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-952" name="d22-2" <?php markSelCheckBox('d22-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-953" name="d22-3" <?php markSelCheckBox('d22-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">5.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-954" name="d22-4" <?php markSelCheckBox('d22-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">6mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-955" name="d22-5" <?php markSelCheckBox('d22-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">6.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-956" name="d22-6" <?php markSelCheckBox('d22-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">3mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-957" name="d22-7" <?php markSelCheckBox('d22-7',$formVars); ?>><label class="form-check-label" for="formCheck-1">3.5mm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-958" name="d22-8" <?php markSelCheckBox('d22-8',$formVars); ?>><label class="form-check-label" for="formCheck-1">4mm</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-959" name="c22-1" <?php markCompCheckBox('c22-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-960" name="c22-2" <?php markCompCheckBox('c22-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Rusch</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-961" name="c22-3" <?php markCompCheckBox('c22-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Vygon</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-1" value="<?php echo (isset($formVars['q22-1']))?$formVars['q22-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-2" value="<?php echo (isset($formVars['q22-2']))?$formVars['q22-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-3" value="<?php echo (isset($formVars['q22-3']))?$formVars['q22-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-4" value="<?php echo (isset($formVars['q22-4']))?$formVars['q22-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-5" value="<?php echo (isset($formVars['q22-5']))?$formVars['q22-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-6" value="<?php echo (isset($formVars['q22-6']))?$formVars['q22-6']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-7" value="<?php echo (isset($formVars['q22-7']))?$formVars['q22-7']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q22-8" value="<?php echo (isset($formVars['q22-8']))?$formVars['q22-8']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="6" name="r22"><?php markRemarkTextA('r22',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-962" name="s23" <?php markSelCheckBox('s23',$formVars); ?>><label class="form-check-label" for="formCheck-35">16C</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Endotrachreal Tube,Double Lumen Tube(DLT)
</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-963" name="d23-1" <?php markSelCheckBox('d23-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">26Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-964" name="d23-2" <?php markSelCheckBox('d23-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">28Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-965" name="d23-3" <?php markSelCheckBox('d23-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">32Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-966" name="d23-4" <?php markSelCheckBox('d23-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">37Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-967" name="d23-5" <?php markSelCheckBox('d23-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">39Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label style="width:100%">Portex</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q23-1" value="<?php echo (isset($formVars['q23-1']))?$formVars['q23-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q23-2" value="<?php echo (isset($formVars['q23-2']))?$formVars['q23-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q23-3" value="<?php echo (isset($formVars['q23-3']))?$formVars['q23-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q23-4" value="<?php echo (isset($formVars['q23-4']))?$formVars['q23-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q23-5" value="<?php echo (isset($formVars['q23-5']))?$formVars['q23-5']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="r23"><?php markRemarkTextA('r23',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-968" name="s24" <?php markSelCheckBox('s24',$formVars); ?>><label class="form-check-label" for="formCheck-36">17</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Epidural Set (MINI PACK)</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-969" name="c24-1" <?php markCompCheckBox('c24-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-970" name="c24-2" <?php markCompCheckBox('c24-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Vygon</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q24" value="<?php echo (isset($formVars['q24']))?$formVars['q24']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r24" value="<?php echo (isset($formVars['r24']))?$formVars['r24']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-971" name="s25" <?php markSelCheckBox('s25',$formVars); ?>><label class="form-check-label" for="formCheck-35">18</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Heat and Moisture Exchanger (HME)</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-972" name="d25-1" <?php markSelCheckBox('d25-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">Adult</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-973" name="d25-2" <?php markSelCheckBox('d25-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">Pediatric&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-974" name="c25-1" <?php markCompCheckBox('c25-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-975" name="c25-2" <?php markCompCheckBox('c25-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Intersurgical</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-976" name="c25-3" <?php markCompCheckBox('c25-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Life Line</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q25-1" value="<?php echo (isset($formVars['q25-1']))?$formVars['q25-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q25-2" value="<?php echo (isset($formVars['q25-2']))?$formVars['q25-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r25" value="<?php echo (isset($formVars['r25']))?$formVars['r25']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-977" name="s26" <?php markSelCheckBox('s26',$formVars); ?>><label class="form-check-label" for="formCheck-36">19</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">High Pressure Bag</label></div>
                        <div class="col-3"><label class="col-form-label">for rapid infusion</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-978" name="c26-1" <?php markCompCheckBox('c26-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Ethox</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-979" name="c26-2" <?php markCompCheckBox('c26-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">SunMed</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q26" value="<?php echo (isset($formVars['q26']))?$formVars['q26']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r26" value="<?php echo (isset($formVars['r26']))?$formVars['r26']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-980" name="s27" <?php markSelCheckBox('s27',$formVars); ?>><label class="form-check-label" for="formCheck-35">20</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">High Pressure Tubing, Male to Male</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-981" name="d27-1" <?php markSelCheckBox('d27-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">150cm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-982" name="d27-2" <?php markSelCheckBox('d27-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">200cm&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-983" name="c27-1" <?php markCompCheckBox('c27-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-984" name="c27-2" <?php markCompCheckBox('c27-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Vyon</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q27-1" value="<?php echo (isset($formVars['q27-1']))?$formVars['q27-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q27-2" value="<?php echo (isset($formVars['q27-2']))?$formVars['q27-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r27" value="<?php echo (isset($formVars['r27']))?$formVars['r27']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-985" name="s28" <?php markSelCheckBox('s28',$formVars); ?>><label class="form-check-label" for="formCheck-35">21</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">High Pressure Tubing, Male to Female</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-986" name="d28-1" <?php markSelCheckBox('d28-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">150cm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-987" name="d28-2" <?php markSelCheckBox('d28-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">200cm&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-988" name="c28-1" <?php markCompCheckBox('c28-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-989" name="c28-2" <?php markCompCheckBox('c28-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Vyon</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q28-1" value="<?php echo (isset($formVars['q28-1']))?$formVars['q28-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q28-2" value="<?php echo (isset($formVars['q28-2']))?$formVars['q28-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r28" value="<?php echo (isset($formVars['r28']))?$formVars['r28']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-990" name="s29" <?php markSelCheckBox('s29',$formVars); ?>><label class="form-check-label" for="formCheck-35">22</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Hypodermic Needle</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-991" name="d29-1" <?php markSelCheckBox('d29-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">26G, 0.5inch</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-992" name="d29-2" <?php markSelCheckBox('d29-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">26G, 1.5inch</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label style="width:100%">BD</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q29-1" value="<?php echo (isset($formVars['q29-1']))?$formVars['q29-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q29-2" value="<?php echo (isset($formVars['q29-2']))?$formVars['q29-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r29" value="<?php echo (isset($formVars['r29']))?$formVars['r29']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-993" name="s30" <?php markSelCheckBox('s30',$formVars); ?>><label class="form-check-label" for="formCheck-36">23</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">IV Infusion Set, with Leur lock</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-994" name="c30-1" <?php markCompCheckBox('c30-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-995" name="c30-2" <?php markCompCheckBox('c30-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">BD</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-996" name="c30-3" <?php markCompCheckBox('c30-3',$formVars); ?>><label class="form-check-label" for="formCheck-63">Braun</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q30" value="<?php echo (isset($formVars['q30']))?$formVars['q30']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r30" value="<?php echo (isset($formVars['r30']))?$formVars['r30']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-997" name="s31" <?php markSelCheckBox('s31',$formVars); ?>><label class="form-check-label" for="formCheck-25">24</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Infant Feeding Tube</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-998" name="d31-1" <?php markSelCheckBox('d31-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">5Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-999" name="d31-2" <?php markSelCheckBox('d31-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">6Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1000" name="d31-3" <?php markSelCheckBox('d31-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">7Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1001" name="d31-4" <?php markSelCheckBox('d31-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">8Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1002" name="d31-5" <?php markSelCheckBox('d31-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">9Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1003" name="d31-6" <?php markSelCheckBox('d31-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Romson</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q31-1" value="<?php echo (isset($formVars['q31-1']))?$formVars['q31-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q31-2" value="<?php echo (isset($formVars['q31-2']))?$formVars['q31-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q31-3" value="<?php echo (isset($formVars['q31-3']))?$formVars['q31-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q31-4" value="<?php echo (isset($formVars['q31-4']))?$formVars['q31-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q31-5" value="<?php echo (isset($formVars['q31-5']))?$formVars['q31-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q31-6" value="<?php echo (isset($formVars['q31-6']))?$formVars['q31-6']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="r31"><?php markRemarkTextA('r31',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1004" name="s32" <?php markSelCheckBox('s32',$formVars); ?>><label class="form-check-label" for="formCheck-36">25</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">IV Infusion Set, without Leur lock</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1005" name="c32-1" <?php markCompCheckBox('c32-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1006" name="c32-2" <?php markCompCheckBox('c32-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">BD</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1007" name="c32-3" <?php markCompCheckBox('c32-3',$formVars); ?>><label class="form-check-label" for="formCheck-63">Braun</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q32" value="<?php echo (isset($formVars['q32']))?$formVars['q32']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r32" value="<?php echo (isset($formVars['r32']))?$formVars['r32']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1008" name="s33" <?php markSelCheckBox('s33',$formVars); ?>><label class="form-check-label" for="formCheck-36">26</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">JR Circuit (Jackson Rees) Pediatric</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1009" name="c33-1" <?php markCompCheckBox('c33-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">MediSafe</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1010" name="c33-2" <?php markCompCheckBox('c33-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">LifeLine</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q33" value="<?php echo (isset($formVars['q33']))?$formVars['q33']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r33" value="<?php echo (isset($formVars['r33']))?$formVars['r33']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1011" name="s34" <?php markSelCheckBox('s34',$formVars); ?>><label class="form-check-label" for="formCheck-36">27</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Microdrip set</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1012" name="c34-1" <?php markCompCheckBox('c34-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Baxter</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1013" name="c34-2" <?php markCompCheckBox('c34-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">B.Braun</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q34" value="<?php echo (isset($formVars['q34']))?$formVars['q34']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r34" value="<?php echo (isset($formVars['r34']))?$formVars['r34']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1014" name="s35" <?php markSelCheckBox('s35',$formVars); ?>><label class="form-check-label" for="formCheck-28">28</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Multifunctional Electrode Pads/AED Pads</label></div>
                        <div class="col-3"><label class="col-form-label">Supplied as a pair</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Philips</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q35" value="<?php echo (isset($formVars['q35']))?$formVars['q35']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r35" value="<?php echo (isset($formVars['r35']))?$formVars['r35']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1015" name="s36" <?php markSelCheckBox('s36',$formVars); ?>><label class="form-check-label" for="formCheck-36">29</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Multilumen Extension</label></div>
                        <div class="col-3"><label class="col-form-label">to be connected peripheral IV cannula</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1016" name="c36-1" <?php markCompCheckBox('c36-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Vygon</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1017" name="c36-2" <?php markCompCheckBox('c36-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">BD</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q36" value="<?php echo (isset($formVars['q36']))?$formVars['q36']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r36" value="<?php echo (isset($formVars['r36']))?$formVars['r36']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1018" name="s37" <?php markSelCheckBox('s37',$formVars); ?>><label class="form-check-label" for="formCheck-25">30</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">NIRS Sensor</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1019" name="d37-1" <?php markSelCheckBox('d37-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">Neonatal</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1020" name="d37-2" <?php markSelCheckBox('d37-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">Pediatric</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1021" name="d37-3" <?php markSelCheckBox('d37-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">Adult</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Covedein</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q37-1" value="<?php echo (isset($formVars['q37-1']))?$formVars['q37-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q37-2" value="<?php echo (isset($formVars['q37-2']))?$formVars['q37-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q37-3" value="<?php echo (isset($formVars['q37-3']))?$formVars['q37-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="r37"><?php markRemarkTextA('r37',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1022" name="s38" <?php markSelCheckBox('s38',$formVars); ?>><label class="form-check-label" for="formCheck-35">31</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">NIV mask</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1023" name="d38-1" <?php markSelCheckBox('d38-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">Small</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1024" name="d38-2" <?php markSelCheckBox('d38-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">Medium</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1025" name="d38-3" <?php markSelCheckBox('d38-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">Large&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1026" name="c38-1" <?php markCompCheckBox('c38-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Pneumocare</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1027" name="c38-2" <?php markCompCheckBox('c38-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Resmed</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q38-1" value="<?php echo (isset($formVars['q38-1']))?$formVars['q38-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q38-2" value="<?php echo (isset($formVars['q38-2']))?$formVars['q38-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q38-3"value="<?php echo (isset($formVars['q38-3']))?$formVars['q38-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="r38"><?php markRemarkTextA('r38',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1028" name="s39" <?php markSelCheckBox('s39',$formVars); ?>><label class="form-check-label" for="formCheck-28">32</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">NOX BOX circuit</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Bedfont(Scientific Ltd)</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q39" value="<?php echo (isset($formVars['q39']))?$formVars['q39']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r39" value="<?php echo (isset($formVars['r39']))?$formVars['r39']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1029" name="s40" <?php markSelCheckBox('s40',$formVars); ?>><label class="form-check-label" for="formCheck-36">33</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Pediatric Drip Set (Burette set) with leur lock</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1030" name="c40-1" <?php markCompCheckBox('c40-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Baxter</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1031" name="c40-2" <?php markCompCheckBox('c40-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">B.Braun</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1032" name="c40-3" <?php markCompCheckBox('c40-3',$formVars); ?>><label class="form-check-label" for="formCheck-63">Romson</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q40" value="<?php echo (isset($formVars['q40']))?$formVars['q40']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r40" value="<?php echo (isset($formVars['r40']))?$formVars['r40']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1033" name="s41" <?php markSelCheckBox('s41',$formVars); ?>><label class="form-check-label" for="formCheck-36">34</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Pediatric Drip Set (Burette set) without leur lock</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1034" name="c41-1" <?php markCompCheckBox('c41-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">Baxter</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1035" name="c41-2" <?php markCompCheckBox('c41-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">B.Braun</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1036" name="c41-3" <?php markCompCheckBox('c41-3',$formVars); ?>><label class="form-check-label" for="formCheck-63">Romson</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q41" value="<?php echo (isset($formVars['q41']))?$formVars['q41']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r41" value="<?php echo (isset($formVars['r41']))?$formVars['r41']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1037" name="s42" <?php markSelCheckBox('s42',$formVars); ?>><label class="form-check-label" for="formCheck-25">35</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Peripheral Venous cannula</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1038" name="d42-1" <?php markSelCheckBox('d42-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">14G</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1039" name="d42-2" <?php markSelCheckBox('d42-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">16G</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1040" name="d42-3" <?php markSelCheckBox('d42-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">18G</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1041" name="d42-4" <?php markSelCheckBox('d42-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">20G</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1042" name="d42-5" <?php markSelCheckBox('d42-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">22G</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1043" name="d42-6" <?php markSelCheckBox('d42-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">24G</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1044" name="d42-7" <?php markSelCheckBox('d42-7',$formVars); ?>><label class="form-check-label" for="formCheck-1">26G</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Becton Dickinson (BD)</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-1" value="<?php echo (isset($formVars['q42-1']))?$formVars['q42-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-2" value="<?php echo (isset($formVars['q42-2']))?$formVars['q42-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-3" value="<?php echo (isset($formVars['q42-3']))?$formVars['q42-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-4" value="<?php echo (isset($formVars['q42-4']))?$formVars['q42-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-5" value="<?php echo (isset($formVars['q42-5']))?$formVars['q42-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-6" value="<?php echo (isset($formVars['q42-6']))?$formVars['q42-6']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q42-7" value="<?php echo (isset($formVars['q42-7']))?$formVars['q42-7']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="5" name="r42"><?php markRemarkTextA('r42',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1045" name="s43" <?php markSelCheckBox('s43',$formVars); ?>><label class="form-check-label" for="formCheck-28">36</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Pressure Monitoring Kit with Dome, Double</label></div>
                        <div class="col-3"><label class="col-form-label">Compatible with our machine</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Edward Life Sciences</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q43" value="<?php echo (isset($formVars['q43']))?$formVars['q43']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="r43"><?php markRemarkTextA('r43',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1046" name="s44" <?php markSelCheckBox('s44',$formVars); ?>><label class="form-check-label" for="formCheck-28">37</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Pressure Monitoring Kit with Dome, Single</label></div>
                        <div class="col-3"><label class="col-form-label">Compatible with our machine</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Edward Life Sciences</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q44" value="<?php echo (isset($formVars['q44']))?$formVars['q44']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r44" value="<?php echo (isset($formVars['r44']))?$formVars['r44']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1047" name="s45" <?php markSelCheckBox('s45',$formVars); ?>><label class="form-check-label" for="formCheck-35">38</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">RAMS Cannula</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1048" name="d45-1" <?php markSelCheckBox('d45-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">Neonate</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1049" name="d45-2" <?php markSelCheckBox('d45-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">Pediatric</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1050" name="d45-3" <?php markSelCheckBox('d45-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">Adult&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1051" name="c45-1" <?php markCompCheckBox('c45-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1052" name="c45-2" <?php markCompCheckBox('c45-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Polymed</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q45-1" value="<?php echo (isset($formVars['q45-1']))?$formVars['q45-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q45-2" value="<?php echo (isset($formVars['q45-2']))?$formVars['q45-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q45-3" value="<?php echo (isset($formVars['q45-3']))?$formVars['q45-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r45" value="<?php echo (isset($formVars['r45']))?$formVars['r45']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1053" name="s46" <?php markSelCheckBox('s46',$formVars); ?>><label class="form-check-label" for="formCheck-35">39</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Ryles Tube</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1054" name="d46-1" <?php markSelCheckBox('d46-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1055" name="d46-2" <?php markSelCheckBox('d46-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1056" name="d46-3" <?php markSelCheckBox('d46-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label style="width:100%">Romson</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q46-1" value="<?php echo (isset($formVars['q46-1']))?$formVars['q46-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q46-2" value="<?php echo (isset($formVars['q46-2']))?$formVars['q46-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q46-3" value="<?php echo (isset($formVars['q46-3']))?$formVars['q46-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r46" value="<?php echo (isset($formVars['r46']))?$formVars['r46']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1057" name="s47" <?php markSelCheckBox('s47',$formVars); ?>><label class="form-check-label" for="formCheck-28">40</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">SIPAP circuit</label></div>
                        <div class="col-3"><label class="col-form-label">Neonate</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">SIPAP</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q47" value="<?php echo (isset($formVars['q47']))?$formVars['q47']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r47" value="<?php echo (isset($formVars['r47']))?$formVars['r47']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1058" name="s48" <?php markSelCheckBox('s48',$formVars); ?>><label class="form-check-label" for="formCheck-36">41</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Sticking Plaster(Leukoplast)</label></div>
                        <div class="col-3"><label class="col-form-label">4inch</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1059" name="c48-1" <?php markCompCheckBox('c48-1',$formVars); ?>><label class="form-check-label" for="formCheck-62">3M</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1060" name="c48-2" <?php markCompCheckBox('c48-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">Johnson&amp;Johnson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1061" name="c48-3" <?php markCompCheckBox('c48-3',$formVars); ?>><label class="form-check-label" for="formCheck-63">Romson</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q48" value="<?php echo (isset($formVars['q48']))?$formVars['q48']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r48" value="<?php echo (isset($formVars['r48']))?$formVars['r48']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1062" name="s49" <?php markSelCheckBox('s49',$formVars); ?>><label class="form-check-label" for="formCheck-35">42</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Suction Catheter with eye</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1063" name="d49-1" <?php markSelCheckBox('d49-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">6Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1064" name="d49-2" <?php markSelCheckBox('d49-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">8Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1065" name="d49-3" <?php markSelCheckBox('d49-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1066" name="d49-4" <?php markSelCheckBox('d49-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1067" name="d49-5" <?php markSelCheckBox('d49-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1068" name="d49-6" <?php markSelCheckBox('d49-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1069" name="c49-1" <?php markCompCheckBox('c49-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1070" name="c49-2" <?php markCompCheckBox('c49-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1071" name="c49-3" <?php markCompCheckBox('c49-3',$formVars); ?>><label class="form-check-label" for="formCheck-60">Top</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q49-1" value="<?php echo (isset($formVars['q49-1']))?$formVars['q49-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q49-2" value="<?php echo (isset($formVars['q49-2']))?$formVars['q49-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q49-3" value="<?php echo (isset($formVars['q49-3']))?$formVars['q49-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q49-4" value="<?php echo (isset($formVars['q49-4']))?$formVars['q49-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q49-5" value="<?php echo (isset($formVars['q49-5']))?$formVars['q49-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "q49-6" value="<?php echo (isset($formVars['q49-6']))?$formVars['q49-6']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="r49"><?php markRemarkTextA('r49',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1072" name="s50" <?php markSelCheckBox('s50',$formVars); ?>><label class="form-check-label" for="formCheck-35">43</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Suction Catheter without eye</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1073" name="d50-1" <?php markSelCheckBox('d50-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">6Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1074" name="d50-2" <?php markSelCheckBox('d50-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">8Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1075" name="d50-3" <?php markSelCheckBox('d50-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1076" name="d50-4" <?php markSelCheckBox('d50-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1077" name="d50-5" <?php markSelCheckBox('d50-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1078" name="d50-6" <?php markSelCheckBox('d50-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr&nbsp;</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1079" name="c50-1" <?php markCompCheckBox('c50-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Romson</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1080" name="c50-2" <?php markCompCheckBox('c50-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Portex</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1081" name="c50-3" <?php markCompCheckBox('c50-3',$formVars); ?>><label class="form-check-label" for="formCheck-60">Top</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q50-1"  value="<?php echo (isset($formVars['q50-1']))?$formVars['q50-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q50-2" value="<?php echo (isset($formVars['q50-2']))?$formVars['q50-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q50-3" value="<?php echo (isset($formVars['q50-3']))?$formVars['q50-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q50-4" value="<?php echo (isset($formVars['q50-4']))?$formVars['q50-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q50-5" value="<?php echo (isset($formVars['q50-5']))?$formVars['q50-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q50-6"  value="<?php echo (isset($formVars['q50-6']))?$formVars['q50-6']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="r50"><?php markRemarkTextA('r50',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1082" name="s51" <?php markSelCheckBox('s51',$formVars); ?>><label class="form-check-label" for="formCheck-35">44</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Three Way with extension</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1083" name="d51-1" <?php markSelCheckBox('d51-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">10cm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1084" name="d51-2" <?php markSelCheckBox('d51-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">100cm</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1085" name="c51-1" <?php markCompCheckBox('c51-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">BD</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1086" name="c51-2" <?php markCompCheckBox('c51-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Vygon</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1087" name="c51-3" <?php markCompCheckBox('c51-3',$formVars); ?>><label class="form-check-label" for="formCheck-60">Top</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q51-1" value="<?php echo (isset($formVars['q51-1']))?$formVars['q51-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q51-2" value="<?php echo (isset($formVars['q51-2']))?$formVars['q51-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r51" value="<?php echo (isset($formVars['r51']))?$formVars['r51']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1088" name="s52" <?php markSelCheckBox('s52',$formVars); ?>><label class="form-check-label" for="formCheck-35">45</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Three Way without extension</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1089" name="d52-1" <?php markSelCheckBox('d52-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">10cm</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1090" name="d52-2" <?php markSelCheckBox('d52-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">100cm</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1091" name="c52-1" <?php markCompCheckBox('c52-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">BD</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1092" name="c52-2" <?php markCompCheckBox('c52-2',$formVars); ?>><label class="form-check-label" for="formCheck-60">Vygon</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q52-1" value="<?php echo (isset($formVars['q52-1']))?$formVars['q52-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "q52-2" value="<?php echo (isset($formVars['q52-2']))?$formVars['q52-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="r52" value="<?php echo (isset($formVars['r52']))?$formVars['r52']:''; ?>"></div>
                    </div>
                </div>
                <div class="container">
                    <div>
                        <p></p>
                        <p>You have reached end of section-A. Move to next Tab for next section.&nbsp;</p>
                        <p><strong><em>Changes are saved automatically.</em></strong></p>
                        <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                            <div class="container"><a class="navbar-brand text-info" href="#" style="font-size: 18px;"><span style="text-decoration: underline;">Jump to Top of this Page</span></a><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="tab-pane" role="tabpanel" id="tab-2">
                <p></p>
                <h2 class="text-left" style="font-size: 26;">&nbsp; &nbsp;Cardio Pulmonary Bypass Items</h2>
                <p>&nbsp; &nbsp; &nbsp; Mark the<strong>&nbsp;Items</strong> Required and go to next Tab for next section.</p>
                <div class="container">
                    <div class="row">
                        <div class="col-1"><label class="col-form-label"><strong>Sr.No</strong></label></div>
                        <div class="col-2"><label class="col-form-label"><strong>Item Name</strong></label></div>
                        <div class="col-3"><label class="col-form-label"><strong>Description</strong></label></div>
                        <div class="col-3"><label class="col-form-label"><strong>Company/Brand Name Preferred&nbsp;</strong></label></div>
                        <div class="col-1"><label class="col-form-label"><strong>Qty Required&nbsp;</strong></label></div>
                        <div class="col-2"><label class="col-form-label"><strong>Remarks</strong><br></label></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1" name="sb1" <?php markSelCheckBox('sb1',$formVars); ?>><label class="form-check-label" for="formCheck-24">1</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Antegrade cardioplegia Cannula, with vent</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-2" name="db1-1" <?php markSelCheckBox('db1-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">14G (Code: 20014)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-3" name="db1-2" <?php markSelCheckBox('db1-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">16G (Code: 20016)</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-4" name="cb1-1" <?php markCompCheckBox('cb1-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-5" name="cb1-2" <?php markCompCheckBox('cb1-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Edward</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-6" name="cb1-3" <?php markCompCheckBox('cb1-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Sarns</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb1-1" value="<?php echo (isset($formVars['qb1-1']))?$formVars['qb1-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb1-2"value="<?php echo (isset($formVars['qb1-2']))?$formVars['qb1-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb1"><?php markRemarkTextA('rb1',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-7" name="sb2" <?php markSelCheckBox('sb2',$formVars); ?>><label class="form-check-label" for="formCheck-24">2A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Aortic perfusion cannula, wire reinforced, Angled </label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-8" name="db2-1" <?php markSelCheckBox('db2-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-9" name="db2-2" <?php markSelCheckBox('db2-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">22Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-10" name="cb2-1" <?php markCompCheckBox('cb2-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-11" name="cb2-2" <?php markCompCheckBox('cb2-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Edward</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-12" name="cb2-3" <?php markCompCheckBox('cb2-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Sarns</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb2-1" value="<?php echo (isset($formVars['qb2-1']))?$formVars['qb2-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb2-2" value="<?php echo (isset($formVars['qb2-2']))?$formVars['qb2-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb2"><?php markRemarkTextA('rb2',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-13" name="sb3" <?php markSelCheckBox('sb3',$formVars); ?>><label class="form-check-label" for="formCheck-24">2B</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Aortic perfusion cannula, wire reinforced, Straight </label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-14" name="db3-1" <?php markSelCheckBox('db3-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">6Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-15" name="db3-2" <?php markSelCheckBox('db3-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">8Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-16" name="db3-3" <?php markSelCheckBox('db3-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-17" name="db3-4" <?php markSelCheckBox('db3-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-18" name="db3-5" <?php markSelCheckBox('db3-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-19" name="db3-6" <?php markSelCheckBox('db3-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-20" name="db3-7" <?php markSelCheckBox('db3-7',$formVars); ?>><label class="form-check-label" for="formCheck-1">18Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-21" name="db3-8" <?php markSelCheckBox('db3-8',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-22" name="cb3-1" <?php markCompCheckBox('cb3-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Medtronic(BioMedicus)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-23" name="cb3-2" <?php markCompCheckBox('cb3-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Edward</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-24" name="cb3-3" <?php markCompCheckBox('cb3-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Sarns</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-1" value="<?php echo (isset($formVars['qb3-1']))?$formVars['qb3-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-2" value="<?php echo (isset($formVars['qb3-2']))?$formVars['qb3-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-3" value="<?php echo (isset($formVars['qb3-3']))?$formVars['qb3-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-4" value="<?php echo (isset($formVars['qb3-4']))?$formVars['qb3-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-5" value="<?php echo (isset($formVars['qb3-5']))?$formVars['qb3-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-6" value="<?php echo (isset($formVars['qb3-6']))?$formVars['qb3-6']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-7" value="<?php echo (isset($formVars['qb3-7']))?$formVars['qb3-7']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb3-8" value="<?php echo (isset($formVars['qb3-8']))?$formVars['qb3-8']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="6" name="rb3"><?php markRemarkTextA('rb3',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-25" name="sb4" <?php markSelCheckBox('sb4',$formVars); ?>><label class="form-check-label" for="formCheck-26">3A</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Autovent cum Filter,Adult</label></div>
                        <div class="col-3"><label class="col-form-label">for adult CPB circuit</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-26" name="cb4-1" <?php markCompCheckBox('cb4-1',$formVars); ?>><label class="form-check-label" for="formCheck-39">BL Life sciences&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-27" name="cb4-2" <?php markCompCheckBox('cb4-2',$formVars); ?>><label class="form-check-label" for="formCheck-40">Dideco</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-28" name="cb4-3" <?php markCompCheckBox('cb4-3',$formVars); ?>><label class="form-check-label" for="formCheck-41">LifeLine</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb4" value="<?php echo (isset($formVars['qb4']))?$formVars['qb4']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rb4"><?php markRemarkTextA('rb4',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-29" name="sb5" <?php markSelCheckBox('sb5',$formVars); ?>><label class="form-check-label" for="formCheck-26">3B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Autovent cum Filter,Pediatric</label></div>
                        <div class="col-3"><label class="col-form-label">for pediatric CPB circuit</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-30" name="cb5-1" <?php markCompCheckBox('cb5-1',$formVars); ?>><label class="form-check-label" for="formCheck-39">BL Life sciences&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-31" name="cb5-2" <?php markCompCheckBox('cb5-2',$formVars); ?>><label class="form-check-label" for="formCheck-40">Dideco</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-32" name="cb5-3" <?php markCompCheckBox('cb5-3',$formVars); ?>><label class="form-check-label" for="formCheck-41">LifeLine</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb5" value="<?php echo (isset($formVars['qb5']))?$formVars['qb5']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rb5"><?php markRemarkTextA('rb5',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-33" name="sb6" <?php markSelCheckBox('sb6',$formVars); ?>><label class="form-check-label" for="formCheck-26">3C</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Autovent cum Filter, Neonate and infant</label></div>
                        <div class="col-3"><label class="col-form-label">Neonate or infant for 3-10kg</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-34" name="cb6-1" <?php markCompCheckBox('cb6-1',$formVars); ?>><label class="form-check-label" for="formCheck-39">BL Life sciences&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-35" name="cb6-2" <?php markCompCheckBox('cb6-2',$formVars); ?>><label class="form-check-label" for="formCheck-40">Dideco</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-36" name="cb6-3" <?php markCompCheckBox('cb6-3',$formVars); ?>><label class="form-check-label" for="formCheck-41">LifeLine</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-37" name="cb6-4" <?php markCompCheckBox('cb6-4',$formVars); ?>><label class="form-check-label" for="formCheck-41">Medtronic&nbsp;</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb6" value="<?php echo (isset($formVars['qb6']))?$formVars['qb6']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rb6"><?php markRemarkTextA('rb6',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-38" name="sb7" <?php markSelCheckBox('sb7',$formVars); ?>><label class="form-check-label" for="formCheck-28">3D</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Autovent cum Filter, Neonate&nbsp;<br></label></div>
                        <div class="col-3"><label class="col-form-label">Neonate baby with &lt; 3kg</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">Pall</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"  name = "qb7" value="<?php echo (isset($formVars['qb7']))?$formVars['qb7']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb7" value="<?php echo (isset($formVars['rb7']))?$formVars['rb7']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-39" name="sb8" <?php markSelCheckBox('sb8',$formVars); ?>><label class="form-check-label" for="formCheck-27">4</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Blower/Mister</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-40" name="cb8-1" <?php markCompCheckBox('cb8-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-41" name="cb8-2" <?php markCompCheckBox('cb8-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Guidant</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb8" value="<?php echo (isset($formVars['qb8']))?$formVars['qb8']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb8" value="<?php echo (isset($formVars['rb8']))?$formVars['rb8']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-42" name="sb9" <?php markSelCheckBox('sb9',$formVars); ?>><label class="form-check-label" for="formCheck-27">5A</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiovascular Patch, ePTFE</label></div>
                        <div class="col-3"><label class="col-form-label">PTFE, W 50mm, L 75mm, thickness 0.6mm</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-43" name="cb9-1" <?php markCompCheckBox('cb9-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Bard</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-44" name="cb9-2" <?php markCompCheckBox('cb9-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Goretex</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb9" value="<?php echo (isset($formVars['qb9']))?$formVars['qb9']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb9" value="<?php echo (isset($formVars['rb9']))?$formVars['rb9']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-45" name="sb10" <?php markSelCheckBox('sb10',$formVars); ?>><label class="form-check-label" for="formCheck-27">5B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiovascular Patch, ePTFE membrane</label></div>
                        <div class="col-3"><label class="col-form-label">PTFE, W 60mm, L 120mm, thickness 0.1mm</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-46" name="cb10-1" <?php markCompCheckBox('cb10-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Bard</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-47" name="cb10-2" <?php markCompCheckBox('cb10-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Goretex</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb10" value="<?php echo (isset($formVars['qb10']))?$formVars['qb10']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb10" value="<?php echo (isset($formVars['rb10']))?$formVars['rb10']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-48" name="sb11" <?php markSelCheckBox('sb11',$formVars); ?>><label class="form-check-label" for="formCheck-27">5C</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiovascular Patch, Dacron</label></div>
                        <div class="col-3"><label class="col-form-label">Dacron</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-49" name="cb11-1" <?php markCompCheckBox('cb11-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Bard</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-50" name="cb11-2" <?php markCompCheckBox('cb11-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Goretex</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb11" value="<?php echo (isset($formVars['qb11']))?$formVars['qb11']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb11" value="<?php echo (isset($formVars['rb11']))?$formVars['rb11']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-51" name="sb12" <?php markSelCheckBox('sb12',$formVars); ?>><label class="form-check-label" for="formCheck-28">5D</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiovascular Patch, Pericardial, Bovine&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">Bovine Pericardium, 9cmx14cm, thickness: 0.2-0.4mm</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">SJM</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb12" value="<?php echo (isset($formVars['qb12']))?$formVars['qb12']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb12 value="<?php echo (isset($formVars['rb12']))?$formVars['rb12']:''; ?>""></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-52" name="sb13" <?php markSelCheckBox('sb13',$formVars); ?>><label class="form-check-label" for="formCheck-28">5E</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiovascular Patch, Pericardial, Bovine&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">Bovine Pericardium</label></div>
                        <div class="col-3">
                            <div></div><label class="col-form-label" style="width:100%">SynkroMax</label></div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb13" name = "qb13" value="<?php echo (isset($formVars['qb13']))?$formVars['qb13']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb13" value="<?php echo (isset($formVars['rb13']))?$formVars['rb13']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-53" name="sb14" <?php markSelCheckBox('sb14',$formVars); ?>><label class="form-check-label" for="formCheck-27">6</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiovascular Felt</label></div>
                        <div class="col-3"><label class="col-form-label">Soft 6inchx6inch</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-54" name="cb14-1" <?php markCompCheckBox('cb14-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Bard</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-55" name="cb14-2" <?php markCompCheckBox('cb14-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Goretex</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb14" value="<?php echo (isset($formVars['qb14']))?$formVars['qb14']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb14" value="<?php echo (isset($formVars['rb14']))?$formVars['rb14']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-56" name="sb15" <?php markSelCheckBox('sb15',$formVars); ?>><label class="form-check-label" for="formCheck-27">7A</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiac Tissue Stabilizer, Octupus Evolution&nbsp;</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-57" name="cb15-1" <?php markCompCheckBox('cb15-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-58" name="cb15-2" <?php markCompCheckBox('cb15-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Guidant</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb15" value="<?php echo (isset($formVars['qb15']))?$formVars['qb15']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb15" value="<?php echo (isset($formVars['rb15']))?$formVars['rb15']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-59" name="sb16" <?php markSelCheckBox('sb16',$formVars); ?>><label class="form-check-label" for="formCheck-27">7B</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiac Tissue Stabilizer, Starfish</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-60" name="cb16-1" <?php markCompCheckBox('cb16-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-61" name="cb16-2" <?php markCompCheckBox('cb16-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Guidant</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb16" value="<?php echo (isset($formVars['qb16']))?$formVars['qb16']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb16" value="<?php echo (isset($formVars['rb16']))?$formVars['rb16']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-62" name="sb17" <?php markSelCheckBox('sb17',$formVars); ?>><label class="form-check-label" for="formCheck-27">7C</label></div>
                        </div>
                        <div class="col-2"><label class="col-form-label">Cardiac Tissue Stabilizer, Urchin</label></div>
                        <div class="col-3"><label class="col-form-label">-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-63" name="cb17-1" <?php markCompCheckBox('cb17-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-64" name="cb17-2" <?php markCompCheckBox('cb17-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Guidant</label></div>
                        </div>
                        <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb17" value="<?php echo (isset($formVars['qb17']))?$formVars['qb17']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb17" value="<?php echo (isset($formVars['rb17']))?$formVars['rb17']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-65" name="sb18" <?php markSelCheckBox('sb18',$formVars); ?>><label class="form-check-label" for="formCheck-29">8</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Cell Saver Kit</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-66" name="db18-1" <?php markSelCheckBox('db18-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">125cc</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-67" name="db18-2" <?php markSelCheckBox('db18-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">225cc</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label style="width:100%">Hemonetics</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb18-1" value="<?php echo (isset($formVars['qb18-1']))?$formVars['qb18-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb18-2" value="<?php echo (isset($formVars['qb18-2']))?$formVars['qb18-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb18" value="<?php echo (isset($formVars['rb18']))?$formVars['rb18']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-68" name="sb19" <?php markSelCheckBox('sb19',$formVars); ?>><label class="form-check-label" for="formCheck-29">9A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Coronary artery ostial cardioplegia cannula, basket type, basket tip 45degree angle</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-69" name="db19-1" <?php markSelCheckBox('db19-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-70" name="db19-2" <?php markSelCheckBox('db19-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-71" name="db19-3" <?php markSelCheckBox('db19-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-72" name="cb19-1" <?php markCompCheckBox('cb19-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-73" name="cb19-2" <?php markCompCheckBox('cb19-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Sarns</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb19-1" value="<?php echo (isset($formVars['qb19-1']))?$formVars['qb19-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb19-2" value="<?php echo (isset($formVars['qb19-2']))?$formVars['qb19-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb19-3" value="<?php echo (isset($formVars['qb19-3']))?$formVars['qb19-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb19"><?php markRemarkTextA('rb19',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-74" name="sb20" <?php markSelCheckBox('sb20',$formVars); ?>><label class="form-check-label" for="formCheck-29">9B</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Coronary artery ostial cardioplegia cannula, flexible silicon tip</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-75" name="db20-1" <?php markSelCheckBox('db20-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">15Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-76" name="db20-2" <?php markSelCheckBox('db20-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">17Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-77" name="db20-3" <?php markSelCheckBox('db20-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-78" name="cb20-1" <?php markCompCheckBox('cb20-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-79" name="cb20-2" <?php markCompCheckBox('cb20-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Sarns</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-80" name="cb20-3" <?php markCompCheckBox('cb20-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Edward</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb20-1" value="<?php echo (isset($formVars['qb20-1']))?$formVars['qb20-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb20-2" value="<?php echo (isset($formVars['qb20-2']))?$formVars['qb20-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb20-3" value="<?php echo (isset($formVars['qb20-3']))?$formVars['qb20-3']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb20"><?php markRemarkTextA('rb20',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-81" name="sb21" <?php markSelCheckBox('sb21',$formVars); ?>><label class="form-check-label" for="formCheck-29">9C</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">Coronary artery ostial cardioplegia cannula, for pediatric, Atriotomy cannula</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-82" name="db21-1" <?php markSelCheckBox('db21-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">2mm&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-83" name="db21-2" <?php markSelCheckBox('db21-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">3mm</label></div>
                        </div>
                        <div class="col-3">
                            <div></div><label style="width:100%">Medtronic</label></div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb21-1" value="<?php echo (isset($formVars['qb21-1']))?$formVars['qb21-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb21-2" value="<?php echo (isset($formVars['qb21-2']))?$formVars['qb21-2']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="rb21"><?php markRemarkTextA('rb21',$formVars); ?></textarea></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-84" name="sb22" <?php markSelCheckBox('sb22',$formVars); ?>><label class="form-check-label" for="formCheck-29">10A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">CPB Custom tubing pack, Adult</label></div></div>
                        <div class="col-3">
                            <div></div><label>-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-85" name="cb22-1" <?php markCompCheckBox('cb22-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">BL Lifesciences</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-86" name="cb22-2" <?php markCompCheckBox('cb22-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">LifeLine</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-87" name="cb22-3" <?php markCompCheckBox('cb22-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb22" value="<?php echo (isset($formVars['qb22']))?$formVars['qb22']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb22" value="<?php echo (isset($formVars['rb22']))?$formVars['rb22']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-88" name="sb23" <?php markSelCheckBox('sb23',$formVars); ?>><label class="form-check-label" for="formCheck-29">10B</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">CPB Custom tubing pack, Pediatric</label></div></div>
                        <div class="col-3">
                            <div></div><label>-</label></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-89" name="cb23-1" <?php markCompCheckBox('cb23-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">BL Lifesciences</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-90" name="cb23-2" <?php markCompCheckBox('cb23-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">LifeLine</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-91" name="cb23-3" <?php markCompCheckBox('cb23-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb23" value="<?php echo (isset($formVars['qb23']))?$formVars['qb23']:0; ?>" /></div></div>
                        <div class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb23" value="<?php echo (isset($formVars['rb23']))?$formVars['rb23']:''; ?>"></div>
                    </div>
                    <div class="row mt-3" style="border-style: dotted;">
                        <div class="col-1">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-92" name="sb24" <?php markSelCheckBox('sb24',$formVars); ?>><label class="form-check-label" for="formCheck-29">11A</label></div>
                        </div>
                        <div class="col-2"><div><label width="100%" class="col-form-label">CPB, Peripheral, Femoral Arterial cannula</label></div></div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-93" name="db24-1" <?php markSelCheckBox('db24-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-94" name="db24-2" <?php markSelCheckBox('db24-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-95" name="db24-3" <?php markSelCheckBox('db24-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-96" name="db24-4" <?php markSelCheckBox('db24-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">17Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-97" name="db24-5" <?php markSelCheckBox('db24-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">18Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-98" name="db24-6" <?php markSelCheckBox('db24-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">19Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-99" name="db24-7" <?php markSelCheckBox('db24-7',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-100" name="db24-8" <?php markSelCheckBox('db24-8',$formVars); ?>><label class="form-check-label" for="formCheck-1">21Fr&nbsp;</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-101" name="db24-9" <?php markSelCheckBox('db24-9',$formVars); ?>><label class="form-check-label" for="formCheck-1">22Fr</label></div>
                        </div>
                        <div class="col-3">
                            <div></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-102" name="cb24-1" <?php markCompCheckBox('cb24-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Edward LifeSciences</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-103" name="cb24-2" <?php markCompCheckBox('cb24-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-104" name="cb24-3" <?php markCompCheckBox('cb24-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Maquet</label></div>
                        </div>
                        <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-1" value="<?php echo (isset($formVars['qb24-1']))?$formVars['qb24-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-2" value="<?php echo (isset($formVars['qb24-2']))?$formVars['qb24-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-3" value="<?php echo (isset($formVars['qb24-3']))?$formVars['qb24-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-4" value="<?php echo (isset($formVars['qb24-4']))?$formVars['qb24-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-5" value="<?php echo (isset($formVars['qb24-5']))?$formVars['qb24-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-6" value="<?php echo (isset($formVars['qb24-6']))?$formVars['qb24-6']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-7" value="<?php echo (isset($formVars['qb24-7']))?$formVars['qb24-7']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-8" value="<?php echo (isset($formVars['qb24-8']))?$formVars['qb24-8']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb24-9" value="<?php echo (isset($formVars['qb24-9']))?$formVars['qb24-9']:0; ?>" /></div></div>
                        <div
                            class="col-2" style="margin-top: 5px;">
                            <div style="overflow:hidden"></div><textarea style="width:100%" rows="7" name="rb24"><?php markRemarkTextA('rb24',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-105" name="sb25" <?php markSelCheckBox('sb25',$formVars); ?>><label class="form-check-label" for="formCheck-29">11B</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">CPB, Peripheral, Femoral Venuos cannula</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-106" name="db25-1" <?php markSelCheckBox('db25-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">15Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-107" name="db25-2" <?php markSelCheckBox('db25-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">17Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-108" name="db25-3" <?php markSelCheckBox('db25-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">24Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-109" name="db25-4" <?php markSelCheckBox('db25-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">23Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-110" name="db25-5" <?php markSelCheckBox('db25-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">18Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-111" name="db25-6" <?php markSelCheckBox('db25-6',$formVars); ?>><label class="form-check-label" for="formCheck-1">19Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-112" name="db25-7" <?php markSelCheckBox('db25-7',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-113" name="db25-8" <?php markSelCheckBox('db25-8',$formVars); ?>><label class="form-check-label" for="formCheck-1">21Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-114" name="db25-9" <?php markSelCheckBox('db25-9',$formVars); ?>><label class="form-check-label" for="formCheck-1">22Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-115" name="cb25-1" <?php markCompCheckBox('cb25-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-116" name="cb25-2" <?php markCompCheckBox('cb25-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-117" name="cb25-3" <?php markCompCheckBox('cb25-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Maquet</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-1" value="<?php echo (isset($formVars['qb25-1']))?$formVars['qb25-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-2" value="<?php echo (isset($formVars['qb25-2']))?$formVars['qb25-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-3" value="<?php echo (isset($formVars['qb25-3']))?$formVars['qb25-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-4" value="<?php echo (isset($formVars['qb25-4']))?$formVars['qb25-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-5" value="<?php echo (isset($formVars['qb25-5']))?$formVars['qb25-5']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-6" value="<?php echo (isset($formVars['qb25-6']))?$formVars['qb25-6']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-7" value="<?php echo (isset($formVars['qb25-7']))?$formVars['qb25-7']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-8" value="<?php echo (isset($formVars['qb25-8']))?$formVars['qb25-8']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb25-9" value="<?php echo (isset($formVars['qb25-9']))?$formVars['qb25-9']:0; ?>" /></div></div>
                    <div class="col-2"
                        style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="7" name="rb25"><?php markRemarkTextA('rb25',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-118" name="sb26" <?php markSelCheckBox('sb26',$formVars); ?>><label class="form-check-label" for="formCheck-29">11C</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">CPB, Peripheral cannula with insertion kit</label></div></div>
                    <div class="col-3">
                        <div></div><label>-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-119" name="cb26-1" <?php markCompCheckBox('cb26-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-120" name="cb26-2" <?php markCompCheckBox('cb26-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-121" name="cb26-3" <?php markCompCheckBox('cb26-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Maquet&nbsp;</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb26-1" value="<?php echo (isset($formVars['qb26-1']))?$formVars['qb26-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb26-2" value="<?php echo (isset($formVars['qb26-2']))?$formVars['qb26-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb26" value="<?php echo (isset($formVars['rb26']))?$formVars['rb26']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-122" name="sb27" <?php markSelCheckBox('sb27',$formVars); ?>><label class="form-check-label" for="formCheck-29">12</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Femoral arterial sheath</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-123" name="db27-1" <?php markSelCheckBox('db27-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">3Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-124" name="db27-2" <?php markSelCheckBox('db27-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">4Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-125" name="db27-3" <?php markSelCheckBox('db27-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">5Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-126" name="db27-4" <?php markSelCheckBox('db27-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">6Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-127" name="cb27-1" <?php markCompCheckBox('cb27-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Arrow</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-128" name="cb27-2" <?php markCompCheckBox('cb27-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">J&amp;J</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-129" name="cb27-3" <?php markCompCheckBox('cb27-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-130" name="cb27-4" <?php markCompCheckBox('cb27-4',$formVars); ?>><label class="form-check-label" for="formCheck-45">Vygon</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb27-1" value="<?php echo (isset($formVars['qb27-1']))?$formVars['qb27-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb27-2" value="<?php echo (isset($formVars['qb27-2']))?$formVars['qb27-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb27-3" value="<?php echo (isset($formVars['qb27-3']))?$formVars['qb27-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb27-4" value="<?php echo (isset($formVars['qb27-4']))?$formVars['qb27-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb27"><?php markRemarkTextA('rb27',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-131" name="sb28" <?php markSelCheckBox('sb28',$formVars); ?>><label class="form-check-label" for="formCheck-30">13A</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemofilter, Adult</label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-132" name="cb28-1" <?php markCompCheckBox('cb28-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-133" name="cb28-2" <?php markCompCheckBox('cb28-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Nipro</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-134" name="cb28-3" <?php markCompCheckBox('cb28-3',$formVars); ?>><label class="form-check-label" for="formCheck-48">Sorin</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb28" value="<?php echo (isset($formVars['qb28']))?$formVars['qb28']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb28" value="<?php echo (isset($formVars['rb28']))?$formVars['rb28']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-135" name="sb29" <?php markSelCheckBox('sb29',$formVars); ?>><label class="form-check-label" for="formCheck-30">13B</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemofilter, Pediatric</label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-136" name="cb29-1" <?php markCompCheckBox('cb29-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-137" name="cb29-2" <?php markCompCheckBox('cb29-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Nipro</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-138" name="cb29-3" <?php markCompCheckBox('cb29-3',$formVars); ?>><label class="form-check-label" for="formCheck-48">Sorin</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb29" value="<?php echo (isset($formVars['qb29']))?$formVars['qb29']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb29" value="<?php echo (isset($formVars['rb29']))?$formVars['rb29']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-139" name="sb30" <?php markSelCheckBox('sb30',$formVars); ?>><label class="form-check-label" for="formCheck-30">13C</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemofilter, Neonate&amp;Infant</label></div>
                    <div class="col-3"><label class="col-form-label">Neonate and infant for 3-10kg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-140" name="cb30-1" <?php markCompCheckBox('cb30-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-141" name="cb30-2" <?php markCompCheckBox('cb30-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Nipro</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-142" name="cb30-3" <?php markCompCheckBox('cb30-3',$formVars); ?>><label class="form-check-label" for="formCheck-48">Sorin</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb30" value="<?php echo (isset($formVars['qb30']))?$formVars['qb30']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb30" value="<?php echo (isset($formVars['rb30']))?$formVars['rb30']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-143" name="sb31" <?php markSelCheckBox('sb31',$formVars); ?>><label class="form-check-label" for="formCheck-30">13D</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemofilter, Neonate</label></div>
                    <div class="col-3"><label class="col-form-label">Neonate for &lt;3kg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-144" name="cb31-1" <?php markCompCheckBox('cb31-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-145" name="cb31-2" <?php markCompCheckBox('cb31-2',$formVars); ?>><label class="form-check-label" for="formCheck-48">Sorin</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb31" value="<?php echo (isset($formVars['qb31']))?$formVars['qb31']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb31" value="<?php echo (isset($formVars['rb31']))?$formVars['rb31']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-146" name="sb32" <?php markSelCheckBox('sb32',$formVars); ?>><label class="form-check-label" for="formCheck-29">14</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Intra Cardiac sump</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-147" name="db32-1" <?php markSelCheckBox('db32-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-148" name="db32-2" <?php markSelCheckBox('db32-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-149" name="cb32-1" <?php markCompCheckBox('cb32-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-150" name="cb32-2" <?php markCompCheckBox('cb32-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb32-1" value="<?php echo (isset($formVars['qb32-1']))?$formVars['qb32-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb32-2" value="<?php echo (isset($formVars['qb32-2']))?$formVars['qb32-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb32" value="<?php echo (isset($formVars['rb32']))?$formVars['rb32']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-151" name="sb33" <?php markSelCheckBox('sb33',$formVars); ?>><label class="form-check-label" for="formCheck-29">15</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Intra Aortic ballon pump Catheter, Ballon size-</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-152" name="db33-1" <?php markSelCheckBox('db33-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">34cc&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-153" name="db33-2" <?php markSelCheckBox('db33-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">40cc</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-154" name="cb33-1" <?php markCompCheckBox('cb33-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Arrow</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-155" name="cb33-2" <?php markCompCheckBox('cb33-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Maquet</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb33-1" value="<?php echo (isset($formVars['qb33-1']))?$formVars['qb33-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb33-2" value="<?php echo (isset($formVars['qb33-2']))?$formVars['qb33-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb33" value="<?php echo (isset($formVars['rb33']))?$formVars['rb33']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-156" name="sb34" <?php markSelCheckBox('sb34',$formVars); ?>><label class="form-check-label" for="formCheck-29">16</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Intra Coronary Shunt</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-157" name="db34-1" <?php markSelCheckBox('db34-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">1mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-158" name="db34-2" <?php markSelCheckBox('db34-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">1.25mm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-159" name="db34-3" <?php markSelCheckBox('db34-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">1.5mm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-160" name="db34-4" <?php markSelCheckBox('db34-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">1.75mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-161" name="db34-5" <?php markSelCheckBox('db34-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">2mm</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-162" name="cb34-1" <?php markCompCheckBox('cb34-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">Medtronic</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-163" name="cb34-2" <?php markCompCheckBox('cb34-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Baxter</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-164" name="cb34-3" <?php markCompCheckBox('cb34-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Chase</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb34-1" value="<?php echo (isset($formVars['qb34-1']))?$formVars['qb34-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb34-2" value="<?php echo (isset($formVars['qb34-2']))?$formVars['qb34-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb34-3" value="<?php echo (isset($formVars['qb34-3']))?$formVars['qb34-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb34-4" value="<?php echo (isset($formVars['qb34-4']))?$formVars['qb34-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb34-5" value="<?php echo (isset($formVars['qb34-5']))?$formVars['qb34-5']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="rb34"><?php markRemarkTextA('rb34',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-165" name="sb35" <?php markSelCheckBox('sb35',$formVars); ?>><label class="form-check-label" for="formCheck-30">17A</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Membrane oxygenator,Adult</label></div>
                    <div class="col-3"><label class="col-form-label">for &gt;30kg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-166" name="cb35-1" <?php markCompCheckBox('cb35-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">Medtronic</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-167" name="cb35-2" <?php markCompCheckBox('cb35-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Maquet</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-168" name="cb35-3" <?php markCompCheckBox('cb35-3',$formVars); ?>><label class="form-check-label" for="formCheck-48">Nipro</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-169" name="cb35-4" <?php markCompCheckBox('cb35-4',$formVars); ?>><label class="form-check-label" for="formCheck-48">Sorin</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb35" value="<?php echo (isset($formVars['qb35']))?$formVars['qb35']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb35" value="<?php echo (isset($formVars['rb35']))?$formVars['rb35']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-170" name="sb36" <?php markSelCheckBox('sb36',$formVars); ?>><label class="form-check-label" for="formCheck-30">17B</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Membrane oxygenator,Pediatric</label></div>
                    <div class="col-3"><label class="col-form-label">for 10-30kg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-171" name="cb36-1" <?php markCompCheckBox('cb36-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-172" name="cb36-2" <?php markCompCheckBox('cb36-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Maquet</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb36" value="<?php echo (isset($formVars['qb36']))?$formVars['qb36']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb36" value="<?php echo (isset($formVars['rb36']))?$formVars['rb36']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-173" name="sb37" <?php markSelCheckBox('sb37',$formVars); ?>><label class="form-check-label" for="formCheck-30">17C</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label" style="width:100%">Membrane oxygenator, neonate &amp; infant</label></div>
                    <div class="col-3"><label class="col-form-label">for 3-10kg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-174" name="cb37-1" <?php markCompCheckBox('cb37-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">Medos(Infant)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-175" name="cb37-2" <?php markCompCheckBox('cb37-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Macquet</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb37" value="<?php echo (isset($formVars['qb37']))?$formVars['qb37']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb37" value="<?php echo (isset($formVars['rb37']))?$formVars['rb37']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-176" name="sb38" <?php markSelCheckBox('sb38',$formVars); ?>><label class="form-check-label" for="formCheck-30">17D</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Membrane oxygenator, Neonate</label></div>
                    <div class="col-3"><label class="col-form-label">for &lt; 3kg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-177" name="cb38-1" <?php markCompCheckBox('cb38-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">Medos (Infant)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-178" name="cb38-2" <?php markCompCheckBox('cb38-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Sorin(Kids 100)</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb38" value="<?php echo (isset($formVars['qb38']))?$formVars['qb38']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb38" value="<?php echo (isset($formVars['rb38']))?$formVars['rb38']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-179" name="sb39" <?php markSelCheckBox('sb39',$formVars); ?>><label class="form-check-label" for="formCheck-30">18A</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Multiple Perfusion Set with vessel cannula</label></div>
                    <div class="col-3"><label class="col-form-label">with 4 channel</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-180" name="cb39-1" <?php markCompCheckBox('cb39-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-181" name="cb39-2" <?php markCompCheckBox('cb39-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb39" value="<?php echo (isset($formVars['qb39']))?$formVars['qb39']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb39" value="<?php echo (isset($formVars['rb39']))?$formVars['rb39']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-182" name="sb40" <?php markSelCheckBox('sb40',$formVars); ?>><label class="form-check-label" for="formCheck-30">18B</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Multiple Perfusion Set without vessel cannula</label></div>
                    <div class="col-3"><label class="col-form-label">with 4 channel</label></div>
                    <div class="col-3">
                    <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-183" name="cb40-1" <?php markCompCheckBox('cb40-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-184" name="cb40-2" <?php markCompCheckBox('cb40-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb40" value="<?php echo (isset($formVars['qb40']))?$formVars['qb40']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb40" value="<?php echo (isset($formVars['rb40']))?$formVars['rb40']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-185" name="sb41" <?php markSelCheckBox('sb41',$formVars); ?>><label class="form-check-label" for="formCheck-29">19A</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vascular tube graft; ePTFE</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-186" name="db41-1" <?php markSelCheckBox('db41-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">3mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-187" name="db41-2" <?php markSelCheckBox('db41-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">3.5mm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-188" name="db41-3" <?php markSelCheckBox('db41-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">4mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-189" name="db41-4" <?php markSelCheckBox('db41-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">5mm</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-190" name="cb41-1" <?php markCompCheckBox('cb41-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">BARD</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-191" name="cb41-2" <?php markCompCheckBox('cb41-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Goretex</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb41-1" value="<?php echo (isset($formVars['qb41-1']))?$formVars['qb41-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb41-2" value="<?php echo (isset($formVars['qb41-2']))?$formVars['qb41-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb41-3" value="<?php echo (isset($formVars['qb41-3']))?$formVars['qb41-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb41-4" value="<?php echo (isset($formVars['qb41-4']))?$formVars['qb41-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="rb41"><?php markRemarkTextA('rb41',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-192" name="sb42" <?php markSelCheckBox('sb42',$formVars); ?>><label class="form-check-label" for="formCheck-29">19B</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vascular tube graft; ePTFE</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-193" name="db42-1" <?php markSelCheckBox('db42-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">12mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-194" name="db42-2" <?php markSelCheckBox('db42-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">13mm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-195" name="db42-3" <?php markSelCheckBox('db42-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">14mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-196" name="db42-4" <?php markSelCheckBox('db42-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">16mm</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-197" name="cb42-1" <?php markCompCheckBox('cb42-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">BARD</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-198" name="cb42-2" <?php markCompCheckBox('cb42-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Goretex</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb42-1" value="<?php echo (isset($formVars['qb42-1']))?$formVars['qb42-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb42-2" value="<?php echo (isset($formVars['qb42-2']))?$formVars['qb42-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb42-3" value="<?php echo (isset($formVars['qb42-3']))?$formVars['qb42-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb42-4" value="<?php echo (isset($formVars['qb42-4']))?$formVars['qb42-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="rb42"><?php markRemarkTextA('rb42',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-199" name="sb43" <?php markSelCheckBox('sb43',$formVars); ?>><label class="form-check-label" for="formCheck-29">19C</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vascular tube graft; Dacron</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-200" name="db43-1" <?php markSelCheckBox('db43-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">8mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-201" name="db43-2" <?php markSelCheckBox('db43-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">10mm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-202" name="db43-3" <?php markSelCheckBox('db43-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">12mm&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-203" name="db43-4" <?php markSelCheckBox('db43-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">14mm</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Maquet</label></div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb43-1" value="<?php echo (isset($formVars['qb43-1']))?$formVars['qb43-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb43-2" value="<?php echo (isset($formVars['qb43-2']))?$formVars['qb43-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb43-3" value="<?php echo (isset($formVars['qb43-3']))?$formVars['qb43-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb43-4" value="<?php echo (isset($formVars['qb43-4']))?$formVars['qb43-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="4" name="rb43"><?php markRemarkTextA('rb43',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-204" name="sb44" <?php markSelCheckBox('sb44',$formVars); ?>><label class="form-check-label" for="formCheck-29">20A</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vena cava cannula, single stage, straight</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-205" name="db44-1" <?php markSelCheckBox('db44-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-206" name="db44-2" <?php markSelCheckBox('db44-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-207" name="db44-3" <?php markSelCheckBox('db44-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-208" name="db44-4" <?php markSelCheckBox('db44-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">18Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-209" name="cb44-1" <?php markCompCheckBox('cb44-1',$formVars); ?>><label class="form-check-label" for="formCheck-438">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-210" name="cb44-2" <?php markCompCheckBox('cb44-2',$formVars); ?>><label class="form-check-label" for="formCheck-438">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb44-1" value="<?php echo (isset($formVars['qb44-1']))?$formVars['qb44-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb44-2" value="<?php echo (isset($formVars['qb44-2']))?$formVars['qb44-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb44-3" value="<?php echo (isset($formVars['qb44-3']))?$formVars['qb44-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb44-4" value="<?php echo (isset($formVars['qb44-4']))?$formVars['qb44-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb44"><?php markRemarkTextA('rb44',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-211" name="sb45" <?php markSelCheckBox('sb45',$formVars); ?>><label class="form-check-label" for="formCheck-29">20B</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vena cava cannula, single stage, angled</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-212" name="db45-1" <?php markSelCheckBox('db45-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">12Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-213" name="db45-2" <?php markSelCheckBox('db45-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">14Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-214" name="db45-3" <?php markSelCheckBox('db45-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-215" name="db45-4" <?php markSelCheckBox('db45-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">18Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-216" name="cb45-1" <?php markCompCheckBox('cb45-1',$formVars); ?>><label class="form-check-label" for="formCheck-438">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-217" name="cb45-2" <?php markCompCheckBox('cb45-2',$formVars); ?>><label class="form-check-label" for="formCheck-438">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb45-1" value="<?php echo (isset($formVars['qb45-1']))?$formVars['qb45-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb45-2" value="<?php echo (isset($formVars['qb45-2']))?$formVars['qb45-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb45-3" value="<?php echo (isset($formVars['qb45-3']))?$formVars['qb45-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb45-4" value="<?php echo (isset($formVars['qb45-4']))?$formVars['qb45-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rb45"><?php markRemarkTextA('rb45',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-218" name="sb46" <?php markSelCheckBox('sb46',$formVars); ?>><label class="form-check-label" for="formCheck-29">20C</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vena cava cannula, dual stage</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-219" name="db46-1" <?php markSelCheckBox('db46-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">34-46&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-220" name="db46-2" <?php markSelCheckBox('db46-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">32-40</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-221" name="cb46-1" <?php markCompCheckBox('cb46-1',$formVars); ?>><label class="form-check-label" for="formCheck-438">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-222" name="cb46-2" <?php markCompCheckBox('cb46-2',$formVars); ?>><label class="form-check-label" for="formCheck-438">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb46-1" value="<?php echo (isset($formVars['qb46-1']))?$formVars['qb46-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb46-2" value="<?php echo (isset($formVars['qb46-2']))?$formVars['qb46-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb46" value="<?php echo (isset($formVars['rb46']))?$formVars['rb46']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-223" name="sb47" <?php markSelCheckBox('sb47',$formVars); ?>><label class="form-check-label" for="formCheck-29">21</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vent catheter</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-224" name="db47-1" <?php markSelCheckBox('db47-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">10Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-225" name="db47-2" <?php markSelCheckBox('db47-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">13Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-226" name="db47-3" <?php markSelCheckBox('db47-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-227" name="cb47-1" <?php markCompCheckBox('cb47-1',$formVars); ?>><label class="form-check-label" for="formCheck-438">Edward LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-228" name="cb47-2" <?php markCompCheckBox('cb47-2',$formVars); ?>><label class="form-check-label" for="formCheck-438">Medtronic</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb47-1" value="<?php echo (isset($formVars['qb47-1']))?$formVars['qb47-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb47-2" value="<?php echo (isset($formVars['qb47-2']))?$formVars['qb47-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb47-3" value="<?php echo (isset($formVars['qb47-3']))?$formVars['qb47-3']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb47" value="<?php echo (isset($formVars['rb47']))?$formVars['rb47']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-229" name="sb48" <?php markSelCheckBox('sb48',$formVars); ?>><label class="form-check-label" for="formCheck-30">22</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Vein cannula</label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-230" name="cb48-1" <?php markCompCheckBox('cb48-1',$formVars); ?>><label class="form-check-label" for="formCheck-46">BL LifeSciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-231" name="cb48-2" <?php markCompCheckBox('cb48-2',$formVars); ?>><label class="form-check-label" for="formCheck-47">Medtronic</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-232" name="cb48-3" <?php markCompCheckBox('cb48-3',$formVars); ?>><label class="form-check-label" for="formCheck-48">LifeLine</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qb48" value="<?php echo (isset($formVars['qb48']))?$formVars['qb48']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rb48" value="<?php echo (isset($formVars['rb48']))?$formVars['rb48']:''; ?>"></div>
                </div>
            </div>
            <div class="container">
                <div>
                    <p></p>
                    <p>You have reached end of section-B. Move to next Tab for next section.&nbsp;</p>
                    <p><strong><em>Changes are saved automatically.</em></strong></p>
                    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                        <div class="container"><a class="navbar-brand text-info" href="#" style="font-size: 18px;"><span style="text-decoration: underline;">Jump to Top of this page</span></a><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="tab-pane" role="tabpanel" id="tab-3">
            <p></p>
            <h2 class="text-left" style="font-size: 26;">&nbsp; &nbsp;Sternotomy and Thoracotomy items&nbsp;</h2>
            <p>&nbsp; &nbsp; &nbsp; Mark the<strong>&nbsp;Items</strong> Required and go to next Tab for next section.</p>
            <div class="container">
                <div class="row">
                    <div class="col-1"><label class="col-form-label"><strong>Sr.No</strong></label></div>
                    <div class="col-2"><label class="col-form-label"><strong>Item Name</strong></label></div>
                    <div class="col-3"><label class="col-form-label"><strong>Description</strong></label></div>
                    <div class="col-3"><label class="col-form-label"><strong>Company/Brand Name Preferred&nbsp;</strong></label></div>
                    <div class="col-1"><label class="col-form-label"><strong>Qty Required&nbsp;</strong></label></div>
                    <div class="col-2"><label class="col-form-label"><strong>Remarks</strong><br></label></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-233" name="sc1" <?php markSelCheckBox('sc1',$formVars); ?>><label class="form-check-label" for="formCheck-28">1</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Asepto Pump<br></label></div>
                    <div class="col-3"><label class="col-form-label">PVC silicon bulb with syringe</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Romson</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc1" value="<?php echo (isset($formVars['qc1']))?$formVars['qc1']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc1" value="<?php echo (isset($formVars['rc1']))?$formVars['rc1']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-239" name="sc2" <?php markSelCheckBox('sc2',$formVars); ?>><label class="form-check-label" for="formCheck-28">2A</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Chest Drain Kit, Bag<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Romson</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc2" value="<?php echo (isset($formVars['qc2']))?$formVars['qc2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc2" value="<?php echo (isset($formVars['rc2']))?$formVars['rc2']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-240" name="sc3" <?php markSelCheckBox('sc3',$formVars); ?>><label class="form-check-label" for="formCheck-28">2B</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Chest Drain Kit, Bottle&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Romson</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc3" value="<?php echo (isset($formVars['qc3']))?$formVars['qc3']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc3" value="<?php echo (isset($formVars['rc3']))?$formVars['rc3']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-241" name="sc4" <?php markSelCheckBox('sc4',$formVars); ?>><label class="form-check-label" for="formCheck-28">2C</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Chest Drain Kit, dry seal and dry suction&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Sinapi Biomedical</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc4" value="<?php echo (isset($formVars['qc4']))?$formVars['qc4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc4" value="<?php echo (isset($formVars['rc4']))?$formVars['rc4']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-246" name="sc5" <?php markSelCheckBox('sc5',$formVars); ?>><label class="form-check-label" for="formCheck-24">3</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Chest tube-straight or angled</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-247" name="dc5-1" <?php markSelCheckBox('dc5-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">16Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-248" name="dc5-2" <?php markSelCheckBox('dc5-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">20Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-279" name="dc5-3" <?php markSelCheckBox('dc5-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">24Fr</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-414" name="dc5-4" <?php markSelCheckBox('dc5-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">28Fr</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-415" name="cc5-1" <?php markCompCheckBox('cc5-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Romson</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-416" name="cc5-2" <?php markCompCheckBox('cc5-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Portex</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc5-1" value="<?php echo (isset($formVars['qc5-1']))?$formVars['qc5-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc5-2" value="<?php echo (isset($formVars['qc5-2']))?$formVars['qc5-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc5-3" value="<?php echo (isset($formVars['qc5-3']))?$formVars['qc5-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc5-4" value="<?php echo (isset($formVars['qc5-4']))?$formVars['qc5-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc5"><?php markRemarkTextA('rc5',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-417" name="sc6" <?php markSelCheckBox('sc6',$formVars); ?>><label class="form-check-label" for="formCheck-24">4</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Crepe Bandage </label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-418" name="dc6-1" <?php markSelCheckBox('dc6-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">4inch</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-458" name="dc6-2" <?php markSelCheckBox('dc6-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">6inch</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-459" name="cc6-1" <?php markCompCheckBox('cc6-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Romson</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-460" name="cc6-2" <?php markCompCheckBox('cc6-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">3M</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-461" name="cc6-3" <?php markCompCheckBox('cc6-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Smith Nephew</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc6-1" value="<?php echo (isset($formVars['qc6-1']))?$formVars['qc6-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc6-2" value="<?php echo (isset($formVars['qc6-2']))?$formVars['qc6-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc6"><?php markRemarkTextA('rc6',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-462" name="sc7" <?php markSelCheckBox('sc7',$formVars); ?>><label class="form-check-label" for="formCheck-24">5</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Disposable caps</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">qty in boxes</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-463" name="cc7-1" <?php markCompCheckBox('cc7-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Genex</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-464" name="cc7-2" <?php markCompCheckBox('cc7-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">3M</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-465" name="cc7-3" <?php markCompCheckBox('cc7-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Rakshak</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc7" value="<?php echo (isset($formVars['qc7']))?$formVars['qc7']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc7"><?php markRemarkTextA('rc7',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-466" name="sc8" <?php markSelCheckBox('sc8',$formVars); ?>><label class="form-check-label" for="formCheck-28">6</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Disposable Gloves, Powdered<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-467" name="dc8-1" <?php markSelCheckBox('dc8-1',$formVars); ?>><label class="form-check-label" for="formCheck-711">6</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-468" name="dc8-2" <?php markSelCheckBox('dc8-2',$formVars); ?>><label class="form-check-label" for="formCheck-715">6.5</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-469" name="dc8-3" <?php markSelCheckBox('dc8-3',$formVars); ?>><label class="form-check-label" for="formCheck-715">7</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-470" name="dc8-4" <?php markSelCheckBox('dc8-4',$formVars); ?>><label class="form-check-label" for="formCheck-715">7.5</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Gammex</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc8-1" value="<?php echo (isset($formVars['qc8-1']))?$formVars['qc8-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc8-2" value="<?php echo (isset($formVars['qc8-2']))?$formVars['qc8-2']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc8-3" value="<?php echo (isset($formVars['qc8-3']))?$formVars['qc8-3']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc8-4" value="<?php echo (isset($formVars['qc8-4']))?$formVars['qc8-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc8" value="<?php echo (isset($formVars['rc8']))?$formVars['rc8']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-471" name="sc9" <?php markSelCheckBox('sc9',$formVars); ?>><label class="form-check-label" for="formCheck-24">7</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Disposable gloves, Powder free</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">-</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Ansell Encore</label></div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc9" value="<?php echo (isset($formVars['qc9']))?$formVars['qc9']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc9"><?php markRemarkTextA('rc9',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-472" name="sc10" <?php markSelCheckBox('sc10',$formVars); ?>><label class="form-check-label" for="formCheck-28">8</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Disposable gloves, latex free, powder free ( PI Hybrid)<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-473" name="dc10-1" <?php markSelCheckBox('dc10-1',$formVars); ?>><label class="form-check-label" for="formCheck-715">7</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-474" name="dc10-2" <?php markSelCheckBox('dc10-2',$formVars); ?>><label class="form-check-label" for="formCheck-715">7.5</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc10-1" value="<?php echo (isset($formVars['qc10-1']))?$formVars['qc10-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc10-2" value="<?php echo (isset($formVars['qc10-2']))?$formVars['qc10-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc10" value="<?php echo (isset($formVars['rc10']))?$formVars['rc10']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-475" name="sc11" <?php markSelCheckBox('sc11',$formVars); ?>><label class="form-check-label" for="formCheck-24">9</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Disposable mask</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-476" name="cc11-1" <?php markCompCheckBox('cc11-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Royal Surgical&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-477" name="cc11-2" <?php markCompCheckBox('cc11-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">3M</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-478" name="cc11-3" <?php markCompCheckBox('cc11-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Rakshak</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc11" value="<?php echo (isset($formVars['qc11']))?$formVars['qc11']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc11"><?php markRemarkTextA('rc11',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-479" name="sc12" <?php markSelCheckBox('sc12',$formVars); ?>><label class="form-check-label" for="formCheck-28">10</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Disposable Patient electrocautery plate<br></label></div>
                    <div class="col-3"><label class="col-form-label">Code 9165</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">3M</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc12" value="<?php echo (isset($formVars['qc12']))?$formVars['qc12']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc12" value="<?php echo (isset($formVars['rc12']))?$formVars['rc12']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-483" name="sc13" <?php markSelCheckBox('sc13',$formVars); ?>><label class="form-check-label" for="formCheck-24">11</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Disposable surgical gown</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">in pairs</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-497" name="cc13-1" <?php markCompCheckBox('cc13-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Surgiwear</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-498" name="cc13-2" <?php markCompCheckBox('cc13-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">3M</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc13" value="<?php echo (isset($formVars['qc13']))?$formVars['qc13']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc13"><?php markRemarkTextA('rc13',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-503" name="sc14" <?php markSelCheckBox('sc14',$formVars); ?>><label class="form-check-label" for="formCheck-24">12</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Disposable suction kit</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-504" name="cc14-1" <?php markCompCheckBox('cc14-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Romson</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-505" name="cc14-2" <?php markCompCheckBox('cc14-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Top</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc14" value="<?php echo (isset($formVars['qc14']))?$formVars['qc14']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc14"><?php markRemarkTextA('rc14',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-506" name="sc15" <?php markSelCheckBox('sc15',$formVars); ?>><label class="form-check-label" for="formCheck-28">13</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Disposable Dressing, Tegaderm<br></label></div>
                    <div class="col-3"><label class="col-form-label">10cmx12cm</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">3M</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc15" value="<?php echo (isset($formVars['qc15']))?$formVars['qc15']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc15" value="<?php echo (isset($formVars['rc15']))?$formVars['rc15']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-507" name="sc16" <?php markSelCheckBox('sc16',$formVars); ?>><label class="form-check-label" for="formCheck-28">14</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Disposable Dressing, Primapore<br></label></div>
                    <div class="col-3"><label class="col-form-label">large,medium,small</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Smith &amp; Nephew</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc16" value="<?php echo (isset($formVars['qc16']))?$formVars['qc16']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc16"><?php markRemarkTextA('rc16',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-508" name="sc17" <?php markSelCheckBox('sc17',$formVars); ?>><label class="form-check-label" for="formCheck-28">15A</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Electro Cautery lead/ pencil ( Teflon Coated E-Z Clean)<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Megadyne</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc17" value="<?php echo (isset($formVars['qc17']))?$formVars['qc17']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc17"><?php markRemarkTextA('rc17',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-509" name="sc18" <?php markSelCheckBox('sc18',$formVars); ?>><label class="form-check-label" for="formCheck-28">15B</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Electro Cautery lead/pencil tip<br></label></div>
                    <div class="col-3"><label class="col-form-label">long</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Megadyne<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc18" value="<?php echo (isset($formVars['qc18']))?$formVars['qc18']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc18"><?php markRemarkTextA('rc18',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-510" name="sc19" <?php markSelCheckBox('sc19',$formVars); ?>><label class="form-check-label" for="formCheck-28">16</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Giggli wire<br></label></div>
                    <div class="col-3"><label class="col-form-label">braided steel wire for bone cutting</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc19" value="<?php echo (isset($formVars['qc19']))?$formVars['qc19']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc19"><?php markRemarkTextA('rc19',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-511" name="sc20" <?php markSelCheckBox('sc20',$formVars); ?>><label class="form-check-label" for="formCheck-24">17A</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Hemostatic Clip, Small (code- 001204)</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-512" name="cc20-1" <?php markCompCheckBox('cc20-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Horizon(Teleflex)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-513" name="cc20-2" <?php markCompCheckBox('cc20-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Ethicon</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc20" value="<?php echo (isset($formVars['qc20']))?$formVars['qc20']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc20"><?php markRemarkTextA('rc20',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-514" name="sc21" <?php markSelCheckBox('sc21',$formVars); ?>><label class="form-check-label" for="formCheck-24">17B</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Hemostatic Clip, Medium (code- 002204)</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-515" name="cc21-1" <?php markCompCheckBox('cc21-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Horizon(Teleflex)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-516" name="cc21-2" <?php markCompCheckBox('cc21-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Ethicon</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc21" value="<?php echo (isset($formVars['qc21']))?$formVars['qc21']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc21"><?php markRemarkTextA('rc21',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-517" name="sc22" <?php markSelCheckBox('sc22',$formVars); ?>><label class="form-check-label" for="formCheck-24">17C</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Hemostatic Clip,  Large (code- LT 400)</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-518" name="cc22-1" <?php markCompCheckBox('cc22-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Horizon(Teleflex)</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-519" name="cc22-2" <?php markCompCheckBox('cc22-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Ethicon</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc22" value="<?php echo (isset($formVars['qc22']))?$formVars['qc22']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc22"><?php markRemarkTextA('rc22',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-520" name="sc23" <?php markSelCheckBox('sc23',$formVars); ?>><label class="form-check-label" for="formCheck-28">18</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent: Absorbable hemostat: SURGICELL<br></label></div>
                    <div class="col-3"><label class="col-form-label">&nbsp;Oxidised regenreated cellulose, Size 4inX 8in</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Ethicon<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc23" value="<?php echo (isset($formVars['qc23']))?$formVars['qc23']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc23"><?php markRemarkTextA('rc23',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-521" name="sc24" <?php markSelCheckBox('sc24',$formVars); ?>><label class="form-check-label" for="formCheck-28">19</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent: Absorbable hemostat; FIBRILLAR<br></label></div>
                    <div class="col-3"><label class="col-form-label">Oxidised regenerated cellulose,2 in X 4in, 4in X4in&nbsp;</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Ethicon<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc24" value="<?php echo (isset($formVars['qc24']))?$formVars['qc24']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc24"><?php markRemarkTextA('rc24',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-522" name="sc25" <?php markSelCheckBox('sc25',$formVars); ?>><label class="form-check-label" for="formCheck-28">20</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent: Absorbable gelatin; SPONGOSTAN<br></label></div>
                    <div class="col-3"><label class="col-form-label">absorbable hemostatic gelatin sponge, 7cm X 5cm X 1cm</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Ethicon<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc25" value="<?php echo (isset($formVars['qc25']))?$formVars['qc25']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc25"><?php markRemarkTextA('rc25',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-523" name="sc26" <?php markSelCheckBox('sc26',$formVars); ?>><label class="form-check-label" for="formCheck-28">21</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent: Hemostatic matrix; SURGIFLOW&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">Hemosatatic Gelatin matrix, 8 ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Ethicon<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc26" value="<?php echo (isset($formVars['qc26']))?$formVars['qc26']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc26"><?php markRemarkTextA('rc26',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-524" name="sc27" <?php markSelCheckBox('sc27',$formVars); ?>><label class="form-check-label" for="formCheck-28">22</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent: surgical sealent; COSEAL<br></label></div>
                    <div class="col-3"><label class="col-form-label">surgical sealent, 4ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Baxter<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc27" value="<?php echo (isset($formVars['qc27']))?$formVars['qc27']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc27"><?php markRemarkTextA('rc27',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-525" name="sc28" <?php markSelCheckBox('sc28',$formVars); ?>><label class="form-check-label" for="formCheck-28">23</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent:&nbsp;Fibrin Sealent; TISSEEL <br></label></div>
                    <div class="col-3"><label class="col-form-label">Fibrin sealent, 4 ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Baxter<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc28" value="<?php echo (isset($formVars['qc28']))?$formVars['qc28']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc28"><?php markRemarkTextA('rc28',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-526" name="sc29" <?php markSelCheckBox('sc29',$formVars); ?>><label class="form-check-label" for="formCheck-28">24</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">hemostatic agent: FLOWSEAL<br></label></div>
                    <div class="col-3"><label class="col-form-label">Gelatin and thrombin, 10 ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Baxter<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc29" value="<?php echo (isset($formVars['qc29']))?$formVars['qc29']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc29"><?php markRemarkTextA('rc29',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-527" name="sc30" <?php markSelCheckBox('sc30',$formVars); ?>><label class="form-check-label" for="formCheck-28">25</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Hemostatic agent: Surgical adhesive; BIOGLUE<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Cryolife<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc30" value="<?php echo (isset($formVars['qc30']))?$formVars['qc30']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc30"><?php markRemarkTextA('rc30',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-528" name="sc31" <?php markSelCheckBox('sc31',$formVars); ?>><label class="form-check-label" for="formCheck-28">26</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">IOBAN<br></label></div>
                    <div class="col-3"><label class="col-form-label">Small, Medium and large 56 cmX45 cm</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">3M<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc31" value="<?php echo (isset($formVars['qc31']))?$formVars['qc31']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc31"><?php markRemarkTextA('rc31',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-529" name="sc32" <?php markSelCheckBox('sc32',$formVars); ?>><label class="form-check-label" for="formCheck-24">27</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Latex Examination Gloves </label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Medium size, Box of 100 pieces</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-530" name="cc32-1" <?php markCompCheckBox('cc32-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Nulife</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-531" name="cc32-2" <?php markCompCheckBox('cc32-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Sara+care</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-532" name="cc32-3" <?php markCompCheckBox('cc32-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Fit-n-safe</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc32" value="<?php echo (isset($formVars['qc32']))?$formVars['qc32']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc32"><?php markRemarkTextA('rc32',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-533" name="sc33" <?php markSelCheckBox('sc33',$formVars); ?>><label class="form-check-label" for="formCheck-28">28</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Plastic Apron, sterile pack<br></label></div>
                    <div class="col-3"><label class="col-form-label">Half size, Full size</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Surgiwear<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc33" value="<?php echo (isset($formVars['qc33']))?$formVars['qc33']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc33"><?php markRemarkTextA('rc33',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-534" name="sc34" <?php markSelCheckBox('sc34',$formVars); ?>><label class="form-check-label" for="formCheck-28">29</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Polydrapes<br></label></div>
                    <div class="col-3"><label class="col-form-label">120cmx210cm</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Surgiwear<br></label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc34" value="<?php echo (isset($formVars['qc34']))?$formVars['qc34']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc34"><?php markRemarkTextA('rc34',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-535" name="sc35" <?php markSelCheckBox('sc35',$formVars); ?>><label class="form-check-label" for="formCheck-24">30</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Patient Scrub: Povidone Iodine, Scrub 10 %</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">500mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-536" name="cc35-1" <?php markCompCheckBox('cc35-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Johnson&amp;Johnson</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-537" name="cc35-2" <?php markCompCheckBox('cc35-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Win Medicare</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-538" name="cc35-3" <?php markCompCheckBox('cc35-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Mirowin</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc35" value="<?php echo (isset($formVars['qc35']))?$formVars['qc35']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc35"><?php markRemarkTextA('rc35',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-539" name="sc36" <?php markSelCheckBox('sc36',$formVars); ?>><label class="form-check-label" for="formCheck-24">31</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Patient Scrub: Povidone Iodine, lotion 10 %</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">500mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-540" name="cc36-1" <?php markCompCheckBox('cc36-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Johnson&amp;Johnson</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-541" name="cc36-2" <?php markCompCheckBox('cc36-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">Win Medicare</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-542" name="cc36-3" <?php markCompCheckBox('cc36-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Mirowin</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc36" value="<?php echo (isset($formVars['qc36']))?$formVars['qc36']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc36"><?php markRemarkTextA('rc36',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-543" name="sc37" <?php markSelCheckBox('sc37',$formVars); ?>><label class="form-check-label" for="formCheck-24">32A</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Rub in hand disinfectant; Propanolol 500mL</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-544" name="dc37-1" <?php markSelCheckBox('dc37-1',$formVars); ?>><label class="form-check-label" for="formCheck-762">1-propanol solution</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-545" name="dc37-2" <?php markSelCheckBox('dc37-2',$formVars); ?>><label class="form-check-label" for="formCheck-762">2-propanol solution</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-546" name="cc37-1" <?php markCompCheckBox('cc37-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Raman&amp;Weil</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-547" name="cc37-2" <?php markCompCheckBox('cc37-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">3M</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-548" name="cc37-3" <?php markCompCheckBox('cc37-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Microwin</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc37-1" value="<?php echo (isset($formVars['qc37-1']))?$formVars['qc37-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc37-2" value="<?php echo (isset($formVars['qc37-2']))?$formVars['qc37-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc37"><?php markRemarkTextA('rc37',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-549" name="sc38" <?php markSelCheckBox('sc38',$formVars); ?>><label class="form-check-label" for="formCheck-24">32B</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Rub in hand disinfectant; Chlorhexidine solution</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">chlorhexidine 10%, 500ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-550" name="cc38-1" <?php markCompCheckBox('cc38-1',$formVars); ?>><label class="form-check-label" for="formCheck-37">Raman&amp;Weil</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-551" name="cc38-2" <?php markCompCheckBox('cc38-2',$formVars); ?>><label class="form-check-label" for="formCheck-38">3M</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-552" name="cc38-3" <?php markCompCheckBox('cc38-3',$formVars); ?>><label class="form-check-label" for="formCheck-38">Microwin</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc38" value="<?php echo (isset($formVars['qc38']))?$formVars['qc38']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rc38"><?php markRemarkTextA('rc38',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-553" name="sc39" <?php markSelCheckBox('sc39',$formVars); ?>><label class="form-check-label" for="formCheck-28">33</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Pacing leads temporary<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">LifeLine</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc39" value="<?php echo (isset($formVars['qc39']))?$formVars['qc39']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc39" value="<?php echo (isset($formVars['rc39']))?$formVars['rc39']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-554" name="sc40" <?php markSelCheckBox('sc40',$formVars); ?>><label class="form-check-label" for="formCheck-28">34</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Romoseal Drain<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-555" name="dc40-1" <?php markSelCheckBox('dc40-1',$formVars); ?>><label class="form-check-label" for="formCheck-260">12</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-556" name="dc40-2" <?php markSelCheckBox('dc40-2',$formVars); ?>><label class="form-check-label" for="formCheck-260">14</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Romson</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc40-1" value="<?php echo (isset($formVars['qc40-1']))?$formVars['qc40-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc40-2" value="<?php echo (isset($formVars['qc40-2']))?$formVars['qc40-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc40" value="<?php echo (isset($formVars['rc40']))?$formVars['rc40']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-557" name="sc41" <?php markSelCheckBox('sc41',$formVars); ?>><label class="form-check-label" for="formCheck-27">35</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Skin Marker</label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-558" name="cc41-1" <?php markCompCheckBox('cc41-1',$formVars); ?>><label class="form-check-label" for="formCheck-42">Surgiwear</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-559" name="cc41-2" <?php markCompCheckBox('cc41-2',$formVars); ?>><label class="form-check-label" for="formCheck-43">Romson</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc41" value="<?php echo (isset($formVars['qc41']))?$formVars['qc41']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc41" value="<?php echo (isset($formVars['rc41']))?$formVars['rc41']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-560" name="sc42" <?php markSelCheckBox('sc42',$formVars); ?>><label class="form-check-label" for="formCheck-28">36</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Skin Stapler</label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Ethicon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc42" value="<?php echo (isset($formVars['qc42']))?$formVars['qc42']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc42" value="<?php echo (isset($formVars['rc42']))?$formVars['rc42']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-561" name="sc43" <?php markSelCheckBox('sc43',$formVars); ?>><label class="form-check-label" for="formCheck-28">37</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Snugger Set</label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-562" name="dc43-1" <?php markSelCheckBox('dc43-1',$formVars); ?>><label class="form-check-label" for="formCheck-262">Adult</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-563" name="dc43-2" <?php markSelCheckBox('dc43-2',$formVars); ?>><label class="form-check-label" for="formCheck-262">Pediatric</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Romson</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc43-1" value="<?php echo (isset($formVars['qc43-1']))?$formVars['qc43-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc43-2" value="<?php echo (isset($formVars['qc43-2']))?$formVars['qc43-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc43" value="<?php echo (isset($formVars['rc43']))?$formVars['rc43']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-564" name="sc44" <?php markSelCheckBox('sc44',$formVars); ?>><label class="form-check-label" for="formCheck-28">38</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Specican</label></div>
                    <div class="col-3"><label class="col-form-label">100mL</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc44" value="<?php echo (isset($formVars['qc44']))?$formVars['qc44']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc44" value="<?php echo (isset($formVars['rc44']))?$formVars['rc44']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-565" name="sc45" <?php markSelCheckBox('sc45',$formVars); ?>><label class="form-check-label" for="formCheck-28">39</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Sternal Saw Blade </label></div>
                    <div class="col-3"><label class="col-form-label">Stainless steel sternal saw blade</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Stryker</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc45" value="<?php echo (isset($formVars['qc45']))?$formVars['qc45']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc45" value="<?php echo (isset($formVars['rc45']))?$formVars['rc45']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-566" name="sc46" <?php markSelCheckBox('sc46',$formVars); ?>><label class="form-check-label" for="formCheck-29">40</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Surgical Blade</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-567" name="dc46-1" <?php markSelCheckBox('dc46-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">10</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-568" name="dc46-2" <?php markSelCheckBox('dc46-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">11</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-569" name="dc46-3" <?php markSelCheckBox('dc46-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">15</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-570" name="dc46-4" <?php markSelCheckBox('dc46-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">22</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-571" name="dc46-5" <?php markSelCheckBox('dc46-5',$formVars); ?>><label class="form-check-label" for="formCheck-1">23</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-572" name="cc46-1" <?php markCompCheckBox('cc46-1',$formVars); ?>><label class="form-check-label" for="formCheck-273">Bard</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-573" name="cc46-2" <?php markCompCheckBox('cc46-2',$formVars); ?>><label class="form-check-label" for="formCheck-273">Surgeon</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc46-1" value="<?php echo (isset($formVars['qc46-1']))?$formVars['qc46-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc46-2" value="<?php echo (isset($formVars['qc46-2']))?$formVars['qc46-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc46-3" value="<?php echo (isset($formVars['qc46-3']))?$formVars['qc46-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc46-4" value="<?php echo (isset($formVars['qc46-4']))?$formVars['qc46-4']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc46-5" value="<?php echo (isset($formVars['qc46-5']))?$formVars['qc46-5']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc46" value="<?php echo (isset($formVars['rc46']))?$formVars['rc46']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-574" name="sc47" <?php markSelCheckBox('sc47',$formVars); ?>><label class="form-check-label" for="formCheck-29">41</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Tissue Stapler cartidges</label></div></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-575" name="dc47-1" <?php markSelCheckBox('dc47-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">ECR 60B&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-576" name="dc47-2" <?php markSelCheckBox('dc47-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">ECR 60D</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-577" name="dc47-3" <?php markSelCheckBox('dc47-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">ECR 60G</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-578" name="dc47-4" <?php markSelCheckBox('dc47-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">ECR 60W</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Ethicon</label></div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc47-1" value="<?php echo (isset($formVars['qc47-1']))?$formVars['qc47-1']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc47-2" value="<?php echo (isset($formVars['qc47-2']))?$formVars['qc47-2']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc47-3" value="<?php echo (isset($formVars['qc47-3']))?$formVars['qc47-3']:0; ?>" /></div>

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc47-4" value="<?php echo (isset($formVars['qc47-4']))?$formVars['qc47-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="3" name="rc47"><?php markRemarkTextA('rc47',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-579" name="sc48" <?php markSelCheckBox('sc48',$formVars); ?>><label class="form-check-label" for="formCheck-29">42</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Urometer</label></div></div>
                    <div class="col-3">
                        <div></div><label>-</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Romson</label></div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc48" value="<?php echo (isset($formVars['qc48']))?$formVars['qc48']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="1" name="rc48"><?php markRemarkTextA('rc48',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-580" name="sc49" <?php markSelCheckBox('sc49',$formVars); ?>><label class="form-check-label" for="formCheck-29">43</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">Vascular Tape</label></div></div>
                    <div class="col-3">
                        <div></div><label>-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-581" name="cc49-1" <?php markCompCheckBox('cc49-1',$formVars); ?>><label class="form-check-label" for="formCheck-44">BL Lifesciences</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-582" name="cc49-2" <?php markCompCheckBox('cc49-2',$formVars); ?>><label class="form-check-label" for="formCheck-45">Scanlon</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-583" name="cc49-3" <?php markCompCheckBox('cc49-3',$formVars); ?>><label class="form-check-label" for="formCheck-45">Aspen Surgical</label></div>
                    </div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qc49" value="<?php echo (isset($formVars['qc49']))?$formVars['qc49']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rc49" value="<?php echo (isset($formVars['rc49']))?$formVars['rc49']:''; ?>"></div>
                </div>
            </div>
            <div class="container">
                <div>
                    <p></p>
                    <p>You have reached end of section-C. Move to next Tab for next section.&nbsp;</p>
                    <p><strong><em>Changes are saved automatically.</em></strong></p>
                    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                        <div class="container"><a class="navbar-brand text-info" href="#" style="font-size: 18px;"><span style="text-decoration: underline;">Jump to Top of this page</span></a><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="tab-pane" role="tabpanel" id="tab-4">
            <p></p>
            <h2 class="text-left" style="font-size: 26;">&nbsp; &nbsp;Suture Materials</h2>
            <p>&nbsp; &nbsp; &nbsp; Mark the<strong>&nbsp;Items</strong> Required and go to next Tab for next section.</p>
            <div class="container">
                <div class="row">
                    <div class="col-1"><label class="col-form-label"><strong>Sr.No</strong></label></div>
                    <div class="col-2"><label class="col-form-label"><strong>Item Name</strong></label></div>
                    <div class="col-3"><label class="col-form-label"><strong>Description &amp; Code</strong></label></div>
                    <div class="col-3"><label class="col-form-label"><strong>Company Name Preferred&nbsp;</strong></label></div>
                    <div class="col-1"><label class="col-form-label"><strong>Qty (in foils)&nbsp;</strong></label></div>
                    <div class="col-2"><label class="col-form-label"><strong>Remarks</strong><br></label></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-234" name="sd1" <?php markSelCheckBox('sd1',$formVars); ?>><label class="form-check-label" for="formCheck-28">1</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Bone Wax<br></label></div>
                    <div class="col-3"><label class="col-form-label">W81001<br></label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd1" value="<?php echo (isset($formVars['qd1']))?$formVars['qd1']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd1" value="<?php echo (isset($formVars['rd1']))?$formVars['rd1']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-235" name="sd2" <?php markSelCheckBox('sd2',$formVars); ?>><label class="form-check-label" for="formCheck-28">2</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O half circle, taper cut17/16 mm double needle, PTFE pladget ( 6x3x1.5/7X3x 1.5)75cm ( Aortic) Code: NMW 6555/ 3219-56,</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-236" name="cd2-1" <?php markCompCheckBox('cd2-1',$formVars); ?>><label class="form-check-label" for="formCheck-55">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-237" name="cd2-2" <?php markCompCheckBox('cd2-2',$formVars); ?>><label class="form-check-label" for="formCheck-56">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd2" value="<?php echo (isset($formVars['qd2']))?$formVars['qd2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd2" value="<?php echo (isset($formVars['rd2']))?$formVars['rd2']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-238" name="sd3" <?php markSelCheckBox('sd3',$formVars); ?>><label class="form-check-label" for="formCheck-28">3</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O, half circle taper cut 17 mm double needle PTFE pledget ( 3X3X1.5) 75 cm, ( Aortic) Code: MNW6556/ 3218-56</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-242" name="cd3-1" <?php markCompCheckBox('cd3-1',$formVars); ?>><label class="form-check-label" for="formCheck-57">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-243" name="cd3-2" <?php markCompCheckBox('cd3-2',$formVars); ?>><label class="form-check-label" for="formCheck-57">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd3" name = "qd3" value="<?php echo (isset($formVars['qd3']))?$formVars['qd3']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd3" value="<?php echo (isset($formVars['rd3']))?$formVars['rd3']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-244" name="sd4" <?php markSelCheckBox('sd4',$formVars); ?>><label class="form-check-label" for="formCheck-28">4</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND ( green) 2-O, double needle 17 mm with pledget ( Aortic) Code: PX 54 H</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd4" value="<?php echo (isset($formVars['qd4']))?$formVars['qd4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd4" value="<?php echo (isset($formVars['rd4']))?$formVars['rd4']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-245" name="sd5" <?php markSelCheckBox('sd5',$formVars); ?>><label class="form-check-label" for="formCheck-28">5</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND (white) 2-O double needle17 mm with pledget ( Aortic) Code: PX17</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd5" value="<?php echo (isset($formVars['qd5']))?$formVars['qd5']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd5" value="<?php echo (isset($formVars['rd5']))?$formVars['rd5']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-249" name="sd6" <?php markSelCheckBox('sd6',$formVars); ?>><label class="form-check-label" for="formCheck-28">6</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND ( green) 2-O, double needle 17 mm, non pledget ( Aortic) Code: W6937</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd6" value="<?php echo (isset($formVars['qd6']))?$formVars['qd6']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd6" value="<?php echo (isset($formVars['rd6']))?$formVars['rd6']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-250" name="sd7" <?php markSelCheckBox('sd7',$formVars); ?>><label class="form-check-label" for="formCheck-28">7</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND (white) 2-O, double needle 17 mm, non pledget ( Aortic) Code: W6917</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd7" value="<?php echo (isset($formVars['qd7']))?$formVars['qd7']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd7" value="<?php echo (isset($formVars['rd7']))?$formVars['rd7']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-251" name="sd8" <?php markSelCheckBox('sd8',$formVars); ?>><label class="form-check-label" for="formCheck-28">8</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O half circle taper cut 25 mm double needle PTFE pledget ( 3X3X1.5) 75 cm ( Mitral) Code: MNW 6578/ 3218-56</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-252" name="cd8-1" <?php markCompCheckBox('cd8-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-253" name="cd8-2" <?php markCompCheckBox('cd8-2',$formVars); ?>><label class="form-check-label" for="formCheck-59">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd8" value="<?php echo (isset($formVars['qd8']))?$formVars['qd8']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd8" value="<?php echo (isset($formVars['rd8']))?$formVars['rd8']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-254" name="sd9" <?php markSelCheckBox('sd9',$formVars); ?>><label class="form-check-label" for="formCheck-28">9</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O half circle taper cut 25 mm double needle PTFE pledget ( 6X3X1.5) 75 cm ( Mitral) Code: MNW 6577/ 3324-56</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-255" name="cd9-1" <?php markCompCheckBox('cd9-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-256" name="cd9-2" <?php markCompCheckBox('cd9-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd9" value="<?php echo (isset($formVars['qd9']))?$formVars['qd9']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd9" value="<?php echo (isset($formVars['rd9']))?$formVars['rd9']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-257" name="sd10" <?php markSelCheckBox('sd10',$formVars); ?>><label class="form-check-label" for="formCheck-28">10</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O ( Green) half circle taper cut 26 mm double needle PTFE pledget (Mitral) Code: PX 62H </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd10" value="<?php echo (isset($formVars['qd10']))?$formVars['qd10']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd10" value="<?php echo (isset($formVars['rd10']))?$formVars['rd10']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-258" name="sd11" <?php markSelCheckBox('sd11',$formVars); ?>><label class="form-check-label" for="formCheck-28">11</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O ( white) half circle taper cut 26 mm double needle PTFE pledget (Mitral) Code: PX 64H </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd11" value="<?php echo (isset($formVars['qd11']))?$formVars['qd11']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd11" value="<?php echo (isset($formVars['rd11']))?$formVars['rd11']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-259" name="sd12" <?php markSelCheckBox('sd12',$formVars); ?>><label class="form-check-label" for="formCheck-28">12</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O ( Green) half circle taper cut double needle 25 mm non pledget ( Mitral ) code: W6977</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd12" value="<?php echo (isset($formVars['qd12']))?$formVars['qd12']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd12" value="<?php echo (isset($formVars['rd12']))?$formVars['rd12']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-260" name="sd13" <?php markSelCheckBox('sd13',$formVars); ?>><label class="form-check-label" for="formCheck-28">13</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O ( White) half circle taper cut double needle 25 mm non pledget ( Mitral ) code: W6987</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd13" value="<?php echo (isset($formVars['qd13']))?$formVars['qd13']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd13" value="<?php echo (isset($formVars['rd13']))?$formVars['rd13']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-261" name="sd14" <?php markSelCheckBox('sd14',$formVars); ?>><label class="form-check-label" for="formCheck-28">14</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 2-O ( Green) half circle round body single needle 26 mm, 75 cm (Mitral) Code: X833H</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd14" value="<?php echo (isset($formVars['qd14']))?$formVars['qd14']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd14" value="<?php echo (isset($formVars['rd14']))?$formVars['rd14']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-262" name="sd15" <?php markSelCheckBox('sd15',$formVars); ?>><label class="form-check-label" for="formCheck-28">15</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 3-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 3-O 1/2 Circle round body, double needle, 100cm. Code: W 6552</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd15" value="<?php echo (isset($formVars['qd15']))?$formVars['qd15']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd15" value="<?php echo (isset($formVars['rd15']))?$formVars['rd15']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-263" name="sd16" <?php markSelCheckBox('sd16',$formVars); ?>><label class="form-check-label" for="formCheck-28">16</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND 3-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND 3-O 1/2 Circle round body Single SH needle, 75cm. Code: X 832 H </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd16" value="<?php echo (isset($formVars['qd16']))?$formVars['qd16']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd16" value="<?php echo (isset($formVars['rd16']))?$formVars['rd16']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-264" name="sd17" <?php markSelCheckBox('sd17',$formVars); ?>><label class="form-check-label" for="formCheck-28">17</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND no-2 <br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND no-2, taper cut 1/2 circle (heavy). Code: W 4843</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd17" value="<?php echo (isset($formVars['qd17']))?$formVars['qd17']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd17" value="<?php echo (isset($formVars['rd17']))?$formVars['rd17']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-265" name="sd18" <?php markSelCheckBox('sd18',$formVars); ?>><label class="form-check-label" for="formCheck-28">18</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHIBOND no-5 <br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHIBOND no-5, taper cut 1/2 circle (heavy). Code: W 4846</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd18" value="<?php echo (isset($formVars['qd18']))?$formVars['qd18']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd18" value="<?php echo (isset($formVars['rd18']))?$formVars['rd18']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-266" name="sd19" <?php markSelCheckBox('sd19',$formVars); ?>><label class="form-check-label" for="formCheck-28">19</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHISTEEL no-5 <br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHISTEEL no-5, 1/2 circle CCS conventional cutting. Code: M 653</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd19" value="<?php echo (isset($formVars['qd19']))?$formVars['qd19']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd19" value="<?php echo (isset($formVars['rd19']))?$formVars['rd19']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-267" name="sd20" <?php markSelCheckBox('sd20',$formVars); ?>><label class="form-check-label" for="formCheck-28">20</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHILON 2-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">ETHILON 2-O Copde: NW 3336</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd20" value="<?php echo (isset($formVars['qd20']))?$formVars['qd20']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd20" value="<?php echo (isset($formVars['rd20']))?$formVars['rd20']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-268" name="sd21" <?php markSelCheckBox('sd21',$formVars); ?>><label class="form-check-label" for="formCheck-28">21</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">ETHILON 3-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">Ethilon 3-O, Code: NW 3328</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd21" value="<?php echo (isset($formVars['qd21']))?$formVars['qd21']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd21" value="<?php echo (isset($formVars['rd21']))?$formVars['rd21']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-269" name="sd22" <?php markSelCheckBox('sd22',$formVars); ?>><label class="form-check-label" for="formCheck-28">22</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Mersilene Tape <br></label></div>
                    <div class="col-3"><label class="col-form-label">Mersilene Tape 1/2 circle round body blunt (heavy) double needle, 65 mm. Code: RS21</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd22" value="<?php echo (isset($formVars['qd22']))?$formVars['qd22']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd22" value="<?php echo (isset($formVars['rd22']))?$formVars['rd22']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-270" name="sd23" <?php markSelCheckBox('sd23',$formVars); ?>><label class="form-check-label" for="formCheck-28">23</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">MONOCRYL 3-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">Monocryl 3-O, Code:&nbsp;NW 1326</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd23" value="<?php echo (isset($formVars['qd23']))?$formVars['qd23']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd23" value="<?php echo (isset($formVars['rd23']))?$formVars['rd23']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-271" name="sd24" <?php markSelCheckBox('sd24',$formVars); ?>><label class="form-check-label" for="formCheck-28">24A</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Pacing wire<br></label></div>
                    <div class="col-3"><label class="col-form-label">FEP 15E/TPW30<br></label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-272" name="cd24-1" <?php markCompCheckBox('cd24-1',$formVars); ?>><label class="form-check-label" for="formCheck-63">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-273" name="cd24-2" <?php markCompCheckBox('cd24-2',$formVars); ?>><label class="form-check-label" for="formCheck-63">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd24" value="<?php echo (isset($formVars['qd24']))?$formVars['qd24']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd24" value="<?php echo (isset($formVars['rd24']))?$formVars['rd24']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-274" name="sd25" <?php markSelCheckBox('sd25',$formVars); ?>><label class="form-check-label" for="formCheck-28">24B</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Pacing wire<br></label></div>
                    <div class="col-3"><label class="col-form-label">FEP 13E/ TPW10</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-275" name="cd25-1" <?php markCompCheckBox('cd25-1',$formVars); ?>><label class="form-check-label" for="formCheck-65">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-280" name="cd25-2" <?php markCompCheckBox('cd25-2',$formVars); ?>><label class="form-check-label" for="formCheck-65">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd25" value="<?php echo (isset($formVars['qd25']))?$formVars['qd25']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd25" value="<?php echo (isset($formVars['rd25']))?$formVars['rd25']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-293" name="sd26" <?php markSelCheckBox('sd26',$formVars); ?>><label class="form-check-label" for="formCheck-28">25</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 1-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 1-O 1/2 circle round body, Code: NW 846 </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd26" value="<?php echo (isset($formVars['qd26']))?$formVars['qd26']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd26" value="<?php echo (isset($formVars['rd26']))?$formVars['rd26']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-294" name="sd27" <?php markSelCheckBox('sd27',$formVars); ?>><label class="form-check-label" for="formCheck-28">26</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 2-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 2-O 1/2 circle round body, Code: NW 844</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd27" value="<?php echo (isset($formVars['qd27']))?$formVars['qd27']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd27" value="<?php echo (isset($formVars['rd27']))?$formVars['rd27']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-295" name="sd28" <?php markSelCheckBox('sd28',$formVars); ?>><label class="form-check-label" for="formCheck-28">27</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 3-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 3-O half circle taper point double needle 26 mm 90 cm. Code: 8522H</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd28" value="<?php echo (isset($formVars['qd28']))?$formVars['qd28']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd28" value="<?php echo (isset($formVars['rd28']))?$formVars['rd28']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-298" name="sd29" <?php markSelCheckBox('sd29',$formVars); ?>><label class="form-check-label" for="formCheck-28">28</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 4-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 4-O half circle taper point double needle 26 mm 90 cm. Code: 8521H</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000"name = "qd29" value="<?php echo (isset($formVars['qd29']))?$formVars['qd29']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd29" value="<?php echo (isset($formVars['rd29']))?$formVars['rd29']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-299" name="sd30" <?php markSelCheckBox('sd30',$formVars); ?>><label class="form-check-label" for="formCheck-28">29</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 4-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 4-O 1/2 Circle Round body,12.5 mm&nbsp;TF double needle, 60cms&nbsp;Code: 8204 H</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd30" value="<?php echo (isset($formVars['qd30']))?$formVars['qd30']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd30" value="<?php echo (isset($formVars['rd30']))?$formVars['rd30']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-301" name="sd31" <?php markSelCheckBox('sd31',$formVars); ?>><label class="form-check-label" for="formCheck-28">30</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 4-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 4-O 1/2 circle round body, 17mm double needle, 90cm. Code: W 8557</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd31" value="<?php echo (isset($formVars['qd31']))?$formVars['qd31']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd31" value="<?php echo (isset($formVars['rd31']))?$formVars['rd31']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-302" name="sd32" <?php markSelCheckBox('sd32',$formVars); ?>><label class="form-check-label" for="formCheck-28">31</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 5-O <br></label></div>
                    <div class="col-3"><label class="col-form-label"> PROLENE 5-O 1/2 Circle Round body-2, 13 mm double needle, 75cms. code: W 8710</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd32" value="<?php echo (isset($formVars['qd32']))?$formVars['qd32']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd32" value="<?php echo (isset($formVars['rd32']))?$formVars['rd32']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-303" name="sd33" <?php markSelCheckBox('sd33',$formVars); ?>><label class="form-check-label" for="formCheck-28">32</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 5-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 5-O 1/2 Circle Round body&nbsp;double needle, 60 cms. Code:&nbsp;TF8205 H  </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd33" value="<?php echo (isset($formVars['qd33']))?$formVars['qd33']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd33" value="<?php echo (isset($formVars['rd33']))?$formVars['rd33']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-304" name="sd34" <?php markSelCheckBox('sd34',$formVars); ?>><label class="form-check-label" for="formCheck-28">33</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 6-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 6-O 3/8 Circle Round body double needle, 9.3mm 60cms. Code: W 8712 </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd34" value="<?php echo (isset($formVars['qd34']))?$formVars['qd34']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd34" value="<?php echo (isset($formVars['rd34']))?$formVars['rd34']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-480" name="sd35" <?php markSelCheckBox('sd35',$formVars); ?>><label class="form-check-label" for="formCheck-28">34</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 6-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 6-O Circle Round body&nbsp;double needle,10mm/13mm, 60 cms. Code: W 8597/ VP 706X</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-481" name="cd35-1" <?php markCompCheckBox('cd35-1',$formVars); ?>><label class="form-check-label" for="formCheck-67">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-482" name="cd35-2" <?php markCompCheckBox('cd35-2',$formVars); ?>><label class="form-check-label" for="formCheck-67">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd35" value="<?php echo (isset($formVars['qd35']))?$formVars['qd35']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd35" value="<?php echo (isset($formVars['rd35']))?$formVars['rd35']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-484" name="sd36" <?php markSelCheckBox('sd36',$formVars); ?>><label class="form-check-label" for="formCheck-28">35</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 7-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 7-O 3/8 circle curved round body taper point double needle BV 175-6, 7.6 mm/ 8 mm, 60 cm Code: 8735H/ VP 630X</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-485" name="cd36-1" <?php markCompCheckBox('cd36-1',$formVars); ?>><label class="form-check-label" for="formCheck-69">ETHICON</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-493" name="cd36-2" <?php markCompCheckBox('cd36-2',$formVars); ?>><label class="form-check-label" for="formCheck-69">TYCO</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd36" value="<?php echo (isset($formVars['qd36']))?$formVars['qd36']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd36" value="<?php echo (isset($formVars['rd36']))?$formVars['rd36']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-494" name="sd37" <?php markSelCheckBox('sd37',$formVars); ?>><label class="form-check-label" for="formCheck-28">36</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLENE 7-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 7-O 3/8 Circle&nbsp;taper point double needle BV 175-6, 8mm, 60cms. Code: EP 8735H </label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd37" value="<?php echo (isset($formVars['qd37']))?$formVars['qd37']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd37" value="<?php echo (isset($formVars['rd37']))?$formVars['rd37']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-495" name="sd38" <?php markSelCheckBox('sd38',$formVars); ?>><label class="form-check-label" for="formCheck-28">37</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">PROLINE 8-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">PROLENE 8-O 3/8 circle taper point double needle BV 175-6, 8 mm, 60 cm. Code: 8741H</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd38" value="<?php echo (isset($formVars['qd38']))?$formVars['qd38']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd38" value="<?php echo (isset($formVars['rd38']))?$formVars['rd38']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-496" name="sd39" <?php markSelCheckBox('sd39',$formVars); ?>><label class="form-check-label" for="formCheck-28">38</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 1-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 1-O Round Body, Code: NW 5332</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd39" value="<?php echo (isset($formVars['qd39']))?$formVars['qd39']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd39" value="<?php echo (isset($formVars['rd39']))?$formVars['rd39']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-584" name="sd40" <?php markSelCheckBox('sd40',$formVars); ?>><label class="form-check-label" for="formCheck-28">39</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 1-O reverse cutting<br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 1-O reverse cutting, Code: NW 5037</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd40" value="<?php echo (isset($formVars['qd40']))?$formVars['qd40']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd40" value="<?php echo (isset($formVars['rd40']))?$formVars['rd40']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-585" name="sd41" <?php markSelCheckBox('sd41',$formVars); ?>><label class="form-check-label" for="formCheck-28">40</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 2-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 2-O Round Body, Code:&nbsp;NW 5331</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd41" value="<?php echo (isset($formVars['qd41']))?$formVars['qd41']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd41" value="<?php echo (isset($formVars['rd41']))?$formVars['rd41']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-586" name="sd42" <?php markSelCheckBox('sd42',$formVars); ?>><label class="form-check-label" for="formCheck-28">41</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 2-O reverse cutting <br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 2-O reverse cutting, Code: NW 5036</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd42" value="<?php echo (isset($formVars['qd42']))?$formVars['qd42']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd42" value="<?php echo (isset($formVars['rd42']))?$formVars['rd42']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-587" name="sd43" <?php markSelCheckBox('sd43',$formVars); ?>><label class="form-check-label" for="formCheck-28">42</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 3-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 3-O round body 25 mm needle Code; NW 5087</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd43" value="<?php echo (isset($formVars['qd43']))?$formVars['qd43']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd43 value="<?php echo (isset($formVars['rd43']))?$formVars['rd43']:''; ?>""></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-588" name="sd44" <?php markSelCheckBox('sd44',$formVars); ?>><label class="form-check-label" for="formCheck-28">43</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 3-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 3-O round body 20 mm needle Code:&nbsp;NW 5085</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd44" value="<?php echo (isset($formVars['qd44']))?$formVars['qd44']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd44" value="<?php echo (isset($formVars['rd44']))?$formVars['rd44']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-589" name="sd45" <?php markSelCheckBox('sd45',$formVars); ?>><label class="form-check-label" for="formCheck-28">44</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK 3-O reverse cutting<br></label></div>
                    <div class="col-3"><label class="col-form-label">MERSILK 3-O reverse cutting, Code: NW 5028</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd45" value="<?php echo (isset($formVars['qd45']))?$formVars['qd45']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd45" value="<?php echo (isset($formVars['rd45']))?$formVars['rd45']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-590" name="sd46" <?php markSelCheckBox('sd46',$formVars); ?>><label class="form-check-label" for="formCheck-28">45</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK SUTUPACK 3-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">SILK SUTUPACK 3-O code: SW 222</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd46" value="<?php echo (isset($formVars['qd46']))?$formVars['qd46']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd46" value="<?php echo (isset($formVars['rd46']))?$formVars['rd46']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-591" name="sd47" <?php markSelCheckBox('sd47',$formVars); ?>><label class="form-check-label" for="formCheck-24">46</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">SILK REEL NO. 1</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">R825</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd47" value="<?php echo (isset($formVars['qd47']))?$formVars['qd47']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rd47"><?php markRemarkTextA('rd47',$formVars); ?></textarea></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-592" name="sd48" <?php markSelCheckBox('sd48',$formVars); ?>><label class="form-check-label" for="formCheck-28">47</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK REEL NO. 2<br></label></div>
                    <div class="col-3"><label class="col-form-label">R826</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd48" value="<?php echo (isset($formVars['qd48']))?$formVars['qd48']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd48" value="<?php echo (isset($formVars['rd48']))?$formVars['rd48']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-593" name="sd49" <?php markSelCheckBox('sd49',$formVars); ?>><label class="form-check-label" for="formCheck-28">48</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">SILK REEL NO. 1-O<br></label></div>
                    <div class="col-3"><label class="col-form-label">R824</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd49" value="<?php echo (isset($formVars['qd49']))?$formVars['qd49']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd49" value="<?php echo (isset($formVars['rd49']))?$formVars['rd49']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-594" name="sd50" <?php markSelCheckBox('sd50',$formVars); ?>><label class="form-check-label" for="formCheck-28">49</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Umblical tape<br></label></div>
                    <div class="col-3"><label class="col-form-label">W2760</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd50" value="<?php echo (isset($formVars['qd50']))?$formVars['qd50']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd50" value="<?php echo (isset($formVars['rd50']))?$formVars['rd50']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-595" name="sd51" <?php markSelCheckBox('sd51',$formVars); ?>><label class="form-check-label" for="formCheck-28">50</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">VICRYL 1-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">VICRYL 1-O round body, Code: NW 2546/2346</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd51" value="<?php echo (isset($formVars['qd51']))?$formVars['qd51']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd51" value="<?php echo (isset($formVars['rd51']))?$formVars['rd51']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-596" name="sd52" <?php markSelCheckBox('sd52',$formVars); ?>><label class="form-check-label" for="formCheck-28">51</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">VICRYL 2-O&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">NW 2341/2317</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd52" value="<?php echo (isset($formVars['qd52']))?$formVars['qd52']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd52" value="<?php echo (isset($formVars['rd52']))?$formVars['rd52']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-597" name="sd53" <?php markSelCheckBox('sd53',$formVars); ?>><label class="form-check-label" for="formCheck-28">52</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">VICRYL 3-O <br></label></div>
                    <div class="col-3"><label class="col-form-label">VICRYL 3-O round body, Code: NW 2437</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd53" value="<?php echo (isset($formVars['qd53']))?$formVars['qd53']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="rd53" value="<?php echo (isset($formVars['rd53']))?$formVars['rd53']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-598" name="sd54" <?php markSelCheckBox('sd54',$formVars); ?>><label class="form-check-label" for="formCheck-24">53</label></div>
                    </div>
                    <div class="col-2"><div><label width="100%" class="col-form-label">VICRYL rapide 3-O</label></div></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">VICRYL rapide 3-o cutting undyed, Code:&nbsp;W9932</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">ETHICON</label></div>
                    <div class="col-1" style="margin-bottom: 5px;margin-top: 5px;">

<div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qd54" value="<?php echo (isset($formVars['qd54']))?$formVars['qd54']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><textarea style="width:100%" rows="2" name="rd54"><?php markRemarkTextA('rd54',$formVars); ?></textarea></div>
                </div>
            </div>
            <div class="container">
                <div>
                    <p></p>
                    <p>You have reached end of section-D. Move to next Tab for next section.&nbsp;</p>
                    <p><strong><em>Changes are saved automatically.</em></strong></p>
                    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                        <div class="container"><a class="navbar-brand text-info" href="#" style="font-size: 18px;"><span style="text-decoration: underline;">Jump to Top of this page</span></a><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="tab-pane" role="tabpanel" id="tab-5">
            <p></p>
            <h2 class="text-left" style="font-size: 26;">&nbsp; &nbsp;Drugs/Medicines</h2>
            <p>&nbsp; &nbsp; &nbsp; Mark the<strong>&nbsp;Items</strong> Required and go to next Tab for next section.</p>
            <div class="container">
                <div class="row">
                    <div class="col-1"><label class="col-form-label"><strong>Sr.No</strong></label></div>
                    <div class="col-2"><label class="col-form-label"><strong>Drug Name</strong><br></label></div>
                    <div class="col-3"><label class="col-form-label"><strong>Strength</strong><br></label></div>
                    <div class="col-3"><label class="col-form-label"><strong>Brand/ Company Name Preferred&nbsp;</strong></label></div>
                    <div class="col-1"><label class="col-form-label"><strong>Qty Required&nbsp;</strong></label></div>
                    <div class="col-2"><label class="col-form-label"><strong>Remarks</strong><br></label></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-277" name="se1" <?php markSelCheckBox('se1',$formVars); ?>><label class="form-check-label" for="formCheck-28">1</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Amino Acid sol.<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-278" name="ce1-1" <?php markCompCheckBox('ce1-1',$formVars); ?>><label class="form-check-label" for="formCheck-55">Claris</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-281" name="ce1-2" <?php markCompCheckBox('ce1-2',$formVars); ?>><label class="form-check-label" for="formCheck-56">Fresnius Kabi</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe1" value="<?php echo (isset($formVars['qe1']))?$formVars['qe1']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re1" value="<?php echo (isset($formVars['re1']))?$formVars['re1']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-282" name="se2" <?php markSelCheckBox('se2',$formVars); ?>><label class="form-check-label" for="formCheck-28">2</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Dextrose<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-283" name="de2-1" <?php markSelCheckBox('de2-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">5%</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-284" name="de2-2" <?php markSelCheckBox('de2-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">10%</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-285" name="de2-3" <?php markSelCheckBox('de2-3',$formVars); ?>><label class="form-check-label" for="formCheck-3">25%</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Baxter</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe2-1" value="<?php echo (isset($formVars['qe2-1']))?$formVars['qe2-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe2-2" value="<?php echo (isset($formVars['qe2-2']))?$formVars['qe2-2']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe2-3" value="<?php echo (isset($formVars['qe2-3']))?$formVars['qe2-3']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re2" value="<?php echo (isset($formVars['re2']))?$formVars['re2']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-286" name="se3" <?php markSelCheckBox('se3',$formVars); ?>><label class="form-check-label" for="formCheck-28">3</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Plasma Lyte A<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe3" value="<?php echo (isset($formVars['qe3']))?$formVars['qe3']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re3" value="<?php echo (isset($formVars['re3']))?$formVars['re3']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-287" name="se4" <?php markSelCheckBox('se4',$formVars); ?>><label class="form-check-label" for="formCheck-28">4</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Normal Saline<br></label></div>
                    <div class="col-3"><label class="col-form-label">Plastic Bottle, 500ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe4" value="<?php echo (isset($formVars['qe4']))?$formVars['qe4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re4" value="<?php echo (isset($formVars['re4']))?$formVars['re4']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-288" name="se5" <?php markSelCheckBox('se5',$formVars); ?>><label class="form-check-label" for="formCheck-28">5</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Normal Saline<br></label></div>
                    <div class="col-3"><label class="col-form-label">Bag, 500ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Baxter</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe5" value="<?php echo (isset($formVars['qe5']))?$formVars['qe5']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re5" value="<?php echo (isset($formVars['re5']))?$formVars['re5']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-289" name="se6" <?php markSelCheckBox('se6',$formVars); ?>><label class="form-check-label" for="formCheck-28">6</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Ringer Lactate<br></label></div>
                    <div class="col-3"><label class="col-form-label">Plastic Bottle, 500ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-290" name="ce6-1" <?php markCompCheckBox('ce6-1',$formVars); ?>><label class="form-check-label" for="formCheck-17">Baxter</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-291" name="ce6-2" <?php markCompCheckBox('ce6-2',$formVars); ?>><label class="form-check-label" for="formCheck-17">Clairs</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-292" name="ce6-3" <?php markCompCheckBox('ce6-3',$formVars); ?>><label class="form-check-label" for="formCheck-17">SKKL</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe6" value="<?php echo (isset($formVars['qe6']))?$formVars['qe6']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re6" value="<?php echo (isset($formVars['re6']))?$formVars['re6']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-296" name="se7" <?php markSelCheckBox('se7',$formVars); ?>><label class="form-check-label" for="formCheck-28">7</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Inj. Ringer Lactate<br></label></div>
                    <div class="col-3"><label class="col-form-label">Bag, 500ml</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe7" value="<?php echo (isset($formVars['qe7']))?$formVars['qe7']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re7" value="<?php echo (isset($formVars['re7']))?$formVars['re7']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-297" name="se8" <?php markSelCheckBox('se8',$formVars); ?>><label class="form-check-label" for="formCheck-28">8</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Adenosine Inj.<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-307" name="de8-1" <?php markSelCheckBox('de8-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">6mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-308" name="de8-2" <?php markSelCheckBox('de8-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">12mg</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-309" name="ce8-1" <?php markCompCheckBox('ce8-1',$formVars); ?>><label class="form-check-label" for="formCheck-57">Neon</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-310" name="ce8-2" <?php markCompCheckBox('ce8-2',$formVars); ?>><label class="form-check-label" for="formCheck-57">Toirrent</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-311" name="ce8-3" <?php markCompCheckBox('ce8-3',$formVars); ?>><label class="form-check-label" for="formCheck-57">Sun</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe8-1" value="<?php echo (isset($formVars['qe8-1']))?$formVars['qe8-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe8-2" value="<?php echo (isset($formVars['qe8-2']))?$formVars['qe8-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re8" value="<?php echo (isset($formVars['re8']))?$formVars['re8']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-312" name="se9" <?php markSelCheckBox('se9',$formVars); ?>><label class="form-check-label" for="formCheck-28">9</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Albumin Inj. 20%<br></label></div>
                    <div class="col-3"><label class="col-form-label">100mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-313" name="ce9-1" <?php markCompCheckBox('ce9-1',$formVars); ?>><label class="form-check-label" for="formCheck-17">Bharat Serum</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-314" name="ce9-2" <?php markCompCheckBox('ce9-2',$formVars); ?>><label class="form-check-label" for="formCheck-17">Reliance</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe9" value="<?php echo (isset($formVars['qe9']))?$formVars['qe9']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re9" value="<?php echo (isset($formVars['re9']))?$formVars['re9']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-315" name="se10" <?php markSelCheckBox('se10',$formVars); ?>><label class="form-check-label" for="formCheck-28">10</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Amikacin Inj.<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-316" name="de10-1" <?php markSelCheckBox('de10-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">100mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-317" name="de10-2" <?php markSelCheckBox('de10-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">500mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-318" name="de10-3" <?php markSelCheckBox('de10-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">1g</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-319" name="ce10-1" <?php markCompCheckBox('ce10-1',$formVars); ?>><label class="form-check-label" for="formCheck-57">Bristol-Myers</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-320" name="ce10-2" <?php markCompCheckBox('ce10-2',$formVars); ?>><label class="form-check-label" for="formCheck-57">Cipla</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe10-1" value="<?php echo (isset($formVars['qe10-1']))?$formVars['qe10-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe10-2" value="<?php echo (isset($formVars['qe10-2']))?$formVars['qe10-2']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe10-3" value="<?php echo (isset($formVars['qe10-3']))?$formVars['qe10-3']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re10" value="<?php echo (isset($formVars['re10']))?$formVars['re10']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-321" name="se11" <?php markSelCheckBox('se11',$formVars); ?>><label class="form-check-label" for="formCheck-28">11</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Aminocaproic Acid Inj (Hemostat)<br></label></div>
                    <div class="col-3"><label class="col-form-label">250mg/ml(20ml)</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-322" name="ce11-1" <?php markCompCheckBox('ce11-1',$formVars); ?>><label class="form-check-label" for="formCheck-17">GSK</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-323" name="ce11-2" <?php markCompCheckBox('ce11-2',$formVars); ?>><label class="form-check-label" for="formCheck-17">Samarth Pharma</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe11" value="<?php echo (isset($formVars['qe11']))?$formVars['qe11']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re11" value="<?php echo (isset($formVars['re11']))?$formVars['re11']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-324" name="se12" <?php markSelCheckBox('se12',$formVars); ?>><label class="form-check-label" for="formCheck-28">12</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Amiodarone Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">50mg/ml-3ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-325" name="ce12-1" <?php markCompCheckBox('ce12-1',$formVars); ?>><label class="form-check-label" for="formCheck-17">Cipla</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-326" name="ce12-2" <?php markCompCheckBox('ce12-2',$formVars); ?>><label class="form-check-label" for="formCheck-17">Sanofi</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-327" name="ce12-3" <?php markCompCheckBox('ce12-3',$formVars); ?>><label class="form-check-label" for="formCheck-17">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe12" value="<?php echo (isset($formVars['qe12']))?$formVars['qe12']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re12" value="<?php echo (isset($formVars['re12']))?$formVars['re12']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-328" name="se13" <?php markSelCheckBox('se13',$formVars); ?>><label class="form-check-label" for="formCheck-28">13</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Atracurium Inj&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">10mg/ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-329" name="ce13-1" <?php markCompCheckBox('ce13-1',$formVars); ?>><label class="form-check-label" for="formCheck-17">Sanartg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-330" name="ce13-2" <?php markCompCheckBox('ce13-2',$formVars); ?>><label class="form-check-label" for="formCheck-17">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe13" value="<?php echo (isset($formVars['qe13']))?$formVars['qe13']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re13" value="<?php echo (isset($formVars['re13']))?$formVars['re13']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-331" name="se14" <?php markSelCheckBox('se14',$formVars); ?>><label class="form-check-label" for="formCheck-28">14</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Bupivacaine/Rupivac&nbsp;Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-332" name="de14-1" <?php markSelCheckBox('de14-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">0.25% Plain 4mL</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-333" name="de14-2" <?php markSelCheckBox('de14-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">0.5% heavy 20mL</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-334" name="ce14-1" <?php markCompCheckBox('ce14-1',$formVars); ?>><label class="form-check-label" for="formCheck-13">AstraZeneca&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-335" name="ce14-2" <?php markCompCheckBox('ce14-2',$formVars); ?>><label class="form-check-label" for="formCheck-13">Sun</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-336" name="ce14-3" <?php markCompCheckBox('ce14-3',$formVars); ?>><label class="form-check-label" for="formCheck-13">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe14-1" value="<?php echo (isset($formVars['qe14-1']))?$formVars['qe14-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe14-2" value="<?php echo (isset($formVars['qe14-2']))?$formVars['qe14-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re14" value="<?php echo (isset($formVars['re14']))?$formVars['re14']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-337" name="se15" <?php markSelCheckBox('se15',$formVars); ?>><label class="form-check-label" for="formCheck-28">15</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Cefazolin<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-338" name="de15-1" <?php markSelCheckBox('de15-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">250mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-339" name="de15-2" <?php markSelCheckBox('de15-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">500mg</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-340" name="ce15-1" <?php markCompCheckBox('ce15-1',$formVars); ?>><label class="form-check-label" for="formCheck-13">Cadilla&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-341" name="ce15-2" <?php markCompCheckBox('ce15-2',$formVars); ?>><label class="form-check-label" for="formCheck-13">Lupin</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-342" name="ce15-3" <?php markCompCheckBox('ce15-3',$formVars); ?>><label class="form-check-label" for="formCheck-13">Ranbaxy</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe15-1" value="<?php echo (isset($formVars['qe15-1']))?$formVars['qe15-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe15-2" value="<?php echo (isset($formVars['qe15-2']))?$formVars['qe15-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re15" value="<?php echo (isset($formVars['re15']))?$formVars['re15']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-343" name="se16" <?php markSelCheckBox('se16',$formVars); ?>><label class="form-check-label" for="formCheck-28">16</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Cefuroxime Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-344" name="de16-1" <?php markSelCheckBox('de16-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">250mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-345" name="de16-2" <?php markSelCheckBox('de16-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">500mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-346" name="de16-3" <?php markSelCheckBox('de16-3',$formVars); ?>><label class="form-check-label" for="formCheck-1">750mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-347" name="de16-4" <?php markSelCheckBox('de16-4',$formVars); ?>><label class="form-check-label" for="formCheck-1">1.5g</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-348" name="ce16-1" <?php markCompCheckBox('ce16-1',$formVars); ?>><label class="form-check-label" for="formCheck-13">Glenmark&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-349" name="ce16-2" <?php markCompCheckBox('ce16-2',$formVars); ?>><label class="form-check-label" for="formCheck-13">Glaxo</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-350" name="ce16-3" <?php markCompCheckBox('ce16-3',$formVars); ?>><label class="form-check-label" for="formCheck-13">Aristo</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-351" name="ce16-4" <?php markCompCheckBox('ce16-4',$formVars); ?>><label class="form-check-label" for="formCheck-13">Supacef</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe16-1" value="<?php echo (isset($formVars['qe16-1']))?$formVars['qe16-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe16-2" value="<?php echo (isset($formVars['qe16-2']))?$formVars['qe16-2']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe16-3" value="<?php echo (isset($formVars['qe16-3']))?$formVars['qe16-3']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe16-4" value="<?php echo (isset($formVars['qe16-4']))?$formVars['qe16-4']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re16" value="<?php echo (isset($formVars['re16']))?$formVars['re16']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-352" name="se17" <?php markSelCheckBox('se17',$formVars); ?>><label class="form-check-label" for="formCheck-28">17</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Cefipime Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-353" name="de17-1" <?php markSelCheckBox('de17-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">500mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-354" name="de17-2" <?php markSelCheckBox('de17-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">1g</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-355" name="ce17-1" <?php markCompCheckBox('ce17-1',$formVars); ?>><label class="form-check-label" for="formCheck-13">Bristol-Myers&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-356" name="ce17-2" <?php markCompCheckBox('ce17-2',$formVars); ?>><label class="form-check-label" for="formCheck-13">Cipla</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-357" name="ce17-3" <?php markCompCheckBox('ce17-3',$formVars); ?>><label class="form-check-label" for="formCheck-13">Cadilla</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe17-1" value="<?php echo (isset($formVars['qe17-1']))?$formVars['qe17-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe17-2" value="<?php echo (isset($formVars['qe17-2']))?$formVars['qe17-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re17" value="<?php echo (isset($formVars['re17']))?$formVars['re17']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-358" name="se18" <?php markSelCheckBox('se18',$formVars); ?>><label class="form-check-label" for="formCheck-28">18</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Ceftizoxime Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-359" name="de18-1" <?php markSelCheckBox('de18-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">500mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-360" name="de18-2" <?php markSelCheckBox('de18-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">1g</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-599" name="ce18-1" <?php markCompCheckBox('ce18-1',$formVars); ?>><label class="form-check-label" for="formCheck-13">Elder</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-600" name="ce18-2" <?php markCompCheckBox('ce18-2',$formVars); ?>><label class="form-check-label" for="formCheck-13">GSK</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe18-1" value="<?php echo (isset($formVars['qe18-1']))?$formVars['qe18-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe18-2" value="<?php echo (isset($formVars['qe18-2']))?$formVars['qe18-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re18" value="<?php echo (isset($formVars['re18']))?$formVars['re18']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-601" name="se19" <?php markSelCheckBox('se19',$formVars); ?>><label class="form-check-label" for="formCheck-28">19</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Diltiazem Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">25mg/5ml</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Torrent</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe19" value="<?php echo (isset($formVars['qe19']))?$formVars['qe19']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re19" value="<?php echo (isset($formVars['re19']))?$formVars['re19']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-602" name="se20" <?php markSelCheckBox('se20',$formVars); ?>><label class="form-check-label" for="formCheck-28">20</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Dexmed Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">200mg/2ml</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Neon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe20" value="<?php echo (isset($formVars['qe20']))?$formVars['qe20']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re20" value="<?php echo (isset($formVars['re20']))?$formVars['re20']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-603" name="se21" <?php markSelCheckBox('se21',$formVars); ?>><label class="form-check-label" for="formCheck-28">21</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Dobutamine Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">250mg/5ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-604" name="ce21-1" <?php markCompCheckBox('ce21-1',$formVars); ?>><label class="form-check-label" for="formCheck-81">Torrent</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-605" name="ce21-2" <?php markCompCheckBox('ce21-2',$formVars); ?>><label class="form-check-label" for="formCheck-81">Sun</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-606" name="ce21-3" <?php markCompCheckBox('ce21-3',$formVars); ?>><label class="form-check-label" for="formCheck-81">Samarth</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-607" name="ce21-4" <?php markCompCheckBox('ce21-4',$formVars); ?>><label class="form-check-label" for="formCheck-81">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe21" value="<?php echo (isset($formVars['qe21']))?$formVars['qe21']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re21" value="<?php echo (isset($formVars['re21']))?$formVars['re21']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-608" name="se22" <?php markSelCheckBox('se22',$formVars); ?>><label class="form-check-label" for="formCheck-28">22</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Duolin Puff<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Cipla</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe22" value="<?php echo (isset($formVars['qe22']))?$formVars['qe22']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re22" value="<?php echo (isset($formVars['re22']))?$formVars['re22']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-609" name="se23" <?php markSelCheckBox('se23',$formVars); ?>><label class="form-check-label" for="formCheck-28">23</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Esmolol Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">10mg/ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-610" name="ce23-1" <?php markCompCheckBox('ce23-1',$formVars); ?>><label class="form-check-label" for="formCheck-81">Neon</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-611" name="ce23-2" <?php markCompCheckBox('ce23-2',$formVars); ?>><label class="form-check-label" for="formCheck-81">USV</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe23" value="<?php echo (isset($formVars['qe23']))?$formVars['qe23']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re23" value="<?php echo (isset($formVars['re23']))?$formVars['re23']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-612" name="se24" <?php markSelCheckBox('se24',$formVars); ?>><label class="form-check-label" for="formCheck-28">24</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Glycopyrrolate Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">0.2mg/ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-613" name="ce24-1" <?php markCompCheckBox('ce24-1',$formVars); ?>><label class="form-check-label" for="formCheck-81">Neon</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-614" name="ce24-2" <?php markCompCheckBox('ce24-2',$formVars); ?>><label class="form-check-label" for="formCheck-81">Piramal</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe24" value="<?php echo (isset($formVars['qe24']))?$formVars['qe24']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re24" value="<?php echo (isset($formVars['re24']))?$formVars['re24']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-615" name="se25" <?php markSelCheckBox('se25',$formVars); ?>><label class="form-check-label" for="formCheck-28">25</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Heparin Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-616" name="de25-1" <?php markSelCheckBox('de25-1',$formVars); ?>><label class="form-check-label" for="formCheck-1">5000U</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-617" name="de25-2" <?php markSelCheckBox('de25-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">25000U</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-618" name="ce25-1" <?php markCompCheckBox('ce25-1',$formVars); ?>><label class="form-check-label" for="formCheck-13">Gland</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-619" name="ce25-2" <?php markCompCheckBox('ce25-2',$formVars); ?>><label class="form-check-label" for="formCheck-13">Neon</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-620" name="ce25-3" <?php markCompCheckBox('ce25-3',$formVars); ?>><label class="form-check-label" for="formCheck-13">VHB</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-621" name="ce25-4" <?php markCompCheckBox('ce25-4',$formVars); ?>><label class="form-check-label" for="formCheck-13">Abbot</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe25-1" value="<?php echo (isset($formVars['qe25-1']))?$formVars['qe25-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe25-2" value="<?php echo (isset($formVars['qe25-2']))?$formVars['qe25-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re25" value="<?php echo (isset($formVars['re25']))?$formVars['re25']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-622" name="se26" <?php markSelCheckBox('se26',$formVars); ?>><label class="form-check-label" for="formCheck-28">26</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Insulin Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">-</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe26" value="<?php echo (isset($formVars['qe26']))?$formVars['qe26']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re26" value="<?php echo (isset($formVars['re26']))?$formVars['re26']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-623" name="se27" <?php markSelCheckBox('se27',$formVars); ?>><label class="form-check-label" for="formCheck-28">27</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Ketamine Inj.<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-624" name="de27-1" <?php markSelCheckBox('de27-1',$formVars); ?>><label class="form-check-label" for="formCheck-2">10mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-625" name="de27-2" <?php markSelCheckBox('de27-2',$formVars); ?>><label class="form-check-label" for="formCheck-1">50mg</label></div>
                    </div>
                    <div class="col-3">
                        <div></div><label style="width:100%">Neon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe27" value="<?php echo (isset($formVars['qe27']))?$formVars['qe27']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re27" value="<?php echo (isset($formVars['re27']))?$formVars['re27']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-626" name="se28" <?php markSelCheckBox('se28',$formVars); ?>><label class="form-check-label" for="formCheck-28">28</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Labetolol Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">5mg/2mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-627" name="ce28-1" <?php markCompCheckBox('ce28-1',$formVars); ?>><label class="form-check-label" for="formCheck-81">Mercury Lab</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-628" name="ce28-2" <?php markCompCheckBox('ce28-2',$formVars); ?>><label class="form-check-label" for="formCheck-81">Samarth</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe28" value="<?php echo (isset($formVars['qe28']))?$formVars['qe28']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re28" value="<?php echo (isset($formVars['re28']))?$formVars['re28']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-629" name="se29" <?php markSelCheckBox('se29',$formVars); ?>><label class="form-check-label" for="formCheck-28">29</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Levosimendan<br></label></div>
                    <div class="col-3"><label class="col-form-label">10mg/mL</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe29" value="<?php echo (isset($formVars['qe29']))?$formVars['qe29']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re29" value="<?php echo (isset($formVars['re29']))?$formVars['re29']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-630" name="se30" <?php markSelCheckBox('se30',$formVars); ?>><label class="form-check-label" for="formCheck-28">30</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Lignocain Spray&nbsp;<br></label></div>
                    <div class="col-3"><label class="col-form-label">10%</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Neon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe30" value="<?php echo (isset($formVars['qe30']))?$formVars['qe30']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re30" value="<?php echo (isset($formVars['re30']))?$formVars['re30']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-631" name="se31" <?php markSelCheckBox('se31',$formVars); ?>><label class="form-check-label" for="formCheck-28">31</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Magnesium Sulphate Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">500mg/mL</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Neon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe31" value="<?php echo (isset($formVars['qe31']))?$formVars['qe31']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re31" value="<?php echo (isset($formVars['re31']))?$formVars['re31']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-632" name="se32" <?php markSelCheckBox('se32',$formVars); ?>><label class="form-check-label" for="formCheck-28">32</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Mannitol Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">20%-100mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-633" name="ce32-1" <?php markCompCheckBox('ce32-1',$formVars); ?>><label class="form-check-label" for="formCheck-59">Cadilla</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-634" name="ce32-2" <?php markCompCheckBox('ce32-2',$formVars); ?>><label class="form-check-label" for="formCheck-59">Venus&nbsp;</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe32" value="<?php echo (isset($formVars['qe32']))?$formVars['qe32']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re32" value="<?php echo (isset($formVars['re32']))?$formVars['re32']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-635" name="se33" <?php markSelCheckBox('se33',$formVars); ?>><label class="form-check-label" for="formCheck-28">33</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Metaprolol Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">1mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-636" name="ce33-1" <?php markCompCheckBox('ce33-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">AstraZeneca&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-637" name="ce33-2" <?php markCompCheckBox('ce33-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Cipla</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-638" name="ce33-3" <?php markCompCheckBox('ce33-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Dr.Reddy</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe33" value="<?php echo (isset($formVars['qe33']))?$formVars['qe33']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re33" value="<?php echo (isset($formVars['re33']))?$formVars['re33']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-639" name="se34" <?php markSelCheckBox('se34',$formVars); ?>><label class="form-check-label" for="formCheck-28">34</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Metoclopramide Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">1mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-640" name="ce34-1" <?php markCompCheckBox('ce34-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">AstraZeneca&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-641" name="ce34-2" <?php markCompCheckBox('ce34-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">IPCA</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe34" value="<?php echo (isset($formVars['qe34']))?$formVars['qe34']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re34" value="<?php echo (isset($formVars['re34']))?$formVars['re34']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-642" name="se35" <?php markSelCheckBox('se35',$formVars); ?>><label class="form-check-label" for="formCheck-28">35</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Midazolam Inj Ampule<br></label></div>
                    <div class="col-3"><label class="col-form-label">5mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-643" name="ce35-1" <?php markCompCheckBox('ce35-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Ranbaxy&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-644" name="ce35-2" <?php markCompCheckBox('ce35-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe35" value="<?php echo (isset($formVars['qe35']))?$formVars['qe35']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re35" value="<?php echo (isset($formVars['re35']))?$formVars['re35']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-645" name="se36" <?php markSelCheckBox('se36',$formVars); ?>><label class="form-check-label" for="formCheck-28">36</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Milrinone Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">1mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-646" name="ce36-1" <?php markCompCheckBox('ce36-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-647" name="ce36-2" <?php markCompCheckBox('ce36-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Cleon</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-648" name="ce36-3" <?php markCompCheckBox('ce36-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Samarth</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-649" name="ce36-4" <?php markCompCheckBox('ce36-4',$formVars); ?>><label class="form-check-label" for="formCheck-61">VHB</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe36" value="<?php echo (isset($formVars['qe36']))?$formVars['qe36']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re36" value="<?php echo (isset($formVars['re36']))?$formVars['re36']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-650" name="se37" <?php markSelCheckBox('se37',$formVars); ?>><label class="form-check-label" for="formCheck-28">37</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Neostigmine+ Glycopyrrolate Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">0.5mg+2.5mg</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Neon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe37" value="<?php echo (isset($formVars['qe37']))?$formVars['qe37']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re37" value="<?php echo (isset($formVars['re37']))?$formVars['re37']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-651" name="se38" <?php markSelCheckBox('se38',$formVars); ?>><label class="form-check-label" for="formCheck-28">38</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Nitroglycerine Inj (NTG)<br></label></div>
                    <div class="col-3"><label class="col-form-label">5mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-652" name="ce38-1" <?php markCompCheckBox('ce38-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-653" name="ce38-2" <?php markCompCheckBox('ce38-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Samarth</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe38" value="<?php echo (isset($formVars['qe38']))?$formVars['qe38']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re38" value="<?php echo (isset($formVars['re38']))?$formVars['re38']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-654" name="se39" <?php markSelCheckBox('se39',$formVars); ?>><label class="form-check-label" for="formCheck-28">39</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Pancuronium Bromide Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">2mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-655" name="ce39-1" <?php markCompCheckBox('ce39-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-656" name="ce39-2" <?php markCompCheckBox('ce39-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Piramal</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe39" value="<?php echo (isset($formVars['qe39']))?$formVars['qe39']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re39" value="<?php echo (isset($formVars['re39']))?$formVars['re39']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-657" name="se40" <?php markSelCheckBox('se40',$formVars); ?>><label class="form-check-label" for="formCheck-28">40</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Paracetamol Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">150mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-658" name="ce40-1" <?php markCompCheckBox('ce40-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Lupin&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-659" name="ce40-2" <?php markCompCheckBox('ce40-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe40" value="<?php echo (isset($formVars['qe40']))?$formVars['qe40']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re40" value="<?php echo (isset($formVars['re40']))?$formVars['re40']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-660" name="se41" <?php markSelCheckBox('se41',$formVars); ?>><label class="form-check-label" for="formCheck-28">41</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Phenylephrine Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">10mg/mL</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Neon</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe41" value="<?php echo (isset($formVars['qe41']))?$formVars['qe41']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re41" value="<?php echo (isset($formVars['re41']))?$formVars['re41']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-661" name="se42" <?php markSelCheckBox('se42',$formVars); ?>><label class="form-check-label" for="formCheck-28">42</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Priperacillin Tazobactum Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">4.5mg</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-662" name="ce42-1" <?php markCompCheckBox('ce42-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Cipla&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-663" name="ce42-2" <?php markCompCheckBox('ce42-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Lupin&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-664" name="ce42-3" <?php markCompCheckBox('ce42-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">VHB</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe42" value="<?php echo (isset($formVars['qe42']))?$formVars['qe42']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re42" value="<?php echo (isset($formVars['re42']))?$formVars['re42']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-665" name="se43" <?php markSelCheckBox('se43',$formVars); ?>><label class="form-check-label" for="formCheck-28">43</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Plegiocard Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">20mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-666" name="ce43-1" <?php markCompCheckBox('ce43-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Samarth&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-667" name="ce43-2" <?php markCompCheckBox('ce43-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe43" value="<?php echo (isset($formVars['qe43']))?$formVars['qe43']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re43" value="<?php echo (isset($formVars['re43']))?$formVars['re43']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-668" name="se44" <?php markSelCheckBox('se44',$formVars); ?>><label class="form-check-label" for="formCheck-28">44</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Potassium Chloride Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">20mg/10mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-669" name="ce44-1" <?php markCompCheckBox('ce44-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Baxter&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-670" name="ce44-2" <?php markCompCheckBox('ce44-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe44" value="<?php echo (isset($formVars['qe44']))?$formVars['qe44']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re44" value="<?php echo (isset($formVars['re44']))?$formVars['re44']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-671" name="se45" <?php markSelCheckBox('se45',$formVars); ?>><label class="form-check-label" for="formCheck-28">45</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Propofol Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">10mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-672" name="ce45-1" <?php markCompCheckBox('ce45-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Fresnius Kabi&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-673" name="ce45-2" <?php markCompCheckBox('ce45-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe45" value="<?php echo (isset($formVars['qe45']))?$formVars['qe45']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re45" value="<?php echo (isset($formVars['re45']))?$formVars['re45']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-674" name="se46" <?php markSelCheckBox('se46',$formVars); ?>><label class="form-check-label" for="formCheck-28">46</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Protamine Inj.<br></label></div>
                    <div class="col-3"><label class="col-form-label">10mg/mL</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">Samarth</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe46" value="<?php echo (isset($formVars['qe46']))?$formVars['qe46']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re46" value="<?php echo (isset($formVars['re46']))?$formVars['re46']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-675" name="se47" <?php markSelCheckBox('se47',$formVars); ?>><label class="form-check-label" for="formCheck-28">47</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Ranitidine Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">25mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-676" name="ce47-1" <?php markCompCheckBox('ce47-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Cadilla&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-677" name="ce47-2" <?php markCompCheckBox('ce47-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">GSK</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe47" value="<?php echo (isset($formVars['qe47']))?$formVars['qe47']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re47" value="<?php echo (isset($formVars['re47']))?$formVars['re47']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-678" name="se48" <?php markSelCheckBox('se48',$formVars); ?>><label class="form-check-label" for="formCheck-28">48</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Soda-Bicarbonate Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">4%</label></div>
                    <div class="col-3">
                        <div></div><label class="col-form-label" style="width:100%">-</label></div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe48" value="<?php echo (isset($formVars['qe48']))?$formVars['qe48']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re48" value="<?php echo (isset($formVars['re48']))?$formVars['re48']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-679" name="se49" <?php markSelCheckBox('se49',$formVars); ?>><label class="form-check-label" for="formCheck-28">49</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Sodium Nitroprusside Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">50mg/vial</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-680" name="ce49-1" <?php markCompCheckBox('ce49-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-681" name="ce49-2" <?php markCompCheckBox('ce49-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Samarth</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe49" value="<?php echo (isset($formVars['qe49']))?$formVars['qe49']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re49" value="<?php echo (isset($formVars['re49']))?$formVars['re49']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-682" name="se50" <?php markSelCheckBox('se50',$formVars); ?>><label class="form-check-label" for="formCheck-28">50</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Teicoplanin Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-683" name="de50-1" <?php markSelCheckBox('de50-1',$formVars); ?>><label class="form-check-label" for="formCheck-139">200mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-684" name="de50-2" <?php markSelCheckBox('de50-2',$formVars); ?>><label class="form-check-label" for="formCheck-139">400mg</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-685" name="ce50-1" <?php markCompCheckBox('ce50-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Aventis&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-686" name="ce50-2" <?php markCompCheckBox('ce50-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Cipla</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-687" name="ce50-3" <?php markCompCheckBox('ce50-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Glenmark&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-688" name="ce50-4" <?php markCompCheckBox('ce50-4',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe50-1" value="<?php echo (isset($formVars['qe50-1']))?$formVars['qe50-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe50-2" value="<?php echo (isset($formVars['qe50-2']))?$formVars['qe50-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re50" value="<?php echo (isset($formVars['re50']))?$formVars['re50']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-689" name="se51" <?php markSelCheckBox('se51',$formVars); ?>><label class="form-check-label" for="formCheck-28">51</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Thyopentone inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-690" name="de51-1" <?php markSelCheckBox('de51-1',$formVars); ?>><label class="form-check-label" for="formCheck-139">0.5gm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-691" name="de51-2" <?php markSelCheckBox('de51-2',$formVars); ?>><label class="form-check-label" for="formCheck-139">1gm</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-692" name="ce51-1" <?php markCompCheckBox('ce51-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Abbott&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-693" name="ce51-2" <?php markCompCheckBox('ce51-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Biocare</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-694" name="ce51-3" <?php markCompCheckBox('ce51-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe51-1" value="<?php echo (isset($formVars['qe51-1']))?$formVars['qe51-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe51-2" value="<?php echo (isset($formVars['qe51-2']))?$formVars['qe51-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re51" value="<?php echo (isset($formVars['re51']))?$formVars['re51']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-695" name="se52" <?php markSelCheckBox('se52',$formVars); ?>><label class="form-check-label" for="formCheck-28">52</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Tranexemic Acid Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">100mg/mL</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-696" name="ce52-1" <?php markCompCheckBox('ce52-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Cadilla&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-697" name="ce52-2" <?php markCompCheckBox('ce52-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Macleod</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-698" name="ce52-3" <?php markCompCheckBox('ce52-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Samarth</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe52" value="<?php echo (isset($formVars['qe52']))?$formVars['qe52']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re52" value="<?php echo (isset($formVars['re52']))?$formVars['re52']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-699" name="se53" <?php markSelCheckBox('se53',$formVars); ?>><label class="form-check-label" for="formCheck-28">53</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Vacomycin Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-700" name="de53-1" <?php markSelCheckBox('de53-1',$formVars); ?>><label class="form-check-label" for="formCheck-139">0.5gm</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-701" name="de53-2" <?php markSelCheckBox('de53-2',$formVars); ?>><label class="form-check-label" for="formCheck-139">1gm</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-702" name="ce53-1" <?php markCompCheckBox('ce53-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">AstraZeneca&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-703" name="ce53-2" <?php markCompCheckBox('ce53-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Alkem</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-704" name="ce53-3" <?php markCompCheckBox('ce53-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Elilily</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-705" name="ce53-4" <?php markCompCheckBox('ce53-4',$formVars); ?>><label class="form-check-label" for="formCheck-61">VHB</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe53-1" value="<?php echo (isset($formVars['qe53-1']))?$formVars['qe53-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe53-2" value="<?php echo (isset($formVars['qe53-2']))?$formVars['qe53-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re53" value="<?php echo (isset($formVars['re53']))?$formVars['re53']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-706" name="se54" <?php markSelCheckBox('se54',$formVars); ?>><label class="form-check-label" for="formCheck-28">54</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Vecuronium Inj<br></label></div>
                    <div class="col-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-707" name="de54-1" <?php markSelCheckBox('de54-1',$formVars); ?>><label class="form-check-label" for="formCheck-139">4mg</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-708" name="de54-2" <?php markSelCheckBox('de54-2',$formVars); ?>><label class="form-check-label" for="formCheck-139">10mg</label></div>
                    </div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-709" name="ce54-1" <?php markCompCheckBox('ce54-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Organon&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-710" name="ce54-2" <?php markCompCheckBox('ce54-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Sun</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-711" name="ce54-3" <?php markCompCheckBox('ce54-3',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe54-1" value="<?php echo (isset($formVars['qe54-1']))?$formVars['qe54-1']:0; ?>" /></div><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe54-2" value="<?php echo (isset($formVars['qe54-2']))?$formVars['qe54-2']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re54" value="<?php echo (isset($formVars['re54']))?$formVars['re54']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-712" name="se55" <?php markSelCheckBox('se55',$formVars); ?>><label class="form-check-label" for="formCheck-28">55</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Xylocard Inj/ Lignocain Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">2%, 50ml/30ml</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-713" name="ce55-1" <?php markCompCheckBox('ce55-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">AstraZeneca&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-714" name="ce55-2" <?php markCompCheckBox('ce55-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe55" value="<?php echo (isset($formVars['qe55']))?$formVars['qe55']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re55" value="<?php echo (isset($formVars['re55']))?$formVars['re55']:''; ?>"></div>
                </div>
                <div class="row mt-3" style="border-style: dotted;">
                    <div class="col-1">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-715" name="se56" <?php markSelCheckBox('se56',$formVars); ?>><label class="form-check-label" for="formCheck-28">56</label></div>
                    </div>
                    <div class="col-2"><label class="col-form-label">Vasopresin Inj<br></label></div>
                    <div class="col-3"><label class="col-form-label">20 units/ ml&nbsp;</label></div>
                    <div class="col-3">
                        <div></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-716" name="ce56-1" <?php markCompCheckBox('ce56-1',$formVars); ?>><label class="form-check-label" for="formCheck-61">Samarth&nbsp;</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-717" name="ce56-2" <?php markCompCheckBox('ce56-2',$formVars); ?>><label class="form-check-label" for="formCheck-61">Neon</label></div>
                    </div>
                    <div class="col-1" style="margin-top: 5px;"><div style="overflow:hidden"><input style="width:75%" type="number" step="1" min="0" max="1000" name = "qe56" value="<?php echo (isset($formVars['qe56']))?$formVars['qe56']:0; ?>" /></div></div>
                    <div class="col-2" style="margin-top: 5px;">
                        <div style="overflow:hidden"></div><input type="text" style="width:100%" name="re56" value="<?php echo (isset($formVars['re56']))?$formVars['re56']:''; ?>"></div>
                </div>
            </div>
            <div class="container">
                <div>
                    <p></p>
                    <p>You have reached end of form. Move to next Tab for verification and submission.</p>
                    <p><strong><em>Changes are saved automatically.</em></strong></p>
                    <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                        <div class="container"><a class="navbar-brand text-info" href="#" style="font-size: 18px;"><span style="text-decoration: underline;">Jump to Top of this page</span></a><button class="navbar-toggler" data-toggle="collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button></div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="tab-pane" role="tabpanel" id="tab-6">
            <p></p>
            <h2 class="text-left" style="font-size: 26;">&nbsp; Form Verification</h2>
            <p></p>
            <p></p>
            <p>&nbsp; &nbsp; &nbsp;The form has been verified by:</p>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="vd" id="formCheck-854" <?php if($_SESSION['etype']!="Doctor") echo "disabled"; ?> <?php markVerifCheckBox("vd",$formVars);?> ><label class="form-check-label" for="formCheck-850">Consultant</label></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check"><input class="form-check-input" type="checkbox"  name="vn" id="formCheck-853" <?php if($_SESSION['etype']!="Nurse") echo "disabled"; ?> <?php markVerifCheckBox("vn",$formVars);?>><label class="form-check-label" for="formCheck-850">Nurse</label></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="vt" id="formCheck-852" <?php if($_SESSION['etype']!="Technician") echo "disabled"; ?> <?php markVerifCheckBox("vt",$formVars);?>><label class="form-check-label" for="formCheck-850">Technician</label></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="vp" id="formCheck-851" <?php if($_SESSION['etype']!="Perfusionist") echo "disabled"; ?> <?php markVerifCheckBox("vp",$formVars);?>><label class="form-check-label" for="formCheck-850">Perfusionist</label></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="text-right"></div><button class="btn btn-success text-center" type="submit" name="buttonSub">Forward the form</button></div>
                </div>
            </div>
        </div>
    </div>
</form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>