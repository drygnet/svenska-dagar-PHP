<?
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/Stockholm');
require('day_functions.php');
require('namedays.php');

//Set up defaults
$startyear=date("Y");
$endyear=date("Y");
$startmonth="01";
$endmonth="12";
$startday="01";
$endday = "";


if (strpos($_SERVER['REQUEST_URI'],'?') !== false) {
$uri_and_params = explode('?', $_SERVER['REQUEST_URI']);
$uri = $uri_and_params[0];
$params = $uri_and_params[1];
}
else{
    $uri = $_SERVER['REQUEST_URI'];
}

$request_parts = explode('/', $uri);



//Check year
if (!isset($request_parts[3]) || is_null($request_parts[3]) || $request_parts[3]==""){
    $startyear=date("Y");
    $startmonth=date("m");
    $startday=date("d");
    $endyear=date("Y");
    $endmonth=date("m");
    $endday=date("d");
    }

elseif (is_numeric($request_parts[3]) && $request_parts[3]>1901 && $request_parts[3]<3000)  {
$startyear = $request_parts[3];
$endyear = $startyear;
}

else{
return_error("Felaktigt årtal");
}




//Check month

if (!isset($request_parts[4]) || is_null($request_parts[4]) || $request_parts[4]==""){
//Use defaults
}

elseif (is_numeric($request_parts[4]) && $request_parts[4]>0 && $request_parts[4]<13)  {
$startmonth = $request_parts[4];
$endmonth = $request_parts[4];
}
else{
return_error("Felaktig månad");
}

//check day
if ($endday != ""){
    //Use set values
}

elseif (!isset($request_parts[5]) || is_null($request_parts[5]) || $request_parts[5]==""){
    $startday="01";
    $endday=cal_days_in_month(CAL_GREGORIAN, $startmonth, $startyear);
}

elseif (is_numeric($request_parts[5]) && $request_parts[5]>0 && $request_parts[5]<=cal_days_in_month(CAL_GREGORIAN, $startmonth, $startyear))  {
$startday = $request_parts[5];
$endday = $request_parts[5];
}
else{
return_error("Felaktig dag ".$request_parts[5]);
}

//Format the dates
$startunixdate = mktime(0,0,0,$startmonth,$startday,$startyear);
$endunixdate = mktime(0,0,0,$endmonth,$endday,$endyear);


$output['cachetid'] = date("Y-m-d H:m:s");
$output['version'] = "2.0";
$output['uri'] = $_SERVER['REQUEST_URI'];
$output['startdatum'] = date("Y-m-d", $startunixdate);
$output['slutdatum'] = date("Y-m-d", $endunixdate);


//Time to loop it!
$number_of_days = 0;
$number_of_workfree = 0;
$number_of_work = 0;
$squeeze_days = array();
$squeeze_days['totalt antal'] = 0;

$loopdate = $startunixdate;

    while ($loopdate <= $endunixdate) {
    	$output['dagar'][date("Y-m-d", $loopdate)]['datum'] = date("Y-m-d", $loopdate);
        
        //check weekday
        list($weekday, $workfree, $redday) = get_weekday($loopdate);
        $output['dagar'][date("Y-m-d", $loopdate)]['veckodag'] = $weekday;
        $output['dagar'][date("Y-m-d", $loopdate)]['arbetsfri dag'] = $workfree;
        $output['dagar'][date("Y-m-d", $loopdate)]['röd dag'] = $redday;
        
        //Check if day is holiday
        if ($type_and_day = get_holiday($loopdate)){
            list($type, $day, $workfree_holiday, $redday_holiday) = $type_and_day;
            $output['dagar'][date("Y-m-d", $loopdate)][$type] = $day;
            
            if ($workfree == "Ja" || $workfree_holiday == "Ja"){
              $workfree = "Ja";  
            }
            
            if ($redday == "Ja" || $redday_holiday == "Ja"){
            $redday = "Ja";
            }
            
            $output['dagar'][date("Y-m-d", $loopdate)]['arbetsfri dag'] = $workfree;
            $output['dagar'][date("Y-m-d", $loopdate)]['röd dag'] = $redday;
            
        }
        
        
    if ($workfree == "Ja"){
        $number_of_workfree++;
    }
    else {
       $number_of_work++;
       $next_day = get_holiday(strtotime('+1 day',$loopdate));
       if ($next_day[0] == "helgdag"){
           $output['dagar'][date("Y-m-d", $loopdate)]['dag före arbetsfri helgdag'] = "Ja";
       }
       
       if (is_workfree($loopdate,"+1 day") == true && is_workfree($loopdate,"-1 day") == true){
           $output['dagar'][date("Y-m-d", $loopdate)]['klämdag'] = "Ja";
           $squeeze_days['totalt antal']++;
           $squeeze_days[] = date("Y-m-d", $loopdate);
       }
       
    }
    
    //Get namedays
    if($names = get_nameday($loopdate)){
        $output['dagar'][date("Y-m-d", $loopdate)]['namnsdag'] = $names;
    }
    
    //Keep this last in the loop please!
	$loopdate = strtotime('+1 day', $loopdate);
    $number_of_days++;
	}

//Store statistics
if (isset($_GET['statistik'])){
$output['statistik']['antal dagar'] = $number_of_days;
$output['statistik']['arbetsfria dagar'] = $number_of_workfree;
$output['statistik']['arbetsdagar'] = $number_of_work;
$output['statistik']['klämdagar'] = $squeeze_days;
}

//Push it out
header("Access-Control-Allow-Origin: *");
$json = json_encode($output);
$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;
print $jsonp_callback ? "$jsonp_callback($json)" : $json;




function return_error($msg){
    header('Status: 400 Bad Request', true, 400);
    exit($msg);
    }

?>
