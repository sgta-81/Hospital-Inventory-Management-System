var displayL=0,displayS=0;
// bac2,l
function showL(){
	 if(displayL===0){
		$( "#ic3" ).hide( 100 );
		$( "#ic2" ).show( 100 );
		displayS=0;
		displayL=1;
	}
}
function showS(){
	 if(displayS===0){
		$( "#ic2" ).hide( 100 );
		$( "#ic3" ).show( 100 );
		displayS=1;
		displayL=0;
	}
}

var displayI=1,displayD=0;

function showD(){
	if(displayD===0){
		$( "#ad1" ).hide( 200 );
		$( "#ad2" ).show( 200 );
		displayI=0;
		displayD=1;
	}
}
function showI(){
	if(displayI===0){
		$( "#ad2" ).hide( 200 );
		$( "#ad1" ).show( 200 );
		displayI=1;
		displayD=0;
	}
}


var displayB=1,displayH=0;

function showH(){
	if(displayH===0){
		$( "#c2" ).hide( 200 );
		$( "#bh" ).show( 200 );
		displayB=0;
		displayH=1;
	}
}
function showB(){
	if(displayB===0){
		$( "#bh" ).hide( 200 );
		$( "#c2" ).show( 200 );
		displayB=1;
		displayH=0;
	}
}
var changeRes;
//document.write("hello");
function reload(){
	var ch=document.getElementById("#c22").value;
	//document.write(ch);
	changeRes=ch; 
}

