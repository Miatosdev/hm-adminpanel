<?php
require dirname(dirname(__FILE__)) . '/inc/Connection.php';

header('Content-type: application/json');

// Use GET parameters instead of reading from input for this API
$country = isset($_GET['country']) ? $_GET['country'] : null;
$state = isset($_GET['state']) ? $_GET['state'] : null;
$region = isset($_GET['region']) ? $_GET['region'] : null;

// Normalize input for comparison
if ($country) {
    $country = strtolower(trim($country)); // Normalize the country input
}

$response = [];

if ($country === "nigeria") {
    if ($state) {
        // Normalize the state input
        $state = trim($state);
        
        if ($region) {
            // If a region is specified, fetch users in that region
            $region = trim($region);
            $sel = $dating->prepare("SELECT * FROM tbl_user WHERE region = ?");
            $sel->bind_param("s", $region);
            $sel->execute();
            $result = $sel->get_result();

            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = array(
                    "id" => $row['id'],
                    "name" => $row['name'], // Adjust this based on tbl_user columns
                    "region" => $row['region']
                );
            }

            if (empty($users)) {
                $response = array(
                    "userlist" => $users,
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "ResponseMsg" => "No users found in this region!"
                );
            } else {
                $response = array(
                    "userlist" => $users,
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "ResponseMsg" => "Users found in the selected region!"
                );
            }
        } else {
            // Fetch regions for the selected state from the database
            $regionSel = $dating->prepare("SELECT DISTINCT region FROM tbl_user WHERE state = ?");
            $regionSel->bind_param("s", $state);
            $regionSel->execute();
            $regionResult = $regionSel->get_result();

            $regions = array();
            while ($row = $regionResult->fetch_assoc()) {
                $regions[] = $row['region'];
            }

            if (empty($regions)) {
                $response = array(
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "ResponseMsg" => "No regions found for the specified state!"
                );
            } else {
                $response = array(
                    "regionlist" => $regions,
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "ResponseMsg" => "Regions found for the selected state!"
                );
            }
        }
    } else {
        // Return the list of states in Nigeria
        $nigerianStates = [
            "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", 
            "Borno", "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", 
            "Gombe", "Imo", "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", 
            "Kogi", "Kwara", "Lagos", "Nasarawa", "Niger", "Ogun", "Ondo",  
            "Osun", "Oyo", "Plateau", "Rivers", "Sokoto", "Taraba", "Yobe", "Zamfara", "FCT"
        ];
        
        $response = array(
            "statelist" => $nigerianStates,
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "States found for Nigeria!"
        );
    }
} else {
    $response = array(
        "ResponseCode" => "200",
        "Result" => "false",
        "ResponseMsg" => "Country not supported or not provided!"
    );
}

echo json_encode($response);
?>
