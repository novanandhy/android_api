<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'include/DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $previllage, $username, $password, $image) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $photo = $uuid . ".png";  
        $path = "upload/$uuid.png";

        $stmt = $this->conn->prepare("INSERT INTO user_tes(unique_id, name, username, encrypted_password, salt, previllage, created_at, image) VALUES(?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("sssssss", $uuid, $name, $username, $encrypted_password, $salt, $previllage, $photo);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            file_put_contents($path,base64_decode($image));
            return true;
        } else {
            return false;
        }
    }

    /**
     * updating existed user
     * returns user details
     */
    public function updateUser($unique_id, $name, $username, $image) {
        $photo = $unique_id . ".png";  
        $path = "upload/$unique_id.png";

        $stmt = $this->conn->prepare("UPDATE user_tes SET username=?, name=?, updated_at=NOW(), image=? WHERE unique_id = ?");
        $stmt->bind_param("ssss", $username, $name, $photo, $unique_id);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            unlink($path);
            file_put_contents($path,base64_decode($image));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get user by username and password
     */
    public function getUserByUsernameAndPassword($username, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM user_tes WHERE username = ?");

        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($username) {
        $stmt = $this->conn->prepare("SELECT username from user_tes WHERE username = ?");

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * store history
     * @param uid,latitude,longitude,year,month,date,hour,minut
     */
    public function storeRelapseData($uid,$latitude,$longitude,$date,$month,$year,$hour,$minute){
        $stmt = $this->conn->prepare("INSERT INTO relapse_history(unique_id, latitude, longitude, date, month, year, hour, minute) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $uid, $latitude, $longitude, $date, $month, $year, $hour, $minute);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            return true;
        } else {
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


    /**
     * conver image to base64
     * @param image string
     * returns base64 string
     */
    public function convertImageToBase64($image){
        $path = "upload/".$image;
        $data = file_get_contents($path);
        $base64 = base64_encode($data);
        return $base64;
    }

}

?>
