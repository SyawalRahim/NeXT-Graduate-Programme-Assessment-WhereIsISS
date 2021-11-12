
<?php
	
	$at=@$_POST['time'];
	$adate=@$_POST['date'];
	
	//Declaring variable and converting variable
	$new_date = date('h.i', strtotime($at));
	//echo $new_date."<br>";
	
	$fulldate = $adate;
	$fulldate = $fulldate." ".$at;
	//echo $fulldate."<br>";
	
	$convert_epoch = strtotime($fulldate);
	//echo $convert_epoch."<br>";
	
	//Carry over #1
	$convert_read = new DateTime("@$convert_epoch");
	//echo $convert_read->format('Y-m-d h:i')."<br>";
	
	//Declaring array for time increase and time decrease

	$minutes_to_add = 10;

	$time = $convert_read->add(new DateInterval('PT' . $minutes_to_add . 'M'));
	//echo $time->format('Y-m-d h:i')."<br>";
	
	$time = $convert_read->sub(new DateInterval('PT' . $minutes_to_add . 'M'));
	//echo $time->format('Y-m-d h:i')."<br>";

	$stamp = $time->format('Y-m-d H:i');
	//echo $stamp;
		
	//loop to increase 10 min (Assign time to array)
	$tarraypos=[];
	$tarrayneg=[];
	$timeadd = new DateTime("@$convert_epoch");
	$timesub = new DateTime("@$convert_epoch");
	for ($i=0;$i<7;$i++)
	{
		if($i==0)
		{
			$tarraypos[$i] = $timeadd->format('Y-m-d h:ia');
		}
		else
		{
			$timeadd = $timeadd->add(new DateInterval('PT' . $minutes_to_add . 'M'));
			$tarraypos[$i] = $timeadd->format('Y-m-d h:ia');
		}
	}
	//echo '<pre>'; print_r($tarraypos); echo '</pre>';
	
	//loop to decrease 10 min
	for ($i=1;$i<7;$i++)
	{
		if($i==0)
		{
			$tarrayneg[$i] = $timesub->format('Y-m-d h:ia');
		}
		else
		{
			$timeadd = $timesub->sub(new DateInterval('PT' . $minutes_to_add . 'M'));
			$tarrayneg[$i] = $timesub->format('Y-m-d h:ia');
		}
	}
	//echo '<pre>'; print_r($tarrayneg); echo '</pre>';
	
	
	$tarrayneg = array_reverse($tarrayneg); //reversing decreasing array
	//echo '<pre>'; print_r($tarrayneg); echo '</pre>';
	
	$tarraymerged = array_merge($tarrayneg,$tarraypos); //merging time array #2
	//echo '<pre>'; print_r($tarraymerged); echo '</pre>';
	
	//convert increasing time to unix time---------------------------------------
	for ($i=0;$i<7;$i++)
	{
		$convert_epoch = strtotime($tarraypos[$i]);
		$tarraypos[$i] = $convert_epoch;
	}
	//echo '<pre>'; print_r($tarraypos); echo '</pre>';
	
	//convert decreasing time to unix time
	for ($i=0;$i<6;$i++)
	{
		$convert_epoch = strtotime($tarrayneg[$i]);
		$tarrayneg[$i] = $convert_epoch;
	}
	//echo '<pre>'; print_r($tarrayneg); echo '</pre>';
	
	
	// convert into long string separated by comma(wheretheiss API limit 10 per call, timestamp must be separated by comma)
	$longpos = implode(',',$tarraypos); 
	$longneg = implode(',',$tarrayneg);
	//echo $longpos."<br>";
	//echo $longneg."<br>";
	
	$mergedUnix=array_merge($tarrayneg,$tarraypos);
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
	$_SESSION["b"]=$tarraymerged;
	$_SESSION["c"]=$mergedCoord;
	header("Location:result.php");
	
?>
