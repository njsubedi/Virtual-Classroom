<?php 
	/* Works out the time since the entry post, takes a an argument in unix time (seconds) */
function time_since($original) {

    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
		array(1, 'second')
    );
	
	$today = time();
    $since = $today - $original;
   
    // $j saves performing the count function each time around the loop
	$jCount = count($chunks);
	
    for ($i = 0, $j = $jCount; $i < $j; $i++)
	{
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
       
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }
    
    if($name == 'week' || $name == 'month')
    {
    		return 'at '.date("M d H:ia");	
	 }
	 
	 else if($name == 'day')
	 {
	 	if($count == '1')
		 	return 'Yesterday '.date("H:ia", $original);
		else
			return date("l H:i a", $original);
	 }
	 
	 else if($name == 'year')
	 {
	 	return date("d M Y H:i a", $original);	
	 }
	 
    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	
    $print2 = '';
   //uncomment this if needed weeks/months 
    if ($count < 3 && $i + 1 < $j) {
	
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print2 = ($count2 == 1) ? ' 1 '.$name2 : " $count2 {$name2}s";
        }
    }
		
	
	if($print == '0 seconds')
		return 'just now';
	else
		return $print.$print2.' ago';
}
?>