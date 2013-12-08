<?php

/**

 * File to handle all API requests

 * Accepts GET and POST

 *

 * Each request will be identified by TAG

 * Response will be JSON data



  /**

 * check for POST request

 */

if (isset($_POST['tag']) && $_POST['tag'] != '') {

    // get tag

    $tag = $_POST['tag'];



    // include db handler

    require_once 'include/DB_Functions.php';

    $db = new DB_Functions();



    // response Array

    $response = array("tag" => $tag, "success" => 0, "error" => 0);



    // check for tag type

    if ($tag == 'login') {

        // Request type is check Login

        $email = $_POST['email'];

        $password = $_POST['password'];



        // check for user

        $user = $db->getUserByEmailAndPassword($email, $password);

        if ($user != false) {

            // user found

            // echo json with success = 1

            $response["success"] = 1;

            $response["uid"] = $user["idalumno"];

            $response["user"]["name"] = $user["nombre"];
			
			$response["user"]["surname"] = $user["apellidos"];

            $response["user"]["email"] = $user["correo"];

            echo json_encode($response);

        } else {

            // user not found

            // echo json with error = 1

            $response["error"] = 1;

            $response["error_msg"] = "Incorrect email or password!";

            echo json_encode($response);

        }

    } else if ($tag == 'register') {

        // Request type is Register new user

        $name = $_POST['name'];

        $email = $_POST['email'];

        $password = $_POST['password'];



        // check if user is already existed

        if ($db->isUserExisted($email)) {

            // user is already existed - error response

            $response["error"] = 2;

            $response["error_msg"] = "User already existed";

            echo json_encode($response);

        } else {

            // store user

            $user = $db->storeUser($name, $email, $password);

            if ($user) {

                // user stored successfully

                $response["success"] = 1;

                $response["uid"] = $user["unique_id"];

                $response["user"]["name"] = $user["name"];

                $response["user"]["email"] = $user["email"];

                $response["user"]["created_at"] = $user["created_at"];

                $response["user"]["updated_at"] = $user["updated_at"];

                echo json_encode($response);

            } else {

                // user failed to store

                $response["error"] = 1;

                $response["error_msg"] = "Error occured in Registartion";

                echo json_encode($response);

            }

        }

    }else if ($tag == 'subject_tag') {

        // Request type is Register new user

        $user = $_POST['user'];

            // store user

            $subjects = $db->getSubjects($user);

            if ($subjects) {
			//$response []= array(
			//			'Asignatura'=>array(
			//							'Datos'=>array(),
			//							'Tokens'=>array())
			//			);
			//			prev($response);
			$response["success"] = 1;
			$i= 0;
			while($row = mysql_fetch_assoc($subjects)){
				$tokens = $db->getTokens($row["id_asignatura_alumno"]);
				if($tokens){
				$response ['Asignaturas'][$i]['Asignatura']['Datos'] =  $row;
				prev($response);
				while($column = mysql_fetch_assoc($tokens)){
				$response ['Asignaturas'][$i]['Asignatura']['Tokens'][] =  $column;
				}
				$i = $i + 1;
				}else{
					$response ['Asignaturas'][$i]['Asignatura']['Datos'] =  $row;
					$response ['ASignaturas'][$i]['Asignatura']['Tokens']=array();
					$i = $i + 1;
				}
				//$response ["name"]["tokens"] = db->getTokens($subjects["id_asignatura_alumno"]);
			}

                echo json_encode($response);

            } else {

                // user failed to store

                $response["error"] = 1;

                $response["error_msg"] = "Error occured in Registartion";

                echo json_encode($response);

            }

        

    }else if ($tag == 'qr_register') {
		$codigo = $_POST['codigo'];

        $asignatura = $_POST['asignatura'];

        $user = $_POST['user'];
		
		$alumn_asig = $db->isUserSignedUp($user,$asignatura);
		
		if ($alumn_asig) {

           $id_asignatura_alumno = $alumn_asig["id_asignatura_alumno"];
		   
		   $idcod = $db->isCodeExistingNotSignedUp($codigo);
		   
		   if ($idcod){
		   
				$idcodigos = $idcod["idcodigos"];
				
				$db->activateCode($idcodigos);
				
				$db->storeCode($idcodigos,$id_asignatura_alumno);
				
				$response["success"] = 1;

                

                $response["user"]["idcodigos"] = $idcod["idcodigos"];
				
		   }else{
		   
				$response["error"] = 2;

				$response["error_msg"] = "Code not existing or already signed up";
		   }

        } else {
		 // user is already signed up - error response
		 
			$response["error"] = 2;

            $response["error_msg"] = "User Not Signed Up on that subject";
	}	
		echo json_encode($response);
	}else {

        echo "Invalid Request";

    }

} else {

    echo "Access Denied";

}

?>