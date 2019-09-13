<?php
    session_start();
    if(isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $id_login = $_SESSION['id_login'];
        require_once('db_connect.php');

        $answer_id = $_POST["answer_id"];

        $data = array();

        $query_user = "SELECT account.login FROM completed_survey INNER JOIN account ON account.id = completed_survey.account_id WHERE completed_survey.id = $answer_id";
        $query_user_result = mysqli_query($link, $query_user);
        while ($row = mysqli_fetch_array($query_user_result)) {
            $data[] = $row["login"];
        }

        $query = "SELECT question.label_of_the_question AS label, question.type, completed_answer.text FROM completed_survey INNER JOIN completed_answer ON completed_answer.id_completed_survey = completed_survey.id INNER JOIN question ON question.id = completed_answer.id_question WHERE completed_survey.id = $answer_id";
        $query_result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_array($query_result)) {
            if ($row["type"] === 'c') {
                if (!isset($data[$row["label"]])) {
                    $data[$row["label"]] = array($row["type"], array($row["text"]));
                } else {
                    $data[$row["label"]][1][] = $row["text"];
                }
            } else {
                $data[$row["label"]] = array($row["type"], $row["text"]);
            }
        }

        echo json_encode($data);
    }