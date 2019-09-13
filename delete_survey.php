<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        require_once('db_connect.php');

        $survey_id = $_POST["survey_id"];

        $query = "DELETE FROM survey.survey WHERE id = $survey_id";
        $query_result = mysqli_query($link, $query);
    }