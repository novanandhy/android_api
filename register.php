 <?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['previllage']) && isset($_POST['username'])&& isset($_POST['password'])&& isset($_POST['image'])) {

    // receiving the post params
    $name = $_POST['name'];
    $previllage = $_POST['previllage'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $image = $_POST['image'];

    // check if user is already existed with the same username
    if ($db->isUserExisted($username)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $username;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($name, $previllage, $username, $password, $image);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["username"] = $user["username"];
            $response["user"]["previllage"] = $user["previllage"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>

