<!DOCTYPE html>
<html>
<body>
<?php
	
	$at=@$_POST['time'];
	$adate=@$_POST['date'];
	
	//Declaring variable and converting variable
	$new_date = date('h.i', strtotime($at));
	echo $new_date."<br>";
	
	$seconds = (24-$new_date)*3600;
	echo $seconds."<br>";
	
	$fulldate = $adate;
	$fulldate = $fulldate." ".$at;
	echo $fulldate."<br>";
	
	$convert_epoch = strtotime($fulldate);
	echo $convert_epoch."<br>";
	
	//Carry over #1
	$convert_read = new DateTime("@$convert_epoch");
	echo $convert_read->format('Y-m-d h:ia')."<br>";
	
	//Declaring array for time increase and time decrease
	$secondsArraypos = []; //time increase array
	$secondsArrayneg = []; //time decrease array
	
	//loop to increase 10 min (Assign time to array)
	for ($i=0;$i<7;$i++)
	{
		if ($i==0){
			//check if it goes over 86400(24hr)
			if($seconds+600 > 86400)
			{
				$secondsArraypos[i] = ($seconds+600)-86400;
				$j=$secondsArraypos[i];
			}
			else
			{
				$secondsArraypos[$i] = $seconds;
				$j=$seconds;
			}
		}
		else
		{
			//check if it goes over 86400(24hr)
			if($seconds+600 > 86400)
			{
				$secondsArraypos[i] = ($seconds+600)-86400;
				$j=$secondsArraypos[i];
			}			
			else
			{
				$secondsArraypos[$i] = $j+600;
				$j=$secondsArraypos[$i];
			}
		}
	}
	//echo '<pre>'; print_r($secondsArraypos); echo '</pre>';
	
	//loop to decrease 10 min
	for ($i=0;$i<6;$i++)
	{
		if ($i==0){
			//check if it goes below 0(24hr)
			if($seconds-600 < 0)
			{
				$secondsArrayneg[i] = ($seconds-600)+86400;
				$j=$secondsArrayneg[i];
			}
			else
			{
				$secondsArrayneg[$i] = $seconds-600;
				$j=$secondsArrayneg[$i];
			}
		}
		else
		{
			//check if it goes below 0(24hr)
			if($seconds+600 < 0)
			{
				$secondsArrayneg[i] = ($seconds-600)+86400;
				$j=$secondsArrayneg[i];
			}			
			else
			{
				$secondsArrayneg[$i] = $j-600;
				$j=$secondsArrayneg[$i];
			}
		}
	}
	//echo '<pre>'; print_r($secondsArrayneg); echo '</pre>';
	
	
	$secondsArrayneg=array_reverse($secondsArrayneg); // reverse the array of decreasing time
	//echo '<pre>'; print_r($secondsArrayneg); echo '</pre>';
	
	//convert seconds to time back (increasing time array)
	for ($i=0;$i<7;$i++)
	{
		$j=$secondsArraypos[$i]/3600;
		$hour=(int)$j;
		$min=$j-$hour;
		$min=round($min,2);
		if($min > 0.60)
		{
			$hour=$hour+1;
			$min=$min-0.60;
			$j=$hour+$min;
		}
		else
			$j=$hour+$min;
		$secondsArraypos[$i]=$j;
	}
	//echo '<pre>'; print_r($secondsArraypos); echo '</pre>';
	
	//round to 2 decimal places
	for ($i=0;$i<7;$i++)
	{
		$secondsArraypos[$i]= number_format($secondsArraypos[$i],2);
	}
	//echo '<pre>'; print_r($secondsArraypos); echo '</pre>';
	
	//convert seconds to time back (decreasing time array)
	for ($i=0;$i<6;$i++)
	{
		$j=$secondsArrayneg[$i]/3600;
		$hour=(int)$j;
		$min=$j-$hour;
		$min=round($min,2);
		if($min > 0.60)
		{
			$hour=$hour+1;
			$min=$min-0.60;
			$j=$hour+$min;
		}
		else
			$j=$hour+$min;
		$secondsArrayneg[$i]=$j;
	}
	//echo '<pre>'; print_r($secondsArrayneg); echo '</pre>';
	
	//round to 2 decimal places
	for ($i=0;$i<6;$i++)
	{
		$secondsArrayneg[$i]= number_format($secondsArrayneg[$i],2);
	}
	//echo '<pre>'; print_r($secondsArrayneg); echo '</pre>';

	//combine time with date(increasing time array)-----------------------------------------------------
	for ($i=0;$i<7;$i++)
	{
		$fulldate = $adate;
		$fulldate = $fulldate." ".$secondsArraypos[$i];
		$secondsArraypos[$i] = $fulldate;
		
	}
	//echo '<pre>'; print_r($secondsArraypos); echo '</pre>';
	
	//convert increasing time to unix time
	for ($i=0;$i<7;$i++)
	{
		$convert_epoch = strtotime($secondsArraypos[$i]);
		$secondsArraypos[$i] = $convert_epoch;
	}
	//echo '<pre>'; print_r($secondsArraypos); echo '</pre>';
	
	//combine time with date(decreasing time array)--------------------------------------------------
	for ($i=0;$i<6;$i++)
	{
		$fulldate = $adate;
		$fulldate = $fulldate." ".$secondsArrayneg[$i];
		$secondsArrayneg[$i] = $fulldate;
		
	}
	//echo '<pre>'; print_r($secondsArrayneg); echo '</pre>';
	
	
	//convert decreasing time to unix time
	for ($i=0;$i<6;$i++)
	{
		$convert_epoch = strtotime($secondsArrayneg[$i]);
		$secondsArrayneg[$i] = $convert_epoch;
	}
	//echo '<pre>'; print_r($secondsArrayneg); echo '</pre>';
	
	//merge the time to be parse into template #2
	$mergedUnix=array_merge($secondsArrayneg,$secondsArraypos);
	//echo '<pre>'; print_r($mergedUnix); echo '</pre>';
	for($i=0; $i<13; $i++)
	{
		$mergedUnix[$i] = new DateTime("@$mergedUnix[$i]");
	}
	//echo '<pre>'; print_r($mergedUnix); echo '</pre>';
	
	// convert into long string separated by comma(wheretheiss API limit 10 per call, timestamp must be separated by comma)
	$longpos = implode(',',$secondsArraypos); 
	$longneg = implode(',',$secondsArrayneg);
	//echo $longpos."<br>";
	//echo $longneg."<br>";
	
	
	//echo '<pre>'; print_r($mergedUnix); echo '</pre>';
	
	//Give the json for increasing time
	$aurl  = "https://api.wheretheiss.at/v1/satellites/25544/positions?timestamps=".$longpos;
	$ajson = file_get_contents($aurl);
	$adata = json_decode($ajson);
	//var_dump($adata);
	
	$burl  = "https://api.wheretheiss.at/v1/satellites/25544/positions?timestamps=".$longneg;
	$bjson = file_get_contents($burl);
	$bdata = json_decode($bjson);
	//echo '<pre>'; print_r($bdata); echo '</pre>';
	
	//assigning the latitude and longitude from json to array as coordinate
	$coordlonpos=[];
	$coordlonneg=[];
	$coordlat=[];
	$coordArraypos=[];
	$coordArrayneg=[];
	
	for($i=0; $i<7; $i++) 
	{
		$coordlon[$i] = $adata[$i]->longitude;
		$coordlat[$i] = $adata[$i]->latitude;
		$coordArraypos[$i] = $coordlat[$i].",".$coordlon[$i];
	}
	//echo '<pre>'; print_r($coordArraypos); echo '</pre>';
	
	for($i=0; $i<6; $i++) 
	{
		$coordlon[$i] = $bdata[$i]->longitude;
		$coordlat[$i] = $bdata[$i]->latitude;
		$coordArrayneg[$i] = $coordlat[$i].",".$coordlon[$i];
	}
	//echo '<pre>'; print_r($coordArrayneg); echo '</pre>';
	
	//merge all coordinate
	$mergedCoord=array_merge($coordArrayneg,$coordArraypos);
	//echo '<pre>'; print_r($mergedCoord); echo '</pre>';
	
	//getting the location only from json
	for($i=0; $i<13; $i++)
	{
		$curl = "https://api.wheretheiss.at/v1/coordinates/".$mergedCoord[$i];
		$cjson = file_get_contents($curl);
		$cdata = json_decode($cjson);
		$mergedCoord[$i] = $cdata->timezone_id;

	}
	//echo '<pre>'; print_r($mergedCoord); echo '</pre>';
	

	
	//Carry over #3 = $mergedCoord
	
	session_start();
	$_SESSION["a"]=$convert_read;
	$_SESSION["b"]=$mergedUnix;
	$_SESSION["c"]=$mergedCoord;
	header("Location:result.php");
?>
</body>
</html>