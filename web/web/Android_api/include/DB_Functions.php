<?php



class DB_Functions {



    private $db;



    //put your code here

    // constructor

    function __construct() {

        require_once 'DB_Connect.php';

        // connecting to database

        $this->db = new DB_Connect();

        $this->db->connect();

    }



    // destructor

    function __destruct() {



    }



    /**

     * Storing new user

     * returns user details

     */

    public function storeUser($name, $email, $password) {

        $uuid = uniqid('', true);

        $hash = $this->hashSSHA($password);

        $encrypted_password = $hash["encrypted"]; // encrypted password

        $salt = $hash["salt"]; // salt

        $result = mysql_query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");

        // check for successful store

        if ($result) {

           return true;

        } else {

            return false;

        }

    }
	
	
	public function storeCode($idcodigos, $id_asignatura_alumnos) {

        $result = mysql_query("INSERT INTO asignatura_codigo(id_codigo, id_asignatura_alumno) VALUES($idcodigos,$id_asignatura_alumnos)");
		$result = mysql_query("update asignatura_alumno set num = num + 1 where id_asignatura_alumno = '$id_asignatura_alumnos'");

        // check for successful store

        if ($result) {

            // return user details

            return true;

        } else {

            return false;

        }

    }
	
	public function activateCode($idcodigo) {

        $result = mysql_query("update codigos set fecha_alta = now() where idcodigos = '$idcodigo'");

        // check for successful store

        if ($result) {

            // get user details

            $uid = mysql_insert_id(); // last inserted id

            $result = mysql_query("SELECT * FROM users WHERE uid = '$uid'");

            // return user details

            return mysql_fetch_array($result);

        } else {

            return false;

        }

    }



    /**

     * Get user by email and password

     */

    public function getUserByEmailAndPassword($email, $password) {

        $result = mysql_query("SELECT * FROM alumnos WHERE correo = '$email'") or die(mysql_error());

        // check for result

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            $result = mysql_fetch_array($result);

            $salt = $result['salt'];

            $encrypted_password = $result['password'];

            $hash = $this->checkhashSSHA($salt, $password);

            // check for password equality
			
			$result["profesor"] = "false";

            if ($encrypted_password == $hash) {

                // user authentication details are correct

                return $result;

            }

        }else{
			$result = mysql_query("SELECT * FROM profesor WHERE correo = '$email'") or die(mysql_error());
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0) {
			
			$result = mysql_fetch_array($result);

            $salt = $result['salt'];

            $encrypted_password = $result['password'];

            $hash = $this->checkhashSSHA($salt, $password);
			
			$result["profesor"] = "true";
			if ($encrypted_password == $hash) {

                // user authentication details are correct

                return $result;

            }
			
			}else {

				// user not found

            return false;
			}

        }

    }



    /**

     * Check user is existed or not

     */

    public function isUserExisted($email) {

        $result = mysql_query("SELECT email from users WHERE email = '$email'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // user existed

            return true;

        } else {

            // user not existed

            return false;

        }

    }
	
	/**
	
	*Check if user is singed up on that subject
	
	*/
	
	public function isUserSignedUp($usuario,$asignatura) {

        $result = mysql_query("SELECT id_asignatura_alumno from asignatura_alumno WHERE id_alumno = '$usuario' AND id_asignatura = '$asignatura'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // user existed

            return mysql_fetch_array($result);;

        } else {

            // user not existed

            return false;

        }

    }

	public function isCodeExistingNotSignedUp($codigo) {

        $result = mysql_query("SELECT idcodigos FROM codigos WHERE codigo = '$codigo' AND fecha_alta IS NULL");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return mysql_fetch_array($result);;

        } else {

            // code not existed or signed up

            return false;

        }

    }
	
	public function getSubjects($user) {

        $result = mysql_query("SELECT nombre, curso, grupo, id_asignatura_alumno, id_asignatura FROM `asignaturas`, `asignatura_alumno` WHERE id_alumno='$user' and id_asignatura = id");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }

    }


	public function getTokens($asig_alumn) {

        $result = mysql_query("SELECT codigo, fecha_alta FROM `codigos`, `asignatura_codigo` WHERE id_asignatura_alumno='$asig_alumn' and id_codigo = idcodigos");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }
		}
		
		public function getMisTokens($asig_alumn) {

        $result = mysql_query("SELECT num FROM `asignatura_alumno` WHERE id_asignatura_alumno='$asig_alumn'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }


    }
	
	public function getNumTokens($asig) {

        $result = mysql_query("SELECT SUM(num) FROM `asignatura_alumno` WHERE id_asignatura='$asig'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }


    }
	
	public function getMaxNumTokens($asig) {

        $result = mysql_query("SELECT MAX(num) FROM `asignatura_alumno` WHERE id_asignatura='$asig'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }


    }
	
	public function getLessTokens($asig,$num) {

        $result = mysql_query("SELECT COUNT(num) FROM `asignatura_alumno` WHERE id_asignatura='$asig' and num < '$num'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }


    }
	public function getEqualTokens($asig,$num) {

        $result = mysql_query("SELECT COUNT(num) FROM `asignatura_alumno` WHERE id_asignatura='$asig' and num = '$num'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }


    }
	public function getMoreTokens($asig,$num) {

        $result = mysql_query("SELECT COUNT(num) FROM `asignatura_alumno` WHERE id_asignatura='$asig' and num > '$num'");

        $no_of_rows = mysql_num_rows($result);

        if ($no_of_rows > 0) {

            // code existed

            return $result;

        } else {

            // code not existed

            return false;

        }


    }
	
    /**

     * Encrypting password

     * @param password

     * returns salt and encrypted password

     */

    public function hashSSHA($password) {



        $salt = sha1(rand());

        $salt = substr($salt, 0, 10);

        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);

        $hash = array("salt" => $salt, "encrypted" => $encrypted);

        return $hash;

    }



    /**

     * Decrypting password

     * @param salt, password

     * returns hash string

     */

    public function checkhashSSHA($salt, $password) {



        $hash = base64_encode(sha1($password . $salt, true) . $salt);



        return $hash;

    }



}



?>