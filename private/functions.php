<?php
//Function.php
//This is where all the function that will be used more than once get declared.
//To reduce the redundancy of the code.

//Define variables to collect from sign up form. Initial value is blank.
$email = $fname = $lname = $user_pw = $pw2 =$phone = $school = $accType = $reference = "";
$email_empty = $fname_empty = $lname_empty = $pw_empty = $pw2_empty = $school_empty = $accType_empty = $reference_empty = "";

//Create a function to test user input, encode special HTMl characters, remove unnecessary space, tab, backsplashes etc.
function test_input($user_input) {
    $user_input = trim($user_input);
    $user_input = stripslashes($user_input);
    $user_input = htmlspecialchars($user_input);
    return $user_input;
};

//Create an error 404 function. This function mostly is used to prevent user to access to the page that the don't have access to.
function error_404($error_message = "") {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found!!!");
    exit("<h1>404 Not Found</h1>" . $error_message);
}

//Create an error 500 fuction. This function is used to catch an error that related to server problem or the source code problem, mostly is for SQL contact query.
function error_500($error_message = "") {
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Error!!!");
    exit("<h1>500 Internal Error!!!</h1>" . $error_message);
}

// Create a URL to a page base on the string given.
function url_for($script_path) {
    // add the leading '/' if not present
    if($script_path[0] != '/'){
        $script_path = "/" . $script_path;
    }
    return WWW_ROOT . $script_path;
}

//Redirect to a page with the URL given
function redirect_to($url) {
    header('Location: ' . $url);
    exit;
}

//Submit a query to database, return true if success and false if fail
function submit_query($db, $query) {
    if (mysqli_query($db, $query)) {
//        echo "true";
        return true;
    } else {
//        echo "false";
        return false;
    }
}


function get_all($db, $table_name) { //get all data from a table
    $query = "SELECT * FROM $table_name";
    $data = mysqli_query($db, $query);
    return $data;
}

//Get specific data from the database given the table name and column name
function get_specific_data ($db, $specific_field, $user_input, $table_name, $field_name) {
    $user_input = test_input($user_input);
    $query = "SELECT $specific_field FROM $table_name WHERE $field_name = '$user_input'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
    }
    elseif (mysqli_num_rows($result) > 1){
        $data = convert_sql_result_to_array($result);
    } else {
        $data = false;
    }
    return $data;
}


//Get all data from the database given the table name and column name
function get_data($db, $user_input, $table_name, $field_name) {
    $data = get_specific_data($db, '*', $user_input, $table_name, $field_name);
    return $data;
}

//Return full name for a person
function get_person_name($db, $user_input, $table_name, $field_name) {
    $data = get_data($db, $user_input, $table_name, $field_name);
    $full_name = $data['first_name'] . " " . $data['last_name'];
    return $full_name;
}

function to_myAccount ($accType) {
    //Get a parameter of 'account type' - 'table name'
    //redirect user to the corresponding myAccount page.
    switch ($accType) {
        case "administrator":
            redirect_to(url_for('/pages/my_account/myaccount_teacher.php'));
            break;
        case "student":
            redirect_to(url_for('/pages/my_account/myaccount_student.php'));
            break;
        case "parent":
            redirect_to(url_for('/pages/my_account/myaccount_parent.php'));
            break;
        default:
            break;
    }
}

//Check if the email given is found in the student table
function check_email_student ($db,$user_input) {
    $existence = false;
    $result_student_table = get_all($db, 'student');

    while (!$existence && $result = mysqli_fetch_assoc($result_student_table)) {
        if ($user_input == $result['email_address']) {
            $existence = true;
        }
    }
    return $existence;
}

//Check if the email given is found in the parent table
function check_email_parent ($db,$user_input) {
    $existence = false;
    $result_parent_table = get_all($db, 'parent');

    while (!$existence && $result = mysqli_fetch_assoc($result_parent_table)) {
        if ($user_input == $result['email_address']) {
            $existence = true;
        }
    }
    return $existence;
}

//Check if the email given is found in the administrator table
function check_email_admin ($db,$user_input) {
    $existence = false;
    $result_admin_table = get_all($db, 'administrator');

    while (!$existence && $result = mysqli_fetch_assoc($result_admin_table)) {
        if ($user_input == $result['email_address']) {
            $existence = true;
        }
    }
    return $existence;
}

