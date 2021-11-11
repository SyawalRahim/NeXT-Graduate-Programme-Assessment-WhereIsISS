<!DOCTYPE html>
<html>
<body>
<?php
	
	$at=@$_POST['time'];
	$bt=@$_POST['time'];
	$adate=@$_POST['date'];
	
	$new_date = date('h.i', strtotime($at));
	echo $new_date."<br>";
	$seconds = $new_date*3600;
	echo $seconds."<br>";
	$fulldate = $adate.'';
	$fulldate = $fulldate." ".$at;
	echo $fulldate."<br>";
	$convert_epoch = strtotime($fulldate);
	echo $convert_epoch."<br>";
	$convert_read = new DateTime("@$convert_epoch");
	echo $convert_read->format('Y-m-d h:ia');
	
	
	
	
	//$aurl  = "http://api.serpstack.com/search?access_key=97ac4591f1a8cb28f2b748fada75ab85&query=".$input."&type=news&gl=my";
	//$ajson = file_get_contents($burl);
	//$adata = json_decode($bjson);

	
	//$burl  = "http://api.serpstack.com/search?access_key=97ac4591f1a8cb28f2b748fada75ab85&query=".$input."&type=news&gl=my";
	//$bjson = file_get_contents($burl);
	//$bdata = json_decode($bjson);
	$bdata=$at;
	session_start();
	//$_SESSION["input"]=$input;
	$_SESSION["a"]=$new_date;
	$_SESSION["b"]=$bdata;
	$_SESSION["c"]=$at;
	//header("Location:result.php");
?>
</body>
</html>