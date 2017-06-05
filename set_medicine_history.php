 <?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['unique_id']) && isset($_POST['id_medicine']) && isset($_POST['status']) && isset($_POST['date']) && isset($_POST['month']) && isset($_POST['year'])) {

    // receiving the post params
    $unique_id = $_POST['unique_id'];
    $id_medicine = $_POST['id_medicine'];
    $status = $_POST['status'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    // create a new user
    $user = $db->storeMedicineHistory($unique_id,$id_medicine,$status,$date,$month,$year);
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

