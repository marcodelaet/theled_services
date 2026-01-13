<?php
//REQUIRE GLOBAL conf
require_once('../../database/.config');

// REQUIRE conexion class
require_once('../../database/connect.database.php');

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

$status     = true;
$data       = false;
$message    = null;

$error      = "";

$campaign_name = "";
if(isset($_POST["campaign_name"])){
    $campaign_name   = $_POST["campaign_name"];
}

$invian_id = "";
if(isset($_POST["invian_id"])){
    $invian_id   = $_POST["invian_id"];
}

$columns    = "UUID,campaign_name,invian_id,created_at,updated_at"; // columns to return (depending of the choosed platform)
$values     = "UUID(),'$campaign_name','$invian_id',now(),now()";

if(isset($_POST["platform"]))
    $platform   = $_POST["platform"];
if(isset($_POST["subjectType"]))
    $type       = $_POST["subjectType"];

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
        if(isset($_POST["fee"])){
            $columns.= ",fee";
            $fee    = $_POST["fee"];
            $values = $fee;
        }
        if(isset($_POST["start_date"])){
            $columns .= ",start_date";
            $start_date   = $_POST["start_date"];
            $values = "'$start_date'";
        }
        if(isset($_POST["stop_date"])){
            $columns .= ",stop_date";
            $stop_date   = "'$stop_date'";
        }
            

        $query_insert   = "INSERT INTO campaigns ($columns) VALUES ($values)";
        $exec_insertion   = $DB->executeInstruction($query_insert);

        
        $message    = 'Insert data successfully';

    } else { // if not authorized, return error
        $error      = "Permission denied!";
    }
    
} else {
    $error      = "Authentication required!";
}


/********
 * Log record?
 */

//close connection
$DB->close();

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
    'page' => $page,
    'totalpages' => $totalpages,
    'numRowsTotal' => $numRowsTotal,
    'numRowsPage' => $numRowsLimited,
    'offset' => $offset,
    'data' => $data
    //'lines' => $lines
];
header('Content-Type: application/json; charset=utf8');
echo json_encode($returning);
?>