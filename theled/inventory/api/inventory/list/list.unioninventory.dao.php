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

$row        = 1;
$error      = "";
$html       = "";
$column     = [];
$dataToUp   = [];
$platform   = $_POST["platform"];
$type       = $_POST["uploadType"];
$separator  = $_POST["separator"];
$lines      = "INSERT INTO ".$type."_".$platform." (";
$newData    = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["upload-file"])) {
    $arquivo_temporario = $_FILES["upload-file"]["tmp_name"]; 
    $nome_arquivo = $_FILES["upload-file"]["name"];

    /*********
     *  Verificar se existe informação de dados na tabela <platform_type> selecionada 
     */
    $query_get_update_flag = "SELECT count(*) as total FROM inventory_update_flag WHERE table_name='".$type."_".$platform."'";
    $rs_count = $DB->getData($query_get_update_flag);

    // Se existir, efetua UPDATE da data da ultima atualização dos dados para AGORA
    if($rs_count[0]['total'] > 0){
        $query_update_update_flag = "UPDATE inventory_update_flag SET last_update=now() WHERE table_name='".$type."_".$platform."'";
        $DB->executeInstruction($query_update_update_flag);
    } else { // Se não existir, cria uma linha nova com a informação de inserção dos dados e seta flag ($newData=true) para criar nova tabela (caso não exista)
        $query_insert_update_flag = "INSERT INTO inventory_update_flag (uuid, table_name, last_update) VALUES (UUID(),'".$type."_".$platform."',now())";
        $DB->executeInstruction($query_insert_update_flag);
        $newData=true;
    }
    
    // 1. Verificar se não houve erro no upload
    if ($_FILES["upload-file"]["error"] === UPLOAD_ERR_OK) {
        if (($handle = fopen($arquivo_temporario, "r")) !== FALSE) {
            $html.=  "<table class='table table-striped table-hover'>\n";
            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                $num = count($data);
                if($row == 1){
                    $html.=  "<thead>\n";
                }
                    
                elseif($row == 2){
                    $html.=  "<tbody>\n";
                    
                }
                if($row > 2)
                    $lines.=",(";
                $html.=  "<tr>";
                for ($c=0; $c < $num; $c++) {
                    if($c>0)
                        $lines.=",";
                    if($row==1){
                        array_push($column,$data[$c]);
                        if(($platform == "hivestack") || ($platform == "admooh") || ($platform == "onsign")){
                            if($c==0)
                                $lines .= "`".substr($data[$c],3)."`";
                            else
                                $lines .= "`".$data[$c]."`";
                        }else{
                            $lines .= "`".$data[$c]."`";
                        }
                    }
                    else {
                        if(($data[$c] == "—") || ($data[$c] == "") ) // colunas nulas
                            $lines .= "NULL";
                        elseif((strlen($data[$c]) == 24 ) && ((is_numeric(substr($data[$c],0,2)) ) && (strpos($data[$c],"-")>0) && (strpos($data[$c],":")>0)) ) // colunas de data (formato dd-mm-YYYY)
                            $lines .= "date('".substr($data[$c],0,10)." ".substr($data[$c],strpos($data[$c],"T")+1,8)."')";
                        elseif((strlen($data[$c]) == 23 ) && ((!is_numeric(substr($data[$c],0,2)) && is_numeric(substr($data[$c],4,2))) && (strpos($data[$c],":")>0)) ) // colunas de data (formato MMM dd YYYY hh:ii:ss AM/PM)
                            $lines .= "date('".substr($data[$c],7,4)."-".getConvertMonthInt(strtoupper(substr($data[$c],0,3)))."-".substr($data[$c],4,2)." ".substr($data[$c],12,8)."')";
                        elseif((strlen($data[$c]) == 22 ) && ((!is_numeric(substr($data[$c],0,2)) && is_numeric(substr($data[$c],4,1))) && (strpos($data[$c],":")>0)) ) // colunas de data (formato MMM d YYYY hh:ii:ss AM/PM)
                            $lines .= "date('".substr($data[$c],6,4)."-".getConvertMonthInt(strtoupper(substr($data[$c],0,3)))."-0".substr($data[$c],4,1)." ".substr($data[$c],11,8)."')";
                        elseif((strlen($data[$c]) == 19 ) && ((strpos($data[$c],"/")>0) && (strpos($data[$c],":")>0)) ) // colunas de data (formato mm/dd/YYYY)
                            $lines .= "date('".substr($data[$c],6,4)."-".substr($data[$c],0,2)."-".substr($data[$c],3,2)." ".substr($data[$c],strpos($data[$c]," ")+1,8)."')";
                        elseif((strlen($data[$c]) == 11 ) && ((strpos($data[$c],":")>0) && ((substr($data[$c],-2) == "AM") || (substr($data[$c],-2) == "PM")) ) )// colunas de horas
                            $lines .= "'".substr($data[$c],0,8)."'";
                        elseif((strlen($data[$c]) == 8 ) && (strpos($data[$c],":")>0) ) // colunas de horas
                            $lines .= "'".$data[$c]."'";
                        elseif((is_numeric($data[$c])) || (is_bool($data[$c])) ) // numeros
                            $lines .= $data[$c];
                        else  
                            $lines .= "'".str_replace("'","''",$data[$c])."'";
                        //$lines.=$column[$c]."='$data[$c]'";
                    }
                    $html.=  "<td>" . $data[$c] . "</td>";
                }
                //$dataToUp[$row] = [$lines];
                $html.=  "</tr>\n";
                if($row == 1)
                    $html.=  "</thead>\n";
                $lines.=")";
                if($row == 1)
                    $lines.=" VALUES (";
                $row++;
            }
            $html.=  "</tbody>\n";
            $html.=  "<caption>Inventory - $num line(s)</caption>\n";
            $html.=  "</table>\n";
            fclose($handle);
        }
    } else {
        $error = "Erro no upload: Código " . $_FILES["upload-file"]["error"];
    }
} else {
    $error = "Nenhum arquivo enviado ou método incorreto.";
}


/********
 * Executando queries finais
 */
// Limpa a tabela se ja possui dados
if(!$newData){
    $query_disable_check = "SET FOREIGN_KEY_CHECKS = 0;";
    $DB->executeInstruction($query_disable_check);
    $query_truncate = "TRUNCATE TABLE ".$type."_".$platform;
    $DB->executeInstruction($query_truncate);
    $query_enable_check = "SET FOREIGN_KEY_CHECKS =1;";
    $DB->executeInstruction($query_enable_check);
} 
// grava os registros da lista na tabela
$sql_insert_data = $lines;
$DB->executeInstruction($sql_insert_data);


//close connection
$DB->close();

$status     = true;
$message    = 'Data uploaded successfully';
if($error != ""){
    $status     = false;
    $message    = $error;
}


$returning = [
    'success' => "$status",
    'message' => "$message",
    //'html' => "$html",
    //'data' => [($dataToUp)]
    'lines' => $lines
];
header('Content-Type: application/json; charset=utf8');
echo json_encode($returning);
?>