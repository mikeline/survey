<?php
    session_start();
    if(isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $id_login = $_SESSION['id_login'];
        require_once('db_connect.php');
        $basename = basename(__FILE__);

        $survey_id = $_POST["survey_id"];

        $query_completed_survey = "INSERT INTO completed_survey(id, id_survey, account_id, date_submitted) VALUES (NULL, $survey_id, $id_login, now())";
        $query_completed_survey = mysqli_query($link, $query_completed_survey);

        $completed_survey_id = mysqli_insert_id($link);

        $res = "";

        foreach ($_POST as $key => $value) {
            if (strpos($key, "question_option_radio") !== false) {
                $question_id = substr($key, 21, strlen($key));
                $option = $value;
                $query_completed_answer = "INSERT INTO completed_answer(id, id_question, id_completed_survey, text) VALUES (NULL, $question_id, $completed_survey_id, '$option')";
                $query_completed_answer = mysqli_query($link, $query_completed_answer);
                $count_attempts = 0;
                while (!$query_completed_answer) {
                    $query_completed_answer = mysqli_query($link, $query_completed_answer);
                    if ($count_attempts > 5) {
                        break;
                    }
                    $count_attempts++;
                }
            } else if (strpos($key, "question_option_checkbox") !== false) {
                $question_id = substr($key, 24, strlen($key));
                foreach ($value as $option) {
                    $query_completed_answer = "INSERT INTO completed_answer(id, id_question, id_completed_survey, text) VALUES (NULL, $question_id, $completed_survey_id, '$option')";
                    $query_completed_answer = mysqli_query($link, $query_completed_answer);
                    $count_attempts = 0;
                    while (!$query_completed_answer) {
                        $query_completed_answer = mysqli_query($link, $query_completed_answer);
                        if ($count_attempts > 5) {
                            break;
                        }
                        $count_attempts++;
                    }
                }
            } else if (strpos($key, "question_answer") !== false) {
                $question_id = substr($key, 15, strlen($key));
                $option = $value;
                $query_completed_answer = "INSERT INTO completed_answer(id, id_question, id_completed_survey, text) VALUES (NULL, $question_id, $completed_survey_id, '$option')";
                $query_completed_answer = mysqli_query($link, $query_completed_answer);
                $count_attempts = 0;
                while (!$query_completed_answer) {
                    $query_completed_answer = mysqli_query($link, $query_completed_answer);
                    if ($count_attempts > 5) {
                        break;
                    }
                    $count_attempts++;
                }
            }
        }
        header("refresh:5; url=index.php");
    }