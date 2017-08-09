<?php

require_once '/home/u332930526/public_html/android_api/include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['uid_user']) && isset($_POST['month']) && isset($_POST['status']) && isset($_POST['year'])) {

    // receiving the post params
    $uid_user = $_POST['uid_user'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $status = $_POST['status'];

    // get the user by uid_user and month
    $medicine = $db->getHistoryByUidAndMonth($uid_user, $month, $year, $status);

    if ($medicine != false && $medicine != "null") {
        $response['medicine'] = $medicine;
        $response['null'] = FALSE;

        //create json $medicine
        echo json_encode($response);
    }
    elseif ($medicine == "null") {
        $response['null'] = TRUE;

        //create json $medicine
        echo json_encode($response);
    } 
    else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response['null'] = FALSE;
        $response["error_msg"] = "input credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response['null'] = FALSE;
    $response["error_msg"] = "Required parameters uid_user or month is missing!";
    echo json_encode($response);
}
?>

