<?php
 
/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// check for post data
if (isset($_GET["codigo"])) {

    $codigo = $_GET['codigo'];
    $query = "SELECT *FROM farmaco1 WHERE codigo_unico = $codigo";
}
if (isset($_GET["cod_nacional"])){
    
    $codigo = $_GET['cod_nacional'];
    $query = "SELECT *FROM farmaco1 WHERE codigo_nacional = $codigo";
}    
    if(!empty($codigo)){
    // get a product from products table
    $result = mysql_query($query);
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $result = mysql_fetch_array($result);
 
            $product = array();
            $product["codigo_unico"] = $result["codigo_unico"];
            $product["espeCOD"] = $result["espeCOD"];
            $product["espeSAN"] = $result["espeSAN"];
            $product["nombre_farmaco"] = $result["nombre_farmaco"];
            $product["codigo_lab"] = $result["codigo_lab"];
            $product["codigo_principio_activo"] = $result["codigo_principio_activo"];
            $product["codigo_nacional"] = $result["codigo_nacional"];
            $product["codigo_sal"] = $result["codigo_sal"];
            $product["unidad"] = $result["unidad"];
            $product["cantidad"] = $result["cantidad"];
            
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
    } else {
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