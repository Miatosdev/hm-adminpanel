<?php
require "Connection.php";
require "Gomeet.php";

if (isset($_POST["type"])) {
    if ($_POST['type'] == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sel_lan = $_POST["sel_lan"];
		$stype = $_POST["stype"];
        $h = new Gomeet($dating);
        if($stype == 'Admin')
		{
        $count = $h->datinglogin($username, $password, 'admin');
        if ($count != 0) {
            $_SESSION['datingname'] = $username;
            $_SESSION["sel_lan"] = $sel_lan;
			$_SESSION["stype"] = $stype;
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Login Successfully!", "message" => "welcome admin!!", "action" => "dashboard.php"];
        } else {
            $returnArr = ["ResponseCode" => "200", "Result" => "false", "title" => "Please Use Valid Data!!", "message" => "welcome admin!!", "action" => "index.php"];
        }
		}
		else 
		{
			$count = $h->datinglogin($username, $password, 'tbl_manager');
        if ($count != 0) {
            $_SESSION['datingname'] = $username;
            $_SESSION["sel_lan"] = $sel_lan;
			$_SESSION["stype"] = $stype;
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Login Successfully!", "message" => "welcome Staff!!", "action" => "dashboard.php"];
        } else {
            $returnArr = ["ResponseCode" => "200", "Result" => "false", "title" => "Please Use Valid Data!!", "message" => "welcome Staff!!", "action" => "index.php"];
        }
		}
    }
    elseif($_POST["type"] == "add_plan")
	{
		$title = $dating->real_escape_string($_POST["title"]);
		$amt = $_POST['amt'];
		$day_limit = $_POST["day_limit"];
		$description = $dating->real_escape_string($_POST["description"]);
		$filter_include = empty($_POST["filter_include"]) ? 0 : 1;
		$audio_video = empty($_POST["audio_video"]) ? 0 : 1;
		$direct_chat = empty($_POST["direct_chat"]) ? 0 : 1;
		$chat = empty($_POST["chat"]) ? 0 : 1;
		$Like_menu = empty($_POST["Like_menu"]) ? 0 : 1;
		$status = $_POST["status"];
		
		$table = "tbl_plan";
            $field_values = [
                "title",
                "amt",
                "day_limit",
                "description",
                "filter_include",
                "audio_video",
                "direct_chat",
                "chat",
                "Like_menu",
				"status"
            ];
            $data_values = [
                "$title",
                "$amt",
                "$day_limit",
                "$description",
                "$filter_include",
                "$audio_video",
                "$direct_chat",
                "$chat",
                "$Like_menu",
				"$status"
            ];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Plan Add Successfully!!",
                    "message" => "Plan section!",
                    "action" => "list_plan.php",
                ];
            } 
	}
