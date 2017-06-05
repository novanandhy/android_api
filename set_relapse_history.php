 <?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['unique_id']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['date']) && isset($_POST['month']) && isset($_POST['year']) && isset($_POST['hour']) && isset($_POST['minute'])) {

    // receiving the post params
    $unique_id = $_POST['unique_id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];

    // create a new user
    $user = $db->storeRelapseData($unique_id,$latitude,$longitude,$date,$month,$year,$hour,$minute);
    if ($user) {
        // user stored successfully
        $response["error"] = FALSE;
        echo json_encode($response);
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in store relapse history";
        echo json_encode($response);
        }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>

