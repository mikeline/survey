<?php
    require_once('db_connect.php');
    $login = $_POST['username'];
    $password = $_POST['psw'];
    if(isset($login) && isset($password))
    {
        $query = "SELECT * FROM account WHERE login = '$login' AND password = '$password'";
        $send_query = mysqli_query($link, $query);
        $user_array = mysqli_fetch_array($send_query);
        $id_login = $user_array['id'];
        $login = $user_array['login'];
        $is_allowed_to_register = $user_array['is_allowed_to_register'];
        $count = mysqli_num_rows($send_query);
        if ($count > 0)
        {
            session_start();
            $_SESSION['id_login'] = $id_login;
            $_SESSION['login'] = $login;
            $_SESSION['is_allowed_to_register'] = $is_allowed_to_register;
            header( "refresh:1;url = index.php" );
        }
        else
        {
            echo "<script type='text/javascript'>alert('Неправильное имя пользователя или пароль'); window.location.href = 'index.php'</script>";

        }
    }
?>