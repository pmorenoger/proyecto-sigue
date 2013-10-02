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
if (isset($_GET["codigo_lab"])) {

    $codigo = $_GET['codigo_lab'];
    $query = "SELECT *FROM laboratorios WHERE codigo_lab = $codigo";
}
  
    if(!empty($codigo)){
    // get a product from products table
    $result = mysql_query($query);
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $result = mysql_fetch_array($result);
 
            $product = array();
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