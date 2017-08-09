<?php

require_once '/home/u332930526/public_html/android_api/include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_GET['uid_user'])) {

    $uid_user = $_GET['uid_user'];
    // get the user by uid_user and password
    $user = $db->getAllUser($uid_user);

    if ($user != false) {
        // use is found
        $response["error"] = FALSE;
        $response["user"] = $user;
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters uid_user is missing!";
    echo json_encode($response);
}
?>