elseif ($_POST['type'] == 'push_notification') {
	$ntitle = $dating->real_escape_string($_POST['ntitle']);
	$nmessage = $dating->real_escape_string($_POST['nmessage']);
	$nurl = $_POST['nurl'];
	$key = $set['one_key'];
	$hash = $set['one_hash'];
	$content = array(
       "en" => $nmessage
   );
$heading = array(
   "en" => $ntitle
);

if($nurl != '')
{
$fields = array(
'app_id' => $key,
'included_segments' =>  array("All"),
'contents' => $content,
'headings' => $heading,
'big_picture' => $nurl
);
$fields = json_encode($fields);
}
else {
	$fields = array(
'app_id' => $key,
'included_segments' =>  array("All"),
'contents' => $content,
'headings' => $heading
);
$fields = json_encode($fields);
}
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$hash));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);
$returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Notification Send Successfully!!", "message" => "Notification section!", "action" => "push_notification.php"];
	}	
	elseif($_POST["type"] == "edit_plan")
	{
		$title = $dating->real_escape_string($_POST["title"]);
		$amt = $_POST['amt'];
		$day_limit = $_POST["day_limit"];
		$description = $dating->real_escape_string($_POST["description"]);
		$filter_include = empty($_POST["filter_include"]) ? 0 : 1;
		$audio_video = empty($_POST["audio_video"]) ? 0 : 1;
		$direct_chat = empty($_POST["direct_chat"]) ? 0 : 1;
		$chat = empty($_POST["chat"]) ? 0 : 1;
		$Like_menu = empty($_POST["Like_menu"]) ? 0 : 1;
		$status = $_POST["status"];
		$id = $_POST["id"];
		
		$table = "tbl_plan";
                $field = ["status" => $status, "title" => $title,"amt"=>$amt,"day_limit"=>$day_limit,"description"=>$description,"filter_include"=>$filter_include,"audio_video"=>$audio_video,"direct_chat"=>$direct_chat,"chat"=>$chat,"Like_menu"=>$Like_menu];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Plan Update Successfully!!",
                        "message" => "Plan section!",
                        "action" => "list_plan.php",
                    ];
                } 
	}
	elseif ($_POST["type"] == "add_coupon") {
        $expire_date = $_POST["expire_date"];
        $status = $_POST["status"];
        $coupon_code = $_POST["coupon_code"];
        $min_amt = $_POST["min_amt"];
        $coupon_val = $_POST["coupon_val"];
        $description = $dating->real_escape_string($_POST["description"]);
        $title = $dating->real_escape_string($_POST["title"]);
        $subtitle = $dating->real_escape_string($_POST["subtitle"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/coupon/";
        $url = "images/coupon/";
        $temp = explode(".", $_FILES["coupon_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["coupon_img"]["tmp_name"], $target_file);
            $table = "tbl_coupon";
            $field_values = [
                "expire_date",
                "status",
                "title",
                "coupon_code",
                "min_amt",
                "coupon_val",
                "description",
                "subtitle",
                "coupon_img",
            ];
            $data_values = [
                "$expire_date",
                "$status",
                "$title",
                "$coupon_code",
                "$min_amt",
                "$coupon_val",
                "$description",
                "$subtitle",
                "$url",
            ];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Coupon Add Successfully!!",
                    "message" => "Coupon section!",
                    "action" => "list_coupon.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "fake_user") {
		
		function generateRandomBirthdateAbove18() {
    // Calculate the maximum date for 18 years ago from today
    $maxDate = date('Y-m-d', strtotime('-18 years'));

    // Generate a random timestamp for the date of birth
    $randomTimestamp = mt_rand(strtotime('-70 years'), strtotime($maxDate)); // Assuming age up to 70 years

    // Convert the timestamp to a date
    $randomBirthdate = date('Y-m-d', $randomTimestamp);

    return $randomBirthdate;
}

function generateRandomMobileNumber($length) 
{
	require 'Connection.php';
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    $randomNumber = rand($min, $max);
    // Trim the random number to the desired length
    $randomNumber = substr($randomNumber, 0, $length - 1);
    $number = '9' . $randomNumber;
$c_refer = $dating->query("select * from tbl_user where mobile='".$number."'")->num_rows;
	if($c_refer != 0)
	{
		generateRandomMobileNumber($_POST['limit_mobile']);
	}
	else 
	{
		return $number;
	}
}

function generateRandomBio() {
    $hobbies = ['reading', 'traveling', 'cooking', 'hiking', 'photography', 'painting', 'gaming', 'swimming'];
    $adjectives = ['enthusiastic', 'passionate', 'dedicated', 'creative', 'adventurous', 'thoughtful', 'energetic', 'friendly'];
    $jobs = ['developer', 'designer', 'writer', 'teacher', 'engineer', 'artist', 'nurse', 'musician'];
    $funFacts = [
        'I have visited 15 countries.',
        'I can speak three languages.',
        'I love to cook exotic dishes.',
        'I have a black belt in karate.',
        'I am an amateur photographer.',
        'I enjoy stargazing on clear nights.',
        'I run marathons for fun.',
        'I have a collection of rare books.'
    ];

    $selectedHobby = $hobbies[array_rand($hobbies)];
    $selectedAdjective = $adjectives[array_rand($adjectives)];
    $selectedJob = $jobs[array_rand($jobs)];
    $selectedFunFact = $funFacts[array_rand($funFacts)];

    $bio = "I am a(n) $selectedAdjective $selectedJob who loves $selectedHobby. $selectedFunFact";
    return $bio;
}

function generateRandomEmail() {
	require 'Connection.php';
    // Array of common email domains
    $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'aol.com'];

    // Generate a random string for the email username
    $username = generateRandomString(8); // Adjust the length as needed

    // Randomly select an email domain
    $domain = $domains[array_rand($domains)];

    // Concatenate the username, "@" symbol, and domain to form the email address
    $email = $username . '@' . $domain;

    
	
	$c_refer = $dating->query("select * from tbl_user where email='".$email."'")->num_rows;
	if($c_refer != 0)
	{
		generateRandomEmail();
	}
	else 
	{
		return $email;
	}
	
}

function generate_random()
{
	require 'Connection.php';
	$six_digit_random_number = mt_rand(100000, 999999);
	$c_refer = $dating->query("select * from tbl_user where code=".$six_digit_random_number."")->num_rows;
	if($c_refer != 0)
	{
		generate_random();
	}
	else 
	{
		return $six_digit_random_number;
	}
}

// Function to generate a random string
function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function generateRandomName() {
    // Arrays of common first names and last names
    $firstNames = ['John', 'Emma', 'Michael', 'Sophia', 'Robert', 'Olivia', 'William', 'Ava', 'James', 'Isabella'];
    $lastNames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor'];

    // Randomly select a first name and a last name
    $randomFirstName = $firstNames[array_rand($firstNames)];
    $randomLastName = $lastNames[array_rand($lastNames)];

    // Concatenate the first name and last name
    $randomName = $randomFirstName . ' ' . $randomLastName;

    return $randomName;
}

function getRandomImageFromDirectory($directory) {
    // Get all files in the directory
    $files = glob($directory . '/*');

    // Filter out directories from the list of files
    $imageFiles = array_filter($files, 'is_file');

    // Select a random image file from the list
    $randomImage = $imageFiles[array_rand($imageFiles)];

    return basename($randomImage); // Return only the filename
}

function generateRandomNearbyCoordinates($latitude, $longitude, $radiusInMeters) {
    // Convert radius from meters to degrees
    $radiusInDegrees = $radiusInMeters / 111320;

    // Generate two random numbers
    $u = mt_rand() / mt_getrandmax();
    $v = mt_rand() / mt_getrandmax();

    // Random distance and angle
    $w = $radiusInDegrees * sqrt($u);
    $t = 2 * pi() * $v;

    // Calculate the new latitude and longitude
    $deltaLat = $w * cos($t);
    $deltaLng = $w * sin($t) / cos(deg2rad($latitude));

    // New latitude and longitude
    $newLat = $latitude + $deltaLat;
    $newLng = $longitude + $deltaLng;

    return ['latitude' => $newLat, 'longitude' => $newLng];
}

// Example usage

$n_user = $_POST['n_user'];    

	for($i=0;$i<$n_user;$i++)
	{
		$password = empty($_POST['password']) ? "123456789" : $_POST['password'];
$gender = $_POST['gender'];
$genders = ['MALE', 'FEMALE'];
$timestamp = date("Y-m-d H:i:s");
$radius_search = rand(1,500);
    if ($gender == 'Random') {
        // Select a random gender
        $selected_gender = $genders[array_rand($genders)];
    } elseif ($gender == 'MALE') {
        // Select male
        $selected_gender = 'MALE';
    } elseif ($gender == 'FEMALE') {
        // Select female
        $selected_gender = 'FEMALE';
    }
if($selected_gender == 'MALE')
{
	$directory = dirname(dirname(__FILE__)) . '/images/male';

// Get a random image from the directory
$randomImage = getRandomImageFromDirectory($directory);

// Output the path to the random image including the directory
$other_pic =  'images/male/' . $randomImage;
}
else 
{
	$directory = dirname(dirname(__FILE__)) . '/images/female';

// Get a random image from the directory
$randomImage = getRandomImageFromDirectory($directory);

// Output the path to the random image including the directory
$other_pic = 'images/female/' . $randomImage;
}
    $search_preference = $_POST['search_preference'];
    if ($search_preference == 0) {
        $preference = $selected_gender;
    } else {
        if ($selected_gender == 'MALE') {
            // Select female
            $preference = 'FEMALE';
        } elseif ($selected_gender == 'FEMALE') {
            // Select male
            $preference = 'MALE';
        }
    }
	$lin = $_POST['interest'];
	$llang = $_POST['language'];
$latitude = $_POST['latitude'];  
$longitude = $_POST['longtitude']; 
$radiusInMeters = $_POST['radius'] * 1000; 

$coordinates = generateRandomNearbyCoordinates($latitude, $longitude, $radiusInMeters);

$lats = $coordinates['latitude'];
$longs = $coordinates['longitude'];
$mobile = generateRandomMobileNumber($_POST['limit_mobile']);
$ccode = $_POST['ccode'];
$code = generate_random();
$name = generateRandomName();
$bio = generateRandomBio();
$birthdate = generateRandomBirthdateAbove18();
$email = generateRandomEmail();

		$goal = $dating->query("SELECT GROUP_CONCAT(id) AS random_goal FROM ( SELECT id FROM `relation_goal` ORDER BY RAND() LIMIT 1 ) AS random_ids_subquery")->fetch_assoc();
		$relation_goal = $goal['random_goal'];
		$intr = $dating->query("SELECT GROUP_CONCAT(id) AS random_interest FROM ( SELECT id FROM `tbl_interest` ORDER BY RAND() LIMIT $lin ) AS random_ids_subquery")->fetch_assoc();
		$interest = $intr['random_interest'];
		$lang = $dating->query("SELECT GROUP_CONCAT(id) AS random_language FROM ( SELECT id FROM `tbl_language` ORDER BY RAND() LIMIT $llang ) AS random_ids_subquery")->fetch_assoc();
		$language = $lang['random_language'];
		$rel = $dating->query("SELECT GROUP_CONCAT(id) AS random_religion FROM ( SELECT id FROM `tbl_religion` ORDER BY RAND() LIMIT 1 ) AS random_ids_subquery")->fetch_assoc();
		$religion = $rel['random_religion'];
		$table = "tbl_user";
            $field_values = ["coin","name", "mobile", "password","rdate","ccode","code","email","gender","lats","longs","profile_bio","birth_date","search_preference","radius_search","relation_goal","interest","language","religion","other_pic","user_type"];
            $data_values = ["$coin","$name", "$mobile", "$password","$timestamp","$ccode","$code","$email","$selected_gender","$lats","$longs","$bio","$birthdate","$preference","$radius_search","$relation_goal","$interest","$language","$religion","$other_pic","FAKE_USER"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
	}
	
	if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Fake User Add Successfully!!",
                    "message" => "Fake User section!",
                    "action" => "fake_user.php",
                ];
            } 
			
}elseif ($_POST["type"] == "add_staff") {
$interest = isset($_POST["interest"]) ? implode(',', $_POST["interest"]) : "";
$page = isset($_POST["page"]) ? implode(',', $_POST["page"]) : "";
$faq = isset($_POST["faq"]) ? implode(',', $_POST["faq"]) : "";
$fakeuser = isset($_POST["fakeuser"]) ? implode(',', $_POST["fakeuser"]) : "";
$plist = isset($_POST["plist"]) ? implode(',', $_POST["plist"]) : "";
$language = isset($_POST["language"]) ? implode(',', $_POST["language"]) : "";
$payout = isset($_POST["payout"]) ? implode(',', $_POST["payout"]) : "";
$report = isset($_POST["report"]) ? implode(',', $_POST["report"]) : "";
$religion = isset($_POST["religion"]) ? implode(',', $_POST["religion"]) : "";
$gift = isset($_POST["gift"]) ? implode(',', $_POST["gift"]) : "";
$rgoal = isset($_POST["rgoal"]) ? implode(',', $_POST["rgoal"]) : "";
$notification = isset($_POST["notification"]) ? implode(',', $_POST["notification"]) : "";
$plan = isset($_POST["plan"]) ? implode(',', $_POST["plan"]) : "";
$package = isset($_POST["package"]) ? implode(',', $_POST["package"]) : "";
$ulist = isset($_POST["ulist"]) ? implode(',', $_POST["ulist"]) : "";
$wallet = isset($_POST["wallet"]) ? implode(',', $_POST["wallet"]) : "";
$coin = isset($_POST["coin"]) ? implode(',', $_POST["coin"]) : "";
$email = $dating->real_escape_string($_POST["email"]);
$password = $dating->real_escape_string($_POST["password"]);
$status = $_POST["status"];

$table = "tbl_manager";
            $field_values = ["wallet","coin","interest", "status", "password","page","faq","fakeuser","plist","language","payout","report","religion","gift","rgoal","notification","plan","package","ulist","email"];
            $data_values = ["$wallet","$coin","$interest", "$status", "$password","$page","$faq","$fakeuser","$plist","$language","$payout","$report","$religion","$gift","$rgoal","$notification","$plan","$package","$ulist","$email"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Manager Add Successfully!!",
                    "message" => "Manager section!",
                    "action" => "list_staff.php",
                ];
            } 
			
}elseif ($_POST["type"] == "edit_staff") {
$interest = isset($_POST["interest"]) ? implode(',', $_POST["interest"]) : "";
$page = isset($_POST["page"]) ? implode(',', $_POST["page"]) : "";
$faq = isset($_POST["faq"]) ? implode(',', $_POST["faq"]) : "";
$fakeuser = isset($_POST["fakeuser"]) ? implode(',', $_POST["fakeuser"]) : "";
$plist = isset($_POST["plist"]) ? implode(',', $_POST["plist"]) : "";
$language = isset($_POST["language"]) ? implode(',', $_POST["language"]) : "";
$payout = isset($_POST["payout"]) ? implode(',', $_POST["payout"]) : "";
$report = isset($_POST["report"]) ? implode(',', $_POST["report"]) : "";
$religion = isset($_POST["religion"]) ? implode(',', $_POST["religion"]) : "";
$gift = isset($_POST["gift"]) ? implode(',', $_POST["gift"]) : "";
$rgoal = isset($_POST["rgoal"]) ? implode(',', $_POST["rgoal"]) : "";
$notification = isset($_POST["notification"]) ? implode(',', $_POST["notification"]) : "";
$plan = isset($_POST["plan"]) ? implode(',', $_POST["plan"]) : "";
$package = isset($_POST["package"]) ? implode(',', $_POST["package"]) : "";
$wallet = isset($_POST["wallet"]) ? implode(',', $_POST["wallet"]) : "";
$coin = isset($_POST["coin"]) ? implode(',', $_POST["coin"]) : "";
$ulist = isset($_POST["ulist"]) ? implode(',', $_POST["ulist"]) : "";
$email = $dating->real_escape_string($_POST["email"]);
$password = $dating->real_escape_string($_POST["password"]);
$status = $_POST["status"];
$id = $_POST["id"];

$table = "tbl_manager";
                $field = ["coin"=>$coin,"wallet"=>$wallet,"status" => $status, "interest" => $interest,"page"=>$page,"faq" => $faq, "fakeuser" => $fakeuser,"plist"=>$plist,"language" => $language, "payout" => $payout,"report"=>$report,"religion"=>$religion,"gift"=>$gift,"rgoal"=>$rgoal,"notification"=>$notification,"plan"=>$plan,"package"=>$package,"ulist"=>$ulist,"email"=>$email,"password"=>$password];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));
				
