<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        $is_allowed_to_register = $_SESSION['is_allowed_to_register'];

        require_once('db_connect.php');

        $username = $_POST["user_name"];
        $email = $_POST["user_email"];
        $psw = $_POST["psw"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $birth_date = $_POST["birth_date"];
        $occupation = $_POST["occupation"];
        $is_new_user_allowed_to_register = $_POST["is_allowed_to_register"];

        $query_user = "INSERT INTO survey.user(id, survey.user.name, last_name, birth_date, email, occupation) VALUES (NULL, '$first_name', '$last_name', '$birth_date', '$email', '$occupation')";
        $query_user_result = mysqli_query($link, $query_user);

        $query_user_select = "SELECT id FROM survey.user WHERE email = '$email'";
        $query_user_select_result = mysqli_query($link, $query_user_select);
        $query_user_select_result = mysqli_fetch_array($query_user_select_result);

        $user_id = $query_user_select_result["id"];

        $query_account = "INSERT INTO account(id, user_id, login, password, is_allowed_to_register) VALUES (NULL, $user_id, '$username', '$psw', $is_new_user_allowed_to_register)";
        $query_account_result = mysqli_query($link, $query_account);

        echo '<script>window.location.href = "index.php"</script>';
    }
