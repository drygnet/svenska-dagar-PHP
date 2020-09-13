<?
error_reporting(E_ALL);
ini_set('display_errors', '1');
function get_holiday($checkdate){
$year = date("Y", $checkdate);
$month = date("m", $checkdate);
$day = date("d", $checkdate);

    //Fixed dates Returnerar typ, dag och om den är abetsfri eller inte
    $fixed["01"]["01"] = array("helgdag","Nyårsdagen","Ja");
    $fixed["01"]["05"] = array("helgdagsafton","Trettondagsafton","Nej");
    $fixed["01"]["06"] = array("helgdag","Trettondedag jul","Ja");
    $fixed["04"]["30"] = array("helgdagsafton","Valborgsmässoafton","Nej");
    $fixed["05"]["01"] = array("helgdag","Första Maj","Ja");
    $fixed["06"]["06"] = array("helgdag","Sveriges nationaldag","Ja");
    $fixed["12"]["24"] = array("helgdag","Julafton","Ja");
    $fixed["12"]["25"] = array("helgdag","Juldagen", "Ja");
    $fixed["12"]["26"] = array("helgdag","Annandag jul","Ja");
    $fixed["12"]["31"] = array("helgdag","Nyårsafton","Ja");    
    if (isset($fixed[$month][$day])){
        return $fixed[$month][$day];
    }
    
    
    //Midsommar
    if ($month == 6 && $day >= 19 && $day <= 25 && date("N",$checkdate) == 5){
        return array("helgdag", "Midsommarafton", "Ja");
    }
    if ($month == 6 && $day >= 20 && $day <= 26 && date("N",$checkdate) == 6){
        return array("helgdag", "Midsommardagen", "Ja");
    }

    //Påsk
    if (date("Ymd",strtotime('-3 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdagsafton", "Skärtorsdagen", "Nej"); 
    }
    
    if (date("Ymd",strtotime('-2 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Långfredagen", "Ja");
    }

    if (date("Ymd",strtotime('-1 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Påskafton", "Ja"); 
    }

    if (date("Ymd",easter_date($year)) == date("Ymd",$checkdate)){
       return array("helgdag", "Påskdagen", "Ja"); 
    }
    
    if (date("Ymd",strtotime('+1 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Annandag påsk", "Ja"); 
    }
    
    if (date("Ymd",strtotime('+39 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Kristi himmelsfärdsdag", "Ja"); 
    }
    
    if (date("Ymd",strtotime('+48 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdagsafton", "Pingstafton", "Ja"); 
    }
    
    if (date("Ymd",strtotime('+49 day',easter_date($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Pingstdagen", "Ja"); 
    }
    
    //Allhelgona
    if ($checkdate >= strtotime($year."-10-30") && $checkdate <= strtotime($year."-11-05") && date("N",$checkdate) == 5){
        return array("helgdagsafton", "Allhelgonaafton", "Nej");    
    }
    
    if ($checkdate >= strtotime($year."-10-31") && $checkdate <= strtotime($year."-11-06") && date("N",$checkdate) == 6){
        return array("helgdag", "Alla helgons dag", "Ja");    
    }
    
    
}
?>