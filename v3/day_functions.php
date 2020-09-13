<?
error_reporting(E_ALL);
ini_set('display_errors', '1');
function get_holiday($checkdate){
$year = date("Y", $checkdate);
$month = date("m", $checkdate);
$day = date("d", $checkdate);
    
    
    //Midsommar
    if ($month == 6 && $day >= 19 && $day <= 25 && date("N",$checkdate) == 5){
        return array("helgdag", "Midsommarafton", "Ja","Nej");
    }
    if ($month == 6 && $day >= 20 && $day <= 26 && date("N",$checkdate) == 6){
        return array("helgdag", "Midsommardagen", "Ja","Ja");
    }

    //Påsk
    if (date("Ymd",strtotime('-3 day', easter($year))) == date("Ymd",$checkdate)){
       return array("helgdagsafton", "Skärtorsdagen", "Nej","Nej"); 
    }
    
    if (date("Ymd",strtotime('-2 day',easter($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Långfredagen", "Ja","Ja");
    }

    if (date("Ymd",strtotime('-1 day',easter($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Påskafton", "Ja","Nej"); 
    }

    if (date("Ymd",easter($year)) == date("Ymd",$checkdate)){
       return array("helgdag", "Påskdagen", "Ja","Ja"); 
    }
    
    if (date("Ymd",strtotime('+1 day',easter($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Annandag påsk", "Ja","Ja"); 
    }
    
    if (date("Ymd",strtotime('+39 day',easter($year))) == date("Ymd",$checkdate)){
       if ($month == "04" && $day == "30"){
           return array("helgdag", "Kristi himmelsfärdsdag, Valborgsmässoafton", "Ja","Ja");
       }
       
       elseif($month == "05" && $day == "01"){
           return array("helgdag", "Kristi himmelsfärdsdag, Första Maj", "Ja","Ja");
       }
       
       else{
            return array("helgdag", "Kristi himmelsfärdsdag", "Ja","Ja");    
       }
        
    }
    
    if (date("Ymd",strtotime('+48 day',easter($year))) == date("Ymd",$checkdate)){
       return array("helgdagsafton", "Pingstafton", "Ja","Nej"); 
    }
    
    if (date("Ymd",strtotime('+49 day',easter($year))) == date("Ymd",$checkdate)){
       return array("helgdag", "Pingstdagen", "Ja","Ja"); 
    }
    
    if (date("Ymd",strtotime('+50 day',easter($year))) == date("Ymd",$checkdate))
    {
     if ($year < "2005")
        {
            return array("helgdag", "Annandag pingst", "Ja","Ja"); 
        }
    else
        {
       return array("helgdag", "Annandag pingst", "Nej","Nej"); 
        }
    }
    
    
    //Allhelgona
    if ($checkdate >= strtotime($year."-10-30") && $checkdate <= strtotime($year."-11-05") && date("N",$checkdate) == 5){
        return array("helgdagsafton", "Allhelgonaafton", "Nej","Nej");    
    }
    
    if ($checkdate >= strtotime($year."-10-31") && $checkdate <= strtotime($year."-11-06") && date("N",$checkdate) == 6){
        return array("helgdag", "Alla helgons dag", "Ja","Ja");    
    } 



    //Fixed dates Returnerar typ, dag och om den är abetsfri eller inte OCH om det är röd dag
    $fixed["01"]["01"] = array("helgdag","Nyårsdagen","Ja","Ja");
    $fixed["01"]["05"] = array("helgdagsafton","Trettondagsafton","Nej","Nej");
    $fixed["01"]["06"] = array("helgdag","Trettondedag jul","Ja","Ja");
    $fixed["04"]["30"] = array("helgdagsafton","Valborgsmässoafton","Nej","Nej");
    $fixed["05"]["01"] = array("helgdag","Första Maj","Ja","Ja");
    $fixed["06"]["06"] = array("helgdag","Sveriges nationaldag","Ja","Ja");
    $fixed["12"]["24"] = array("helgdag","Julafton","Ja","Nej");
    $fixed["12"]["25"] = array("helgdag","Juldagen", "Ja","Ja");
    $fixed["12"]["26"] = array("helgdag","Annandag jul","Ja","Ja");
    $fixed["12"]["31"] = array("helgdag","Nyårsafton","Ja","Nej");
    
    
    
    //Ovveride for nationaldagen
    if ($year < "2005"){
        $fixed["06"]["06"] = array("helgdag","Sveriges nationaldag","Nej","Nej");
    }
    
    if ($year < "1983"){
        $fixed["06"]["06"] = array("helgdag","Svenska flaggans dag","Nej","Nej");
    }
    
    
    
    if (isset($fixed[$month][$day])){
        return $fixed[$month][$day];
    }

 
    
} //Slut get_holiday


//Konvertering till riktiga påskdatum även innan 1970
function easter($easteryear) {
    $base = strtotime($easteryear."-03-21");
    $days = easter_days($easteryear);
    $easter = strtotime("+".$days." day", $base);
    return $easter;
}


    //Veckodagar (returnerar veckodag, om dagen är arbetsfri och om det är en röd dag)
    function get_weekday($checkdate){
        switch (date("N",$checkdate)) {
            case 1:
                return array("Måndag", "Nej", "Nej"); 
                break;
            case 2:
                return array("Tisdag", "Nej", "Nej");
                break;
            case 3:
                return array("Onsdag", "Nej", "Nej");
                break;
            case 4:
                return array("Torsdag", "Nej", "Nej");
                break;
            case 5:
                return array("Fredag", "Nej", "Nej");
                break;
            case 6:
                return array("Lördag", "Ja", "Nej");
                break;
            case 7:
                return array("Söndag", "Ja", "Ja");
                break;
        }   
    }
    //Slut veckodagar

function is_workfree($checkdate, $offset){
    $weekday = get_weekday(strtotime($offset,$checkdate));
    $holiday = get_holiday(strtotime($offset,$checkdate));
    
    
    
    if ($weekday[1] == "Ja"  || $holiday[2] == "Ja"){
        return true;
    }
    
    
}


?>