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
			if($user["profesor"]==="false"){
			
				$response["user"]["uid"] = $user["idalumno"];
			
			}else{
				$response["user"]["uid"] = $user["idprofesor"];
			}

            $response["user"]["name"] = $user["nombre"];
			
			$response["user"]["surname"] = $user["apellidos"];

            $response["user"]["email"] = $user["correo"];
			
			$response["user"]["profesor"] = $user["profesor"];

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
			$response["success"] = 1;
			$i= 0;
			while($row = mysql_fetch_assoc($subjects)){
				$tokens = $db->getTokens($row["id_asignatura_alumno"]);
				if($tokens){
				$response ['Asignaturas'][$i]['Asignatura']['Datos'] =  $row;
				prev($response);
				$statistic1 = mysql_fetch_assoc( $db->getMisTokens($row["id_asignatura_alumno"]));
				$response ['Asignaturas'][$i]['Asignatura']['Estadisticas']['MisTokens'] = $statistic1["num"];
				$statistic = mysql_fetch_assoc( $db->getNumTokens($row["id_asignatura"]));
				$response ['Asignaturas'][$i]['Asignatura']['Estadisticas']['AllTokens'] = $statistic["SUM(num)"];
				$statistic = mysql_fetch_assoc( $db->getMaxNumTokens($row["id_asignatura"]));
				$response ['Asignaturas'][$i]['Asignatura']['Estadisticas']['MaxTokens'] = $statistic["MAX(num)"];
				$statistic = mysql_fetch_assoc( $db->getLessTokens($row["id_asignatura"],$statistic1["num"]));
				$response ['Asignaturas'][$i]['Asignatura']['Estadisticas']['LessTokens'] = $statistic["COUNT(num)"];
				$statistic = mysql_fetch_assoc( $db->getEqualTokens($row["id_asignatura"],$statistic1["num"]));
				$response ['Asignaturas'][$i]['Asignatura']['Estadisticas']['EqualTokens'] = $statistic["COUNT(num)"];
				$statistic = mysql_fetch_assoc( $db->getMoreTokens($row["id_asignatura"],$statistic1["num"]));
				$response ['Asignaturas'][$i]['Asignatura']['Estadisticas']['MoreTokens'] = $statistic["COUNT(num)"];
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

                $response["error_msg"] = "Error occured obtaining the subjects";

                echo json_encode($response);

            }

        

    }else if ($tag == 'alumno_tag') {

        // Request type is Register new user

        $asig = $_POST['user'];

            // store user

            $students = $db->getStudents($asig);

            if ($students) {
			$response["success"] = 1;
			$i= 0;
			while($row = mysql_fetch_assoc($students)){
				$tokens = $db->getTokens($row["id_asignatura_alumno"]);
				$activities = $db->getActivities($row["id_alumno"],$asig);
				$details = $db->getdetails($row["id_alumno"]);
				if($tokens){
				$response ['Alumnos'][$i]['Alumno']['Datos'] =  $row;
				$response ['Alumnos'][$i]['Alumno']['Details'] =  mysql_fetch_assoc($details);
				prev($response);				
				//$statistic = mysql_fetch_assoc( $db->getMaxNumTokens($row["id_asignatura"]));
				//$response ['Alumnos'][$i]['Alumno']['Estadisticas']['MaxTokens'] = $statistic["MAX(num)"];
				//$statistic = mysql_fetch_assoc( $db->getLessTokens($row["id_asignatura"],$statistic1["num"]));
				//$response ['Alumnos'][$i]['Alumno']['Estadisticas']['LessTokens'] = $statistic["COUNT(num)"];
				//$statistic = mysql_fetch_assoc( $db->getEqualTokens($row["id_asignatura"],$statistic1["num"]));
				//$response ['Alumnos'][$i]['Alumno']['Estadisticas']['EqualTokens'] = $statistic["COUNT(num)"];
				//$statistic = mysql_fetch_assoc( $db->getMoreTokens($row["id_asignatura"],$statistic1["num"]));
				//$response ['Alumnos'][$i]['Alumno']['Estadisticas']['MoreTokens'] = $statistic["COUNT(num)"];
				while($column = mysql_fetch_assoc($tokens)){
				$response ['Alumnos'][$i]['Alumno']['Tokens'][] =  $column;
				}
				while($column = mysql_fetch_assoc($activities)){
				$response ['Alumnos'][$i]['Alumno']['Activities'][] =  $column;
				}
				$i = $i + 1;
				}else{
					$response ['Alumnos'][$i]['Alumno']['Datos'] =  $row;
					$response ['Alumnos'][$i]['Alumno']['Tokens']=array();
					$response ['Alumnos'][$i]['Alumno']['Actividades']=array();
					$response ['Alumnos'][$i]['Alumno']['Details'] =  $row;
					$i = $i + 1;
				}
				//$response ["name"]["tokens"] = db->getTokens($subjects["id_asignatura_alumno"]);
			}
				$statistic1 = mysql_fetch_assoc( $db->getRedeemedTokens($asig));
				$response ['Estadisticas']['Redeemed'] = $statistic1["COUNT(codigo)"];
				$statistic = mysql_fetch_assoc( $db->getNotRedeemedTokens($asig));
				$response ['Estadisticas']['NotRedeemed'] = $statistic["COUNT(codigo)"];
                echo json_encode($response);

            } else {

                // user failed to store

                $response["error"] = 1;

                $response["error_msg"] = "Error occured obtaining the subjects";

                echo json_encode($response);

            }
			
			}else if($tag == 'subject_tag_prof'){
		
		// Request type is Register new user

        $user = $_POST['user'];

            // store user

            $subjects = $db->getSubjectsProf($user);

            if ($subjects) {
			$response["success"] = 1;
			$i= 0;
			while($row = mysql_fetch_assoc($subjects)){
				
				$response ['Asignaturas'][$i]['Datos'] =  $row;				
				$i = $i + 1;
				//$response ["name"]["tokens"] = db->getTokens($subjects["id_asignatura_alumno"]);
			}

                echo json_encode($response);

            } else {

                // user failed to store

                $response["error"] = 1;

                $response["error_msg"] = "Error occured obtaining the subjects";

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
		
	}else if ($tag == 'act_tag') {
		$nota = $_POST['nota'];

        $observaciones = $_POST['observaciones'];	
			
		$id = $_POST['id'];
				
				$db->updateActivity($nota, $observaciones, $id);
				
				
				
				$response["success"] = 1;

				
		 
		   

        
		echo json_encode($response);
		
	}else {

        echo "Invalid Request";

    }

} else {

    echo "Access Denied";

}

?>