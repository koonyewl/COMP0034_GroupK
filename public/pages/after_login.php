<?php include($_SERVER['DOCUMENT_ROOT'] . "/COMP0034_GroupK/private/initialize.php"); ?>

<?php require_once('../../private/shared/pages_header.php');?>

<?php


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $login_email = test_input($_POST['email']);
    $login_password = test_input($_POST['pw']);
    $table_name = NULL;


    if (!check_email($db,$login_email)) {
        $_SESSION['error'] = "Email does not exist";
        redirect_to(url_for('/pages/login.php'));
    }elseif (check_email_admin($db,$login_email)) {
        $table_name = "administrator";
    }elseif (check_email_student($db,$login_email)) {
        $table_name = "student";
    }elseif (check_email_parent($db,$login_email)) {
        $table_name = "parent";
    }

    if (!is_null($table_name)) {
        echo $table_name . " is the table name<br>";
        $data = get_data($db,$login_email,$table_name,"email_address");
        if (password_verify($login_password,$data['password'])) {
            echo "log in successful!<br>";
            $_SESSION['credential'] = $login_email;
            $_SESSION['accType'] = $table_name;
            to_myAccount($table_name);
        }else {
            $_SESSION['error'] = "Your Password is wrong";
            redirect_to(url_for('/pages/login.php'));
        }
    }

}



?>

<?php require_once('../../private/shared/pages_footer.php');?>