//Check if the email given is found in any of the 3 user tables
function check_email($db, $user_input) {
    $existence = false;
    if (!$existence) {
        $existence = check_email_student($db,$user_input);
    }
    if (!$existence) {
        $existence = check_email_admin($db,$user_input);
    }
    if (!$existence) {
        $existence = check_email_parent($db,$user_input);
    }
    return $existence;
}

//Check if the student is available to link to a parent (given the student email)
function check_student_avail($db, $user_email) {
    $query = "SELECT * FROM student_parent JOIN student s on student_parent.student_id = s.student_id WHERE s.email_address = '$user_email'";

    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        return false; //Student has already registered to a parent account!
    }else {
        return true; //Student is available to register
    }
}

//Return all id related to a parent account, including student_id, admin_id, school_id
function get_parent ($db, $user_input, $input_field) {
    $query = "SELECT * FROM parent JOIN student_parent USING (parent_id) JOIN admin_student USING (student_id) JOIN school_admin USING (admin_id) " .
        "WHERE parent.$input_field = '$user_input'";
    $result = mysqli_query($db, $query);
    return convert_sql_result_to_array($result);
}

//Return id related to a student account, including admin_id, school_id
//The parent is not included as there will be a case that the student account is not linked to any parent account.
function get_student ($db, $user_input, $input_field) {
    $query = "SELECT * FROM student JOIN admin_student USING (student_id) JOIN school_admin USING (admin_id) " .
        "WHERE student.$input_field = '$user_input'";
    $result = mysqli_query($db, $query);
    return convert_sql_result_to_array($result);
}

//Return id related to a admin account, including student_id, school_id
//The parent is not included as there will be a case that the student account is not linked to any parent account.
function get_admin ($db, $user_input, $input_field) {
    $query = "SELECT * FROM administrator JOIN admin_student USING (admin_id) JOIN school_admin USING (admin_id) " .
        "WHERE administrator.$input_field = '$user_input'";
    $result = mysqli_query($db, $query);

    return convert_sql_result_to_array($result);
}


//Find school id given the admin information
function get_admin_school ($db, $user_input, $input_field) {
    $query = "SELECT * FROM administrator JOIN school_admin USING (admin_id) " .
        "WHERE administrator.$input_field = '$user_input'";
    $result = mysqli_query($db, $query);

    return convert_sql_result_to_array($result);
}

//Find the list of student related to 1 admin
function get_admin_student ($db, $user_input, $input_field) {
    $query = "SELECT * FROM administrator JOIN admin_student USING (admin_id) " .
        "WHERE administrator.$input_field = '$user_input'";
    $result = mysqli_query($db, $query);

    return convert_sql_result_to_array($result);
}


//Convert the sql result to a multidimensional array as it is easier to use.
function convert_sql_result_to_array ($sql_result) {
    if ($sql_result){
        $data = [];
        while($row = mysqli_fetch_assoc($sql_result)) {
            array_push($data,$row);
        }
    } else {
        $data = false;
    }
    return $data;
}


//Return all the available menus
function get_list_of_menus ($db) {
    $query = "SELECT DISTINCT * FROM list_of_menus WHERE visibility = '1'";
    $result = mysqli_query($db, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        array_push($data,$row);
    }
    return $data;
}


//Get the information of 1 menu (name, desc, availability)
function get_menu ($db, $menu_id) {
    $query = "SELECT * FROM display_menu JOIN item USING (item_id) WHERE menu_id = '$menu_id'";
    $result = mysqli_query($db, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        array_push($data,$row);
    }
    return $data;
}


//Find the school address given the email address, mostly used in order page to find the delivery address
function find_school_address ($db, $user_email) {
    if (check_email_student($db, $user_email)) {
        $id_list = get_student($db, $user_email, 'email_address');
    }elseif (check_email_admin($db, $user_email)) {
        $id_list = get_admin_school($db, $user_email, 'email_address');
    }
    if (isset($id_list)) {
        $school_id = $id_list[0]['school_id'];

        $query = "SELECT * FROM school WHERE school_id='$school_id'";
        $result = mysqli_fetch_array(mysqli_query($db, $query));
        $address = $result['school_name'] . " at " . $result['school_address'];
        return $address;
    }else {
        return false;
    }

}


