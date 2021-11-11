<?php
	
	$input=@$_POST['search'];
	
	
	$aurl  = "http://api.serpstack.com/search?access_key=97ac4591f1a8cb28f2b748fada75ab85&query=".$input."&type=news&gl=my";
	$ajson = file_get_contents($burl);
	$adata = json_decode($bjson);

	
	$burl  = "http://api.serpstack.com/search?access_key=97ac4591f1a8cb28f2b748fada75ab85&query=".$input."&type=news&gl=my";
	$bjson = file_get_contents($burl);
	$bdata = json_decode($bjson);
	
	session_start();
	$_SESSION["input"]=$input;
	$_SESSION["a"]=$adata;
	$_SESSION["b"]=$bdata;
	header("Location:result.php");
?>