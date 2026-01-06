<?php
//REQUIRE GLOBAL conf
require_once('../../../database/.config');

// REQUIRE conexion class
require_once('../../../database/connect.database.php');

function getConvertMonthBRInt($strMonth){
    $returning  = $strMonth;
    switch($strMonth){
        case "JAN":
            $returning = 1;
            break;
        case "FEV":
            $returning = 2;
            break;
        case "MAR":
            $returning = 3;
            break;
        case "ABR":
            $returning = 4;
            break;
        case "MAI":
            $returning = 5;
            break;
        case "JUN":
            $returning = 6;
            break;
        case "JUL":
            $returning = 7;
            break;
        case "AGO":
            $returning = 8;
            break;
        case "SET":
            $returning = 9;
            break;
        case "OUT":
            $returning = 10;
            break;
        case "NOV":
            $returning = 11;
            break;
        case "DEZ":
            $returning = 12;
            break;
    } 
    return $returning;  
}


function getConvertMonthENInt($strMonth){
    $returning  = $strMonth;
    switch($strMonth){
        case "JAN":
            $returning = 1;
            break;
        case "FEB":
            $returning = 2;
            break;
        case "MAR":
            $returning = 3;
            break;
        case "APR":
            $returning = 4;
            break;
        case "MAY":
            $returning = 5;
            break;
        case "JUN":
            $returning = 6;
            break;
        case "JUL":
            $returning = 7;
            break;
        case "AUG":
            $returning = 8;
            break;
        case "SEP":
            $returning = 9;
            break;
        case "OCT":
            $returning = 10;
            break;
        case "NOV":
            $returning = 11;
            break;
        case "DEC":
            $returning = 12;
            break;
    }
    return $returning;
}

function getConvertMonthInt($strMonth){
    return getConvertMonthBRInt(getConvertMonthENInt($strMonth));
}


$DB = new MySQLDB($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASSWORD,$DATABASE_NAME);

// call conexion instance
$con = $DB->connect();

$maxreturn  = 200; // number total of lines to return
$totalpages = 1;

$error      = "";
$html       = "";
$dataToShow = [];

$columns    = ""; // columns to return (depending of the choosed platform)
$where      = ""; // generating an where statement

if(isset($_POST["platform"]))
    $platform   = $_POST["platform"];
if(isset($_POST["uploadType"]))
    $type       = $_POST["uploadType"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["auth"]) && (isset($_POST["secretkey"])))) {
    $auth       = str_replace("'","''",$_POST["auth"]); 
    $secretkey  = str_replace("'","''",$_POST["secretkey"]);

    /*********
     *  checking if user is enable to use this API (maybe we can use an external file to check it) 
     */
    $query_get_authorization = "SELECT count(*) as total FROM authorizations WHERE auth='$auth' AND secretkey='$secretkey'";
    $rs_count = $DB->getData($query_get_authorization);

    // if enable, bring the lines
    if($rs_count[0]['total'] > 0){
        $query_return   = "SELECT $columns FROM ".$type."_".$platform." $where ";
        $totalpages = ceil($DB->numrows($query_return) / $maxreturn);

        $page       = 1;
        if(isset($_POST["page"])){
            $page       = $_POST['page'];
            if($page > $totalpages)
                $page = $totalpages;
            if($page <= 0)
                $page = 1;
        }
            
        $offset     = ($page * $maxreturn) - $maxreturn;

        $query_return_limited=  $query_return . " LIMIT $offset, $maxreturn";
        $rs_data    = $DB->getData($query_return_limited);

    } else { // if not authorized, return error
        $error      = "Permission denied!";
    }
    
} else {
    $error      = "No Permission!";
}


/********
 * Log record?
 */

//close connection
$DB->close();

$status     = true;
$data       = false;
$message    = 'Get Data successfully';
if($error != ""){
    $status     = false;
    $message    = $error;
} else {
    $data = json_encode($rs_data);
}


$returning = [
    'success' => $status,
    'message' => "$message",
    //'html' => "$html",
    'data' => $data
    //'lines' => $lines
];
header('Content-Type: application/json; charset=utf8');
echo json_encode($returning);
?>