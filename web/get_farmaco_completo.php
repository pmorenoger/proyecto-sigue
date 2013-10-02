<?php
 
/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */
error_reporting(E_ALL);
ini_set('display_errors', True);

 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// check for post data
if (isset($_GET["codigo"])) {

    $codigo = $_GET['codigo'];
    $query = "SELECT distinct * FROM farmaco1 WHERE codigo_unico = $codigo group by codigo_unico";
    $query2 =  "SELECT distinct codigo_principio_activo FROM farmaco1 WHERE  codigo_unico = $codigo  group by codigo_unico";   
}
if (isset($_GET["cod_nacional"])){
    
    $codigo = $_GET['cod_nacional'];
    $query = "SELECT distinct * FROM farmaco1 WHERE codigo_nacional = $codigo group by codigo_nacional";
    $query2 =  "SELECT distinct codigo_principio_activo FROM farmaco1 WHERE codigo_nacional = $codigo group by codigo_principio_activo";
}
    if(!empty($codigo)){
    // get a product from products table
    
    $result = mysql_query($query);


    if($result === false) {
    echo "Query failed: " . mysql_error();
    exit;
    }


        if (!empty($result)) {
            // check for empty result


            
            if (mysql_num_rows($result) > 0) {

                
                $result = mysql_fetch_array($result);

                $product = array();
                $product["codigo_unico"] = $result["codigo_unico"];
                $product["espeCOD"] = $result["espeCOD"];
                $product["espeSAN"] = $result["espeSAN"];
                $product["espedes"] = $result["espedes"];
                $product["nombre_farmaco"] = $result["nombre_farmaco"];
                $product["codigo_lab"] = $result["codigo_lab"];              
                $product["codigo_nacional"] = $result["codigo_nacional"];
                $product["codigo_sal"] = $result["codigo_sal"];
                $product["unidad"] = $result["unidad"];
                $product["cantidad"] = $result["cantidad"];
                
                // info lab
                $cod_lab = $product["codigo_lab"];
                $result  = mysql_query("SELECT distinct * FROM laboratorios WHERE codigo_lab = $cod_lab group by codigo_lab" );
                if (!empty($result)) {
                    // check for empty result
                    if (mysql_num_rows($result) > 0) {

                      $result = mysql_fetch_array($result);
                        $product["codigo_lab"] = $result["codigo_lab"];
                        $product["nombre_lab"] = $result["nombre_lab"];
                        $product["nombre_lab2"] = $result["nombre_lab2"];
                        $product["via"] = $result["via"];
                        $product["nombre_calle"] = $result["nombre_calle"];
                        $product["lab_n_via"] = $result["lab_n_via"];
                        $product["lab_localidad"] = $result["lab_localidad"];
                        $product["lab_provincia"] = $result["lab_provincia"];
                        $product["lab_cp"] = $result["lab_cp"];
                        $product["lab_telf"] = $result["lab_telf"];
                        $product["lab_fax"] = $result["lab_fax"];
                        $product["lab_email"] = $result["lab_email"];
                        $product["lab_observaciones"] = $result["lab_observaciones"];
                    }
                }

                // info A.P 
               
                 $codigos_principios = array();
                 $result  = mysql_query($query2);
                 $count = 0;
                 while ($row = mysql_fetch_array($result)){
                                $codigos_principios[$count] = $row["codigo_principio_activo"];
                                $count = $count+1;
                            }
                

                $x = 0;
                $lista_principios;
                while($x < $count){
                // $cod_principio_activo = $product["codigo_principio_activo"];
                    $cod = $codigos_principios[$x];
                                        
                $result  = mysql_query("SELECT distinct * FROM principios_activos WHERE prin_codigo = $cod group by prin_codigo");
               
                
                if (!empty($result)) {
                    // check for empty result                    
                                 
                    if (mysql_num_rows($result) > 0)  {
                        $result =  mysql_fetch_array($result);                       
                       // $principio = array();
                        $principio["prin_codigo"] = $result["prin_codigo"];
                        $principio["formula"] = $result["formula"];
                        $principio["formula2"] = $result["formula2"];
                        $principio["TERACT"] = $result["TERACT"];
                        $principio["FOREMP"] = $result["FOREMP"];
                        $principio["INFO"] = $result["INFO"];
                       $lista_principios[$x] = array();
                       $lista_principios[$x] = $principio;                       
                       ++$x;

                    }
                }
                }
                $product["lista_principios"] = array();
                array_push($product["lista_principios"], $lista_principios);


                // extra info

                $cod_sales = $product["codigo_sal"];
                $result  = mysql_query("SELECT distinct *FROM sales WHERE codigo_sal = $cod_sales group by codigo_sal");

                if (!empty($result)) {
                    // check for empty result
                    if (mysql_num_rows($result) > 0) {
                        $result = mysql_fetch_array($result);
                        $product["nombre_sal"] = $result["nombre_sal"];                    
                    }
                }


                // success
                $response["success"] = 1;

                // user node
                $response["product"] = array();

                array_push($response["product"], $product);

                // echoing JSON response
                echo json_encode($response);
              
            } else {
                // no product found
                $response["success"] = 0;
                $response["message"] = "No product found";

                // echo no users JSON
                echo json_encode($response);
                
            }
        } 
        else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No product found";

            // echo no users JSON
            echo json_encode($response);
            
        }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
   
}
?>