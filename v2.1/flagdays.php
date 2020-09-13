<?
function get_flagday($checkdate){
$year = date("Y", $checkdate);
$month = date("m", $checkdate);
$day = date("d", $checkdate);
    
    $flagday["01"]["01"] = "Nyårsdagen";
    $flagday["01"]["28"] = "Kung Carl XVI Gustafs namnsdag";
    $flagday["03"]["12"] = "Kronprinsessan Victorias namnsdag";
    $flagday["04"]["30"] = "Kung Carl XVI Gustafs födelsedag";
    $flagday["05"]["01"] = "Första maj";
    $flagday["05"]["29"] = "Veterandagen";
    $flagday["06"]["06"] = "Sveriges nationaldag och svenska flaggans dag";
    $flagday["07"]["14"] = "Kronprinsessan Victorias födelsedag";
    $flagday["08"]["08"] = "Drottning Silvias namnsdag";
    $flagday["10"]["24"] = "FN-dagen";
    $flagday["11"]["06"] = "Gustav Adolfsdagen";
    $flagday["12"]["10"] = "Nobeldagen";
    $flagday["12"]["23"] = "Drottning Silvias födelsedag";
    $flagday["12"]["25"] = "Juldagen";
    
    if (date("Ymd",easter($year)) == date("Ymd",$checkdate)){
       return "Påskdagen"; 
    }
    
    if (date("Ymd",strtotime('+49 day',easter($year))) == date("Ymd",$checkdate)){
       return "Pingstdagen"; 
    }
    
    if ($month == 6 && $day >= 20 && $day <= 26 && date("N",$checkdate) == 6){
        return "Midsommardagen";
    }
    
    if (($year - 1994) % 4 == 0) {
        if (strtotime('september '.$year.' +2 sunday') == strtotime(date("Ymd",$checkdate))) {
            return "Val till Sveriges riksdag";
        }  
    }
    
    
    if (isset($flagday[$month][$day])){
        return $flagday[$month][$day];
    }
    else
    {
    return "";
    }



} //end get_nameday

?>