//Link the user id to an order id.
function save_order_id ($db, $order_id, $user_id_field, $user_id) {
    if ($user_id_field == 'admin_id') {
        $query = "INSERT INTO admin_order (admin_id, order_id) VALUES ('$user_id', '$order_id')";
        return submit_query($db,$query);
    }elseif ($user_id_field == 'student_id') {
        $query = "INSERT INTO student_order (student_id, order_id) VALUES ('$user_id', '$order_id')";
        return submit_query($db,$query);
    }else {
        return false;
    }
}


//Fidn the order details
function get_order_details ($db, $user_id, $id_field, $table_name, $delivery_date) {
    if($delivery_date == NULL) {
        $query = "SELECT * FROM $table_name JOIN order_detail USING (order_id) " .
            "WHERE $id_field = '$user_id' ORDER BY order_detail.delivery_date";
    }else {
        $query = "SELECT * FROM $table_name JOIN order_detail USING (order_id) " .
            "WHERE $id_field = '$user_id' AND order_detail.delivery_date = '$delivery_date'";
    }

    $result = mysqli_query($db, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        array_push($data,$row);
    }
    return $data;
}


//Find all the order of an admin
function get_order_of_admin ($db, $user_id) {
    $data = get_order_details($db, $user_id, 'admin_id', 'admin_order', NULL);
    return $data;
}


//Find all the order of a student
function get_order_of_student ($db, $user_id) {
    $data = get_order_details($db, $user_id, 'student_id', 'student_order', NULL);
    return $data;
}


//Find the list of items in an order.
function get_order_item ($db, $order_id) {
    $query = "SELECT * FROM order_item JOIN item USING (item_id) " .
        "WHERE order_item.order_id = '$order_id'";
    $result = mysqli_query($db, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        array_push($data,$row);
    }
    return $data;
}


//Generate a random string as registration code for student to link to parent
//As eventually the random string can be repeated, the student id is added in front of the string to make sure the code is unique
function generate_random_string($length, $pre_string = 0) {
    $pre_string_length = strlen((string)$pre_string); //check the number of digits of the student_id
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';  //a set of characters that has all number, lowercase letters, uppercase letters.
    $characters_length = strlen($characters); //Get the length of characters
    $random_string = '';
    $random_string .= $pre_string; //Add the student id as the first digit
    for ($i = $pre_string_length - 1; $i < $length - 1; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }
    return $random_string;
}
function generate_registration_code($db, $student_id) {
    $registration_code = generate_random_string("15", $student_id);
    $query = "UPDATE student SET registration_code = '$registration_code' WHERE student_id = '$student_id'";
    return submit_query($db, $query);
}


//Simply get all data related to the user give the account, regardless the user account type.
function get_data_by_email($db,$user_email) {
    if (check_email_student($db, $user_email)) {
        $data = get_data($db, $user_email,'student', 'email_address');
    }elseif (check_email_admin($db, $user_email)) {
        $data = get_data($db, $user_email,'administrator', 'email_address');
    }elseif (check_email_parent($db, $user_email)) {
        $data = get_data($db, $user_email,'parent', 'email_address');
    }else {
        $data = false;
    }
    return $data;
}


//Check if the student registration_code is corrected
function check_student_ref_code ($db, $student_email, $ref_code) {
    $code_from_db = get_specific_data($db, 'registration_code',$student_email,'student','email_address');
    if ($code_from_db) {
        if ($ref_code == $code_from_db['registration_code']) {
            return true; //code is correct
        }else {
            return false; //code is incorrect
        }
    }else {
        return false; // can't find the code
    }
}


//Check the school password is correct or not
function check_school_password($db, $school_id, $school_password) {
    $password_from_db = get_specific_data($db, 'school_password', $school_id, 'school', 'school_id');
    if ($password_from_db) {
        if ($password_from_db['school_password'] == $school_password) {
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }
}

//Check if the student is verified or not
function check_student_verification_status ($db, $user_email) {
    $data = get_data_by_email($db,$user_email);
    if ($data) {
        if ($data["status"] == 1) {
            return true;
        }elseif ($data["status"] == 0) {
            return false;
        }
    }else {
        return false;
    }
}

//Check the staff log in information
function check_staff_credential ($db, $staff_username, $staff_password) {
    $result = get_all($db, "staff_credential");
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["username"] == $staff_username && $row["password"] == $staff_password) {
            return true;
            break;
        }
    }
    return false;

}

?>