if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Manager Update Successfully!!",
                    "message" => "Manager section!",
                    "action" => "list_staff.php",
                ];
            } 
			
}elseif ($_POST["type"] == "com_payout") {
        $payout_id = $_POST["payout_id"];
        $target_dir = dirname(dirname(__FILE__)) . "/images/payout/";
        $url = "images/payout/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "payout_setting";
            $field = ["proof" => $url, "status" => "completed"];
            $where = "where id=" . $payout_id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));

            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Payout Update Successfully!!",
                    "message" => "Payout section!",
                    "action" => "list_payout.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "add_gal") {
        $status = $_POST["status"];
		$dating_id = $_POST['dating_id'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/gallery/";
        $url = "images/gallery/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
       
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_gallery";
            $field_values = ["img", "status", "dating_id"];
            $data_values = ["$url", "$status", "$dating_id"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Gallery Add Successfully!!",
                    "message" => "Gallery section!",
                    "action" => "list_gal.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "edit_gal") {
        $status = $_POST["status"];
		$dating_id = $_POST['dating_id'];
		$id = $_POST['id'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/gallery/";
        $url = "images/gallery/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
           
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_gallery";
                $field = ["status" => $status, "img" => $url,"dating_id"=>$dating_id];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Gallery Update Successfully!!",
                        "message" => "Gallery section!",
                        "action" => "list_gal.php",
                    ];
                } 
            
        } else {
            $table = "tbl_gallery";
            $field = ["status" => $status,"dating_id"=>$dating_id];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Gallery Update Successfully!!",
                    "message" => "Gallery section!",
                    "action" => "list_gal.php",
                ];
            } 
        }
    }elseif($_POST["type"] == "add_dating")
	{
		$dating_number = $_POST["dating_number"];
        $dating_status = $_POST["dating_status"];
        $dating_rating = $_POST["dating_rating"];
        $total_seat = $_POST["total_seat"];
        $dating_ac = $_POST["dating_ac"];
        $dating_title = $dating->real_escape_string($_POST["dating_title"]);
        $driver_name = $dating->real_escape_string($_POST["driver_name"]);
        $driver_mobile = $dating->real_escape_string($_POST["driver_mobile"]);
        
		
		$dating_gear = $_POST["dating_gear"];
		$dating_facility = implode(',',$_POST["dating_facility"]);
		$dating_type = $_POST["dating_type"];
		$dating_brand = $_POST["dating_brand"];
		$dating_available = $_POST["dating_available"];
		$dating_rent_price = $_POST["dating_rent_price"];
		$dating_rent_price_driver = $_POST["dating_rent_price_driver"];
		$engine_hp = $_POST["engine_hp"];
		$price_type = $_POST["price_type"];
		$fuel_type = $_POST["fuel_type"];
		$dating_desc = $dating->real_escape_string($_POST["dating_desc"]);
		$pick_address = $dating->real_escape_string($_POST["pick_address"]);
		$pick_lat = $_POST["pick_lat"];
		$pick_lng = $_POST["pick_lng"];
		$total_km = $_POST["total_km"];
		
		$imageList = '';
$url = 'images/dating/';
 $v = array();
   foreach ($_FILES['dating_img']['name'] as $key => $filename) {
    $tempLocation = $_FILES['dating_img']['tmp_name'][$key];
    $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
    $target_path = dirname(dirname(__FILE__)) . '/images/dating/';
    $v[] = $url . $newname;
    move_uploaded_file($tempLocation, $target_path . $newname);

    $temp = explode(".", $filename);
    // Check if the file extension is not allowed
    
}

$imageList = implode('$;', $v);
            
			$table = "tbl_dating";
            $field_values = [
                "dating_number",
                "dating_status",
                "dating_title",
                "dating_rating",
                "total_seat",
                "dating_ac",
                "driver_name",
                "driver_mobile",
                "dating_img",
				"dating_gear",
				"dating_facility",
				"dating_type",
				"dating_brand",
				"dating_available",
				"dating_rent_price",
				"dating_rent_price_driver",
				"engine_hp",
				"price_type",
				"fuel_type",
				"dating_desc",
				"pick_address",
				"pick_lat",
				"pick_lng",
				"total_km"
            ];
            $data_values = [
                "$dating_number",
                "$dating_status",
                "$dating_title",
                "$dating_rating",
                "$total_seat",
                "$dating_ac",
                "$driver_name",
                "$driver_mobile",
                "$imageList",
				"$dating_gear",
				"$dating_facility",
				"$dating_type",
				"$dating_brand",
				"$dating_available",
				"$dating_rent_price",
				"$dating_rent_price_driver",
				"$engine_hp",
				"$price_type",
				"$fuel_type",
				"$dating_desc",
				"$pick_address",
				"$pick_lat",
				"$pick_lng",
				"$total_km"
            ];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "dating Add Successfully!!",
                    "message" => "dating section!",
                    "action" => "list_dating.php",
                ];
            } 
		
	}elseif($_POST["type"] == "edit_dating")
	{
		$dating_number = $_POST["dating_number"];
		$id = $_POST["id"];
        $dating_status = $_POST["dating_status"];
        $dating_rating = $_POST["dating_rating"];
        $total_seat = $_POST["total_seat"];
        $dating_ac = $_POST["dating_ac"];
        $dating_title = $dating->real_escape_string($_POST["dating_title"]);
        $driver_name = $dating->real_escape_string($_POST["driver_name"]);
        $driver_mobile = $dating->real_escape_string($_POST["driver_mobile"]);
		$dating_gear = $_POST["dating_gear"];
		$dating_facility = implode(',',$_POST["dating_facility"]);
		$dating_type = $_POST["dating_type"];
		$dating_brand = $_POST["dating_brand"];
		$dating_available = $_POST["dating_available"];
		$dating_rent_price = $_POST["dating_rent_price"];
		$dating_rent_price_driver = $_POST["dating_rent_price_driver"];
		$engine_hp = $_POST["engine_hp"];
		$price_type = $_POST["price_type"];
		$fuel_type = $_POST["fuel_type"];
		$dating_desc = $dating->real_escape_string($_POST["dating_desc"]);
		$pick_address = $dating->real_escape_string($_POST["pick_address"]);
		$pick_lat = $_POST["pick_lat"];
		$pick_lng = $_POST["pick_lng"];
		$total_km = $_POST["total_km"];
		$imlist = $_POST['imlist'];
		
		$imageList = '';
$url = 'images/dating/';
if (!empty($_FILES['dating_img']['name'][0])) {
 $v = array();
   foreach ($_FILES['dating_img']['name'] as $key => $filename) {
    $tempLocation = $_FILES['dating_img']['tmp_name'][$key];
    $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
    $target_path = dirname(dirname(__FILE__)) . '/images/dating/';
    $v[] = $url . $newname;
   

    $temp = explode(".", $filename);
    // Check if the file extension is not allowed
   
	 move_uploaded_file($tempLocation, $target_path . $newname);	
	
}
}

		if (empty($_FILES['dating_img']['name'][0]) && $imlist != "0") {
    // No new image was uploaded, and there are existing images
    $imageList = $imlist;
    
} else if (empty($_FILES['dating_img']['name'][0]) && $imlist == "0") {
    // No new image was uploaded, and there are no existing images
    $imageList = $imlist;
    
} else if ($imlist == "0") {
    // New images were uploaded, and there are no existing images
    $imageList = implode('$;', $v);
    
} else {
    // New images were uploaded, and there are existing images
    $imageList = $imlist . '$;' . implode('$;', $v);
   
}

			$table = "tbl_dating";
                $field = [
                    "dating_number" => $dating_number,
					"dating_img"   =>  $imageList,
                    "dating_status" => $dating_status,
                    "dating_title" => $dating_title,
                    "dating_rating" => $dating_rating,
                    "total_seat" => $total_seat,
                    "dating_ac" => $dating_ac,
                    "driver_name" => $driver_name,
                    "driver_mobile" => $driver_mobile,
					"dating_gear" => $dating_gear,
					"dating_facility" => $dating_facility,
					"dating_type" => $dating_type,
					"dating_brand" => $dating_brand,
					"dating_available" => $dating_available,
					"dating_rent_price" => $dating_rent_price,
					"dating_rent_price_driver" => $dating_rent_price_driver,
					"engine_hp" => $engine_hp,
					"price_type" => $price_type,
					"fuel_type" => $fuel_type,
					"dating_desc" => $dating_desc,
					"pick_address" => $pick_address,
					"pick_lat" => $pick_lat,
					"pick_lng" => $pick_lng,
					"total_km" => $total_km
                ];
                $where =
                    "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "dating Update Successfully!!",
                    "message" => "dating section!",
                    "action" => "list_dating.php",
                ];
            } 
		
	}	elseif ($_POST["type"] == "edit_coupon") {
        $expire_date = $_POST["expire_date"];
        
        $id = $_POST["id"];
        $status = $_POST["status"];
        $coupon_code = $_POST["coupon_code"];
        $min_amt = $_POST["min_amt"];
        $coupon_val = $_POST["coupon_val"];
        $description = $dating->real_escape_string($_POST["description"]);
        $title = $dating->real_escape_string($_POST["title"]);
        $subtitle = $dating->real_escape_string($_POST["subtitle"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/coupon/";
        $url = "images/coupon/";
        $temp = explode(".", $_FILES["coupon_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["coupon_img"]["name"] != "") {
           
                move_uploaded_file(
                    $_FILES["coupon_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_coupon";
                $field = [
                    "status" => $status,
                    "coupon_img" => $url,
                    "title" => $title,
                    "coupon_code" => $coupon_code,
                    "min_amt" => $min_amt,
                    "coupon_val" => $coupon_val,
                    "description" => $description,
                    "subtitle" => $subtitle,
                    "expire_date" => $expire_date,
                ];
                $where =
                    "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Coupon Update Successfully!!",
                        "message" => "Coupon section!",
                        "action" => "list_coupon.php",
                    ];
                } 
            
        } else {
            $table = "tbl_coupon";
            $field = [
                "status" => $status,
                "title" => $title,
                "coupon_code" => $coupon_code,
                "min_amt" => $min_amt,
                "coupon_val" => $coupon_val,
                "description" => $description,
                "subtitle" => $subtitle,
                "expire_date" => $expire_date,
            ];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Coupon Update Successfully!!",
                    "message" => "Coupon section!",
                    "action" => "list_coupon.php",
                ];
            } 
        }
    }elseif ($_POST["type"] == "add_facility") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/facility/";
        $url = "images/facility/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
      
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_facility";
            $field_values = ["img", "status", "title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Facility Add Successfully!!",
                    "message" => "Facility section!",
                    "action" => "list_facility.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "edit_facility") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $title = $dating->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/facility/";
        $url = "images/facility/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
         
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "tbl_facility";
                $field = ["status" => $okey, "img" => $url, "title" => $title];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Facility Update Successfully!!",
                        "message" => "Facility section!",
                        "action" => "list_facility.php",
                    ];
                } 
            
        } else {
            $table = "tbl_facility";
            $field = ["status" => $okey, "title" => $title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($bus);
            $check = $h->busupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Facility Update Successfully!!",
                    "message" => "Facility section!",
                    "action" => "list_facility.php",
                ];
            } 
        }
    }
      elseif ($_POST['type'] == 'add_page') {
        $ctitle = $dating->real_escape_string($_POST['ctitle']);
        $cstatus = $_POST['cstatus'];
        $cdesc = $dating->real_escape_string($_POST['cdesc']);
        $table = "tbl_page";

        $field_values = ["description", "status", "title"];
        $data_values = ["$cdesc", "$cstatus", "$ctitle"];

        $h = new Gomeet($dating);
        $check = trim($h->datinginsertdata($field_values, $data_values, $table));
        if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Page Add Successfully!!", "message" => "Page section!", "action" => "list_page.php"];
        } 
    } elseif ($_POST['type'] == 'edit_page') {
        $id = $_POST['id'];
        $ctitle = $dating->real_escape_string($_POST['ctitle']);
        $cstatus = $_POST['cstatus'];
        $cdesc = $dating->real_escape_string($_POST['cdesc']);

        $table = "tbl_page";
        $field = ['description' => $cdesc, 'status' => $cstatus, 'title' => $ctitle];
        $where = "where id=" . $id . "";
        $h = new Gomeet($dating);
        $check = trim($h->datingupdateData($field, $table, $where));
        if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Page Update Successfully!!", "message" => "Page section!", "action" => "list_page.php"];
        } 
    } elseif ($_POST['type'] == 'edit_payment') {
        $attributes = mysqli_real_escape_string($dating, $_POST['p_attr']);
        $ptitle = mysqli_real_escape_string($dating, $_POST['ptitle']);
        $okey = $_POST['status'];
        $id = $_POST['id'];
        $p_show = $_POST['p_show'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/payment/";
        $url = "images/payment/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != '') {
           
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "tbl_payment_list";
                $field = ['status' => $okey, 'img' => $url, 'attributes' => $attributes, 'subtitle' => $ptitle, 'p_show' => $p_show];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Payment Gateway Update Successfully!!", "message" => "Payment Gateway section!", "action" => "paymentlist.php"];
                } 
            
        } else {
            $table = "tbl_payment_list";
            $field = ['status' => $okey, 'attributes' => $attributes, 'subtitle' => $ptitle, 'p_show' => $p_show];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Payment Gateway Update Successfully!!", "message" => "Payment Gateway section!", "action" => "paymentlist.php"];
            } 
        }
    } elseif ($_POST['type'] == 'add_faq') {
        $question = mysqli_real_escape_string($dating, $_POST['question']);
        $answer = mysqli_real_escape_string($dating, $_POST['answer']);
        $okey = $_POST['status'];

        $table = "tbl_faq";
        $field_values = ["question", "answer", "status"];
        $data_values = ["$question", "$answer", "$okey"];

        $h = new Gomeet($dating);
        $check = trim($h->datinginsertdata($field_values, $data_values, $table));
        if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Faq Add Successfully!!", "message" => "Faq section!", "action" => "list_faq.php"];
        } 
    }elseif ($_POST['type'] == 'add_wallet_balance') {
		$uid = $_POST["id"];
		$wallet  = $_POST['amount'];
	$vp = $dating->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	  
  $table="tbl_user";
  $field = array('wallet'=>$vp['wallet']+$wallet);
  $where = "where id=".$uid."";
$h = new Gomeet($dating);
	  $check = $h->datingupdateData($field,$table,$where);
	  
	  $timestamps    = date("Y-m-d");
	   $table="wallet_report";
  $field_values=array("uid","message","status","amt","tdate");
  $data_values=array("$uid",'Wallet Balance Added!!','Credit',"$wallet","$timestamps");
   
      $h = new Gomeet($dating);
	  $checks = $h->datinginsertdata($field_values,$data_values,$table);
	  
	  if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Wallet Add Successfully!!", "message" => "Wallet section!", "action" => "wallet_manage.php?id=".$uid];
        } 
	}elseif ($_POST['type'] == 'add_coin_balance') {
		$uid = $_POST["id"];
		$wallet  = $_POST['amount'];
	$vp = $dating->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	  
  $table="tbl_user";
  $field = array('coin'=>$vp['coin']+$wallet);
  $where = "where id=".$uid."";
$h = new Gomeet($dating);
	  $check = $h->datingupdateData($field,$table,$where);
	  
	  $timestamps    = date("Y-m-d");
	   $table="coin_report";
  $field_values=array("uid","message","status","amt","tdate");
  $data_values=array("$uid",'Coin Balance Added!!','Credit',"$wallet","$timestamps");
   
      $h = new Gomeet($dating);
	  $checks = $h->datinginsertdata($field_values,$data_values,$table);
	  
	  if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Coin Add Successfully!!", "message" => "Coin section!", "action" => "coin_manage.php?id=".$uid];
        } 
	}elseif ($_POST['type'] == 'sub_wallet_balance') {
		$uid = $_POST["id"];
		$wallet  = $_POST['amount'];
	$vp = $dating->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	  if ($vp['wallet'] >= $wallet) {
  $table="tbl_user";
  $field = array('wallet'=>$vp['wallet']-$wallet);
  $where = "where id=".$uid."";
$h = new Gomeet($dating);
	  $check = $h->datingupdateData($field,$table,$where);
	  
	  $timestamps    = date("Y-m-d");
	   $table="wallet_report";
  $field_values=array("uid","message","status","amt","tdate");
  $data_values=array("$uid",'Wallet Balance Substract!!','Debit',"$wallet","$timestamps");
   
      $h = new Gomeet($dating);
	  $checks = $h->datinginsertdata($field_values,$data_values,$table);
	  
	  if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Wallet Substract Successfully!!", "message" => "Wallet section!", "action" => "wallet_manage.php?id=".$uid];
        } 
	  }
	  else 
	  {
		$returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Wallet Balance Not There As Per Operation Refresh One Time Screen!!!", "message" => "Wallet section!", "action" => "wallet_manage.php?id=".$uid];  
	  }
	}elseif ($_POST['type'] == 'sub_coin_balance') {
		$uid = $_POST["id"];
		$wallet  = $_POST['amount'];
	$vp = $dating->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
	  if ($vp['coin'] >= $wallet) {
  $table="tbl_user";
  $field = array('coin'=>$vp['coin']-$wallet);
  $where = "where id=".$uid."";
$h = new Gomeet($dating);
	  $check = $h->datingupdateData($field,$table,$where);
	  
	  $timestamps    = date("Y-m-d");
	   $table="coin_report";
  $field_values=array("uid","message","status","amt","tdate");
  $data_values=array("$uid",'Coin Balance Substract!!','Debit',"$wallet","$timestamps");
   
      $h = new Gomeet($dating);
	  $checks = $h->datinginsertdata($field_values,$data_values,$table);
	  
	  if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Coin Substract Successfully!!", "message" => "Coin section!", "action" => "coin_manage.php?id=".$uid];
        } 
	  }
	  else 
	  {
		$returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Coin Balance Not There As Per Operation Refresh One Time Screen!!!", "message" => "Coin section!", "action" => "coin_manage.php?id=".$uid];  
	  }
	}elseif ($_POST['type'] == 'add_package') {
        $coin = mysqli_real_escape_string($dating, $_POST['coin']);
        $amt = mysqli_real_escape_string($dating, $_POST['amt']);
        $status = $_POST['status'];

        $table = "tbl_package";
        $field_values = ["coin", "amt", "status"];
        $data_values = ["$coin", "$amt", "$status"];

        $h = new Gomeet($dating);
        $check = trim($h->datinginsertdata($field_values, $data_values, $table));
        if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Package Add Successfully!!", "message" => "Package section!", "action" => "list_package.php"];
        } 
    }elseif ($_POST['type'] == 'edit_package') {
        $coin = mysqli_real_escape_string($dating, $_POST['coin']);
        $amt = mysqli_real_escape_string($dating, $_POST['amt']);
        $status = $_POST['status'];
        $id = $_POST['id'];
        $table = "tbl_package";
        $field = ['coin' => $coin, 'status' => $status, 'amt' => $amt];
        $where = "where id=" . $id . "";
        $h = new Gomeet($dating);
        $check = trim($h->datingupdateData($field, $table, $where));
        if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Package Update Successfully!!", "message" => "Package section!", "action" => "list_package.php"];
        } 
    } elseif ($_POST['type'] == 'edit_faq') {
        $question = mysqli_real_escape_string($dating, $_POST['question']);
        $answer = mysqli_real_escape_string($dating, $_POST['answer']);
        $okey = $_POST['status'];
        $id = $_POST['id'];

        $table = "tbl_faq";
        $field = ['question' => $question, 'status' => $okey, 'answer' => $answer];
        $where = "where id=" . $id . "";
        $h = new Gomeet($dating);
        $check = trim($h->datingupdateData($field, $table, $where));
        if ($check == 1) {
            $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Faq Update Successfully!!", "message" => "Faq section!", "action" => "list_faq.php"];
        } 
    }  elseif ($_POST['type'] == 'edit_profile') {
        
            $dname = $_POST['username'];
            $dsname = $_POST['password'];
            $id = $_POST['id'];
            $table = "admin";
            $field = ['username' => $dname, 'password' => $dsname];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Profile Update Successfully!!", "message" => "Profile  section!", "action" => "profile.php"];
            } 
        
    }  elseif ($_POST['type'] == 'edit_setting') {
        $webname = mysqli_real_escape_string($dating, $_POST['webname']);
        $timezone = $_POST['timezone'];
        $currency = $_POST['currency'];
        $id = $_POST['id'];
        $sms_type = $_POST['sms_type'];
		$auth_key = $_POST['auth_key'];
		$otp_id = $_POST['otp_id'];
		$acc_id = $_POST['acc_id'];
		$auth_token = $_POST['auth_token'];
		$twilio_number = $_POST['twilio_number'];
        $admob = $_POST['admob'];
		$slogin = $_POST['slogin'];
		$mode = $_POST['mode'];
		$fmode = $_POST['fmode'];
        $one_key = $_POST['one_key'];
        $one_hash = $_POST['one_hash'];
		$banner_id = $_POST['banner_id'];
		$in_id = $_POST['in_id'];
		$coin_fun = $_POST['coin_fun'];
		$coin_limit = $_POST['coin_limit'];
        $map_key = $_POST['map_key'];
       $coin_amt = $_POST['coin_amt'];
       $otp_auth = $_POST['otp_auth'];
	   $agora_app_id = $_POST['agora_app_id'];
	   $ios_banner_id = $_POST['ios_banner_id'];
	   $ios_in_id = $_POST['ios_in_id'];
	   
        $target_dir = dirname(dirname(__FILE__)) . "/images/website/";
        $url = "images/website/";
        $temp = explode(".", $_FILES["weblogo"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["weblogo"]["name"] != '') {
           
                move_uploaded_file($_FILES["weblogo"]["tmp_name"], $target_file);
                $table = "tbl_setting";
                $field = ['ios_in_id'=>$ios_in_id,'ios_banner_id'=>$ios_banner_id,'agora_app_id'=>$agora_app_id,'coin_fun'=>$coin_fun,'coin_limit'=>$coin_limit,'otp_auth'=>$otp_auth,'map_key'=>$map_key,'in_id'=>$in_id,'banner_id'=>$banner_id,'admob'=>$admob,'slogin'=>$slogin,'mode'=>$mode,'fmode'=>$fmode,'timezone' => $timezone, 'weblogo' => $url, 'webname' => $webname, 'currency' => $currency, 'one_key' => $one_key, 'one_hash' => $one_hash,'twilio_number'=>$twilio_number,'auth_token'=>$auth_token,'acc_id'=>$acc_id,'otp_id'=>$otp_id,'auth_key'=>$auth_key,'sms_type'=>$sms_type,'coin_amt'=>$coin_amt];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Setting Update Successfully!!", "message" => "Setting section!", "action" => "setting.php"];
                } 
            
        } else {
            $table = "tbl_setting";
            $field = ['ios_in_id'=>$ios_in_id,'ios_banner_id'=>$ios_banner_id,'agora_app_id'=>$agora_app_id,'coin_fun'=>$coin_fun,'coin_limit'=>$coin_limit,'otp_auth'=>$otp_auth,'map_key'=>$map_key,'in_id'=>$in_id,'banner_id'=>$banner_id,'admob'=>$admob,'slogin'=>$slogin,'mode'=>$mode,'fmode'=>$fmode,'timezone' => $timezone, 'webname' => $webname, 'currency' => $currency, 'one_key' => $one_key, 'one_hash' => $one_hash,'twilio_number'=>$twilio_number,'auth_token'=>$auth_token,'acc_id'=>$acc_id,'otp_id'=>$otp_id,'auth_key'=>$auth_key,'sms_type'=>$sms_type,'coin_amt'=>$coin_amt];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = ["ResponseCode" => "200", "Result" => "true", "title" => "Setting Update Successfully!!", "message" => "Offer section!", "action" => "setting.php"];
            } 
        }
    } elseif ($_POST["type"] == "add_city") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["title"]);
        
        
            
            $table = "tbl_city";
            $field_values = [ "status", "title"];
            $data_values = [ "$okey", "$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "City Add Successfully!!",
                    "message" => "City section!",
                    "action" => "list_city.php",
                ];
            } 
        
    } elseif ($_POST["type"] == "add_interest") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["cat_name"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/interest/";
        $url = "images/interest/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_interest";
            $field_values = ["img", "status","title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Interest Add Successfully!!",
                    "message" => "Interest section!",
                    "action" => "list_interest.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "add_gift") {
	$okey = $_POST["status"];
	$gprice = $_POST["gprice"];
	$target_dir = dirname(dirname(__FILE__)) . "/images/gift/";
        $url = "images/gift/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        $file_extension = strtolower(end($temp));
      
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
			 $table = "tbl_gift";
            $field_values = ["img", "status","price"];
            $data_values = ["$url", "$okey", "$gprice"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Gift Add Successfully!!",
                    "message" => "Gift section!",
                    "action" => "list_gift.php",
                ];
            } 
		
	}elseif ($_POST["type"] == "add_language") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["cat_name"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/language/";
        $url = "images/language/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_language";
            $field_values = ["img", "status","title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Language Add Successfully!!",
                    "message" => "Language section!",
                    "action" => "list_language.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "add_religion") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["cat_name"]);

            $table = "tbl_religion";
            $field_values = [ "status","title"];
            $data_values = ["$okey", "$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Religion Add Successfully!!",
                    "message" => "Religion section!",
                    "action" => "list_religion.php",
                ];
            } 
        
    } elseif ($_POST["type"] == "add_relation") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["title"]);
		$subtitle = $dating->real_escape_string($_POST["subtitle"]);

            $table = "relation_goal";
            $field_values = [ "status","title","subtitle"];
            $data_values = ["$okey", "$title", "$subtitle"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Relation Goal Add Successfully!!",
                    "message" => "Relation Goal section!",
                    "action" => "list_goal.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "add_dating_type") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/datingtype/";
        $url = "images/datingtype/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "dating_type";
            $field_values = ["img", "status","title"];
            $data_values = ["$url", "$okey","$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "dating Type Add Successfully!!",
                    "message" => "dating Type section!",
                    "action" => "list_dating_type.php",
                ];
            } 
        
    } elseif ($_POST["type"] == "add_dating_brand") {
        $okey = $_POST["status"];
        $title = $dating->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/datingbrand/";
        $url = "images/datingbrand/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "dating_brand";
            $field_values = ["img", "status","title"];
            $data_values = ["$url", "$okey","$title"];

            $h = new Gomeet($dating);
            $check = trim($h->datinginsertdata($field_values, $data_values, $table));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "dating Brand Add Successfully!!",
                    "message" => "dating Brand section!",
                    "action" => "list_dating_brand.php",
                ];
            } 
        
    }  elseif ($_POST["type"] == "edit_city") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $title = $dating->real_escape_string($_POST["title"]);
        
            $table = "tbl_city";
            $field = ["status" => $okey, "title" => $title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "City Update Successfully!!",
                    "message" => "City section!",
                    "action" => "list_city.php",
                ];
            } 
        
    } elseif ($_POST["type"] == "edit_gift") {
	$okey = $_POST["status"];
    $id = $_POST["id"];
	$gprice = $_POST["gprice"];
     
	    $target_dir = dirname(dirname(__FILE__)) . "/images/gift/";
        $url = "images/gift/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            $file_extension = strtolower(end($temp));
       
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "tbl_gift";
                $field = ["status" => $okey, "img" => $url,"price"=>$gprice];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Gift Update Successfully!!",
                        "message" => "Gift section!",
                        "action" => "list_gift.php",
                    ];
                } 
            
        } else {
            $table = "tbl_gift";
            $field = ["status" => $okey,"price"=>$gprice];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Gift Update Successfully!!",
                    "message" => "Gift section!",
                    "action" => "list_gift.php",
                ];
            } 
        }
	
	}elseif ($_POST["type"] == "edit_interest") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$title = $dating->real_escape_string($_POST['cat_name']);
        $target_dir = dirname(dirname(__FILE__)) . "/images/interest/";
        $url = "images/interest/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
          
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "tbl_interest";
                $field = ["status" => $okey, "img" => $url,"title"=>$title];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Interest Update Successfully!!",
                        "message" => "Interest section!",
                        "action" => "list_interest.php",
                    ];
                } 
            
        } else {
            $table = "tbl_interest";
            $field = ["status" => $okey,"title"=>$title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Interest Update Successfully!!",
                    "message" => "Interest section!",
                    "action" => "list_interest.php",
                ];
            } 
        }
    } elseif ($_POST["type"] == "edit_language") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$title = $dating->real_escape_string($_POST['cat_name']);
        $target_dir = dirname(dirname(__FILE__)) . "/images/language/";
        $url = "images/language/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
           
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "tbl_language";
                $field = ["status" => $okey, "img" => $url,"title"=>$title];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Interest Update Successfully!!",
                        "message" => "Interest section!",
                        "action" => "list_language.php",
                    ];
                } 
            
        } else {
            $table = "tbl_language";
            $field = ["status" => $okey,"title"=>$title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Interest Update Successfully!!",
                    "message" => "Interest section!",
                    "action" => "list_language.php",
                ];
            } 
        }
    }elseif ($_POST["type"] == "edit_religion") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$title = $dating->real_escape_string($_POST['cat_name']);
      
            $table = "tbl_religion";
            $field = ["status" => $okey,"title"=>$title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Religion Update Successfully!!",
                    "message" => "Religion section!",
                    "action" => "list_religion.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "edit_relation") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$title = $dating->real_escape_string($_POST['title']);
		$subtitle = $dating->real_escape_string($_POST['subtitle']);
      
            $table = "relation_goal";
            $field = ["status" => $okey,"title"=>$title,"subtitle"=>$subtitle];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Relation Goal Update Successfully!!",
                    "message" => "Relation Goal section!",
                    "action" => "list_goal.php",
                ];
            } 
        
    }elseif ($_POST["type"] == "edit_dating_type") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$title = $dating->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/datingtype/";
        $url = "images/datingtype/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
          
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "dating_type";
                $field = ["status" => $okey, "img" => $url,"title"=>$title];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "dating Type Update Successfully!!",
                        "message" => "dating Type section!",
                        "action" => "list_dating_type.php",
                    ];
                } 
            
        } else {
            $table = "dating_type";
            $field = ["status" => $okey,"title"=>$title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "dating Type Update Successfully!!",
                    "message" => "dating Type section!",
                    "action" => "list_dating_type.php",
                ];
            } 
        }
    } elseif ($_POST["type"] == "edit_dating_brand") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$title = $dating->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/datingbrand/";
        $url = "images/datingbrand/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
          
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
                $table = "dating_brand";
                $field = ["status" => $okey, "img" => $url,"title"=>$title];
                $where = "where id=" . $id . "";
                $h = new Gomeet($dating);
                $check = trim($h->datingupdateData($field, $table, $where));

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "dating Brand Update Successfully!!",
                        "message" => "dating Brand section!",
                        "action" => "list_dating_brand.php",
                    ];
                } 
            
        } else {
            $table = "dating_brand";
            $field = ["status" => $okey,"title"=>$title];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "dating Brand Update Successfully!!",
                    "message" => "dating Brand section!",
                    "action" => "list_dating_brand.php",
                ];
            } 
        }
    } 	elseif ($_POST["type"] == "update_status") {
        $id = $_POST["id"];
        $status = $_POST["status"];
        $coll_type = $_POST["coll_type"];
        $page_name = $_POST["page_name"];
         if ($coll_type == "userstatus") {
            $table = "tbl_user";
            $field = "status=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData_single($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "User Status Change Successfully!!",
                    "message" => "User section!",
                    "action" => "userlist.php",
                ];
            } 
        }elseif ($coll_type == "verifystatus") {
			if($status == 0)
			{
             $table = "tbl_user";
            $field = ["is_verify" => $status,"identity_picture"=>NULL,"is_verify"=>0];
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData($field, $table, $where));
			}
			else 
			{
            $table = "tbl_user";
            $field = "is_verify=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData_single($field, $table, $where));
			}
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "User Verify Change Successfully!!",
                    "message" => "User section!",
                    "action" => "userlist.php",
                ];
            } 
        }  elseif ($coll_type == "dark_mode") {
		
            $table = "tbl_setting";
            $field = "show_dark=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new Gomeet($dating);
            $check = trim($h->datingupdateData_single($field, $table, $where));
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Dark Mode Status Change Successfully!!",
                    "message" => "Dark Mode section!",
                    "action" => $page_name,
                ];
            } 
	
        }
		

		else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Option Not There!!",
                "message" => "Error!!",
                "action" => "dashboard.php",
            ];
        }
    } else {
        $returnArr = ["ResponseCode" => "200", "Result" => "false", "title" => "Don't Try Extra Function!", "message" => "welcome admin!!", "action" => "dashboard.php"];
    }
} else {
    $returnArr = ["ResponseCode" => "200", "Result" => "false", "title" => "Don't Try Extra Function!", "message" => "welcome admin!!", "action" => "dashboard.php"];
}
echo json_encode($returnArr);
?>
