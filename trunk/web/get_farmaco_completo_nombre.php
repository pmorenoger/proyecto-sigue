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

if (isset($_GET["nombre"])) {

    $nombre = $_GET['nombre'];
    $query = "SELECT distinct * FROM farmaco1 WHERE nombre_farmaco like '%$nombre%' group by codigo_unico";
    
   // echo "Query " . $query ;
}

if(!empty($query)){
    // get a product from products table   
    $result = mysql_query($query);

        if (!empty($result)) {
            // check for empty result

            //Comentar
            $veces = 0;            
            
            while ($fila = mysql_fetch_array($result) ) {

                //Comentar
               // echo "Count ->". $veces;
                //$result = mysql_fetch_array($result);

                $product = array();
                $product["codigo_unico"] = $fila["codigo_unico"];
                $product["espeCOD"] = $fila["espeCOD"];
                $product["espeSAN"] = $fila["espeSAN"];
                $product["nombre_farmaco"] = $fila["nombre_farmaco"];
                $product["codigo_lab"] = $fila["codigo_lab"];              
                $product["codigo_nacional"] = $fila["codigo_nacional"];
                $product["espedes"] =$fila["espedes"];
                $product["codigo_sal"] = $fila["codigo_sal"];
                $product["unidad"] = $fila["unidad"];
                $product["cantidad"] = $fila["cantidad"];
               
                
                // info lab
                $cod_lab = $product["codigo_lab"];
                $lab  = mysql_query("SELECT *FROM laboratorios WHERE codigo_lab = $cod_lab");
                if (!empty($lab)) {
                    //Comentar
                    //echo "Entra en el lab " . $veces;
                    // check for empty result
                    if (mysql_num_rows($lab) > 0) {

                      $lab = mysql_fetch_array($lab);
                        $product["codigo_lab"] = $lab["codigo_lab"];
                        $product["nombre_lab"] = $lab["nombre_lab"];
                        $product["nombre_lab2"] = $lab["nombre_lab2"];                        
                    }
                }

                // info A.P 
              

                // success
                
                $veces = $veces +1;
                // user node

                $response["product".$veces] = array();


                array_push($response["product".$veces], $product);

            }
                $response["success"] = 1;
                // echoing JSON response
                echo json_encode($response);
                
               
             
          
        }
        else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No product found";

            // echo no users JSON
            echo json_encode($response);
            
        
        }
}
?>