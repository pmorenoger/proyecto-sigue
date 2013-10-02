<?php
 
/*
 * Following code will list all the products
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db

$db = new DB_CONNECT();

if (isset($_GET["poblacion"])) {

$poblacion = $_GET['poblacion'];
$query = "SELECT *FROM farmaciasmadrid WHERE poblacion ='$poblacion'";   
}

if (isset($_GET["guardia"])) {
$guardia = $_GET['guardia'];
    if(!empty($query)){

        $query .= " AND guardia = $guardia";
    }

}

  
if (isset($_GET["cp"])){
    $cp = $_GET['cp'];
    if(!empty($query)){

        $query .= " AND cp = $cp";
    }
   
}
    if(!empty($query)){

 
// get all products from products table
$result = mysql_query($query) or die(mysql_error());
 
// check for empty result
if (mysql_num_rows($result) > 0) {
    // looping through all results
    // products node
    $response["farmacias"] = array();
 
    while ($row = mysql_fetch_array($result)) {
        // temp user array
        $product = array();
        $product["poblacion"] = $row["poblacion"];
        $product["direccion"] = $row["direccion"];
        $product["cp"] = $row["cp"];
        $product["guardia"] = $row["guardia"];
        $product["longitud"] = $row["longitud"];
        $product["latitud"] = $row["latitud"];
 
        // push single product into final response array
        array_push($response["farmacias"], $product);
    }
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "No products found";
 
    // echo no users JSON
    echo json_encode($response);
}
}
else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
    mysql_close();
}
?>