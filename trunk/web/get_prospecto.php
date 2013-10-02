<?php

error_reporting(E_ALL);
ini_set('display_errors', True);

 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET["codigo"])) {

    $codigo = $_GET['codigo'];
    $query = "SELECT * FROM dbo_epigespe WHERE espeunic = $codigo order by codiepig";    

    $result = mysql_query($query);


    if($result === false) {
    echo "Query failed: " . mysql_error();
    exit;
    }
    
    $epigrafe_escrito = 0;
    $epigrafe_actual = 0; 
    $texto = "";
    $query_array = array();
    //Si no esta vacio
    if (!empty($result)) {
          //Mientras haya filas (epigrafes con texto)
          while ($fila = mysql_fetch_array($result) ){
             
              //Saco el nombre del epigrafe, actualizo epigrafe actual si el que me llega es nuevo.
              //Si no lo he escrito, lo escribo. Si ya esta escrito, salto.
             
              $epigrafe_actual = $fila["CODIEPIG"];              
              if($epigrafe_actual != $epigrafe_escrito){
                  //Escribo el texto del epigrafe:
                  $query_epigrafe = "SELECT DES FROM dbo_epigrafe where codiepig = $epigrafe_actual LIMIT 1";
                  $query_epi_result = mysql_query($query_epigrafe);
                  $query_array = mysql_fetch_array($query_epi_result);                  
                  $epigrafe = $query_array["DES"];
                  $texto .= "#&".$epigrafe;
                  $texto .= "#&";
                  $epigrafe_escrito = $epigrafe_actual;
              }
              $codigo_texto = $fila["CODITEXT"];                                                                      
              //Busco el texto al que se refiere esa parte del epigrafe
              $query_texto = "SELECT TEXTO_COM FROM dbo_texto where coditext = $codigo_texto LIMIT 1";
              $query_texto_result = mysql_query($query_texto);
              $query_array = mysql_fetch_array($query_texto_result);                  
              $texto .= $query_array["TEXTO_COM"];
              //echo $texto;
              
          }
          
          //Devuelto el texto en el JSON, en el campo texto:
        
          $prospecto = utf8_encode($texto);
          $response["success"] = 1;
          $response["texto"] = $prospecto;
          echo json_encode($response);
      }                                
    
}
else{
     $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
   
    
}

?>
