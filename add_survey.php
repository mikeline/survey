<?php
    session_start();
    if(isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $id_login = $_SESSION['id_login'];
        require_once('db_connect.php');
        $basename = basename(__FILE__);

        $survey_name = $_POST['survey_name'];
        $survey_desc = $_POST['survey_desc'];


        $survey_query_insert = "INSERT INTO survey(id, survey.name, creator_id, date_created, description) VALUES (NULL, '$survey_name', $id_login, now(), '$survey_desc')";
        $survey_query_insert = mysqli_query($link, $survey_query_insert);


        $survey_query_select = "SELECT id FROM survey WHERE survey.name LIKE '$survey_name'";
        $survey_query_select = mysqli_query($link, $survey_query_select);
        $survey_id = mysqli_fetch_array($survey_query_select);
        $survey_id = $survey_id['id'];


        for ($i = 1; $i <= $_POST['question_count']; $i++) {
            $question_type = $_POST["question_type{$i}"];
            $question_body = $_POST["question_text{$i}"];
            if ($question_type < 3) {
                $question_options = $_POST["question_option{$i}"];
            }


            $question_query_insert = "INSERT INTO question(id, label_of_the_question, question.type, survey_id) VALUES (NULL, '$question_body', $question_type, $survey_id)";
            $question_query_insert = mysqli_query($link, $question_query_insert);

            $question_query_select = "SELECT id FROM question WHERE label_of_the_question LIKE '$question_body'";
            $question_query_select = mysqli_query($link, $question_query_select);
            $question_id = mysqli_fetch_array($question_query_select);
            $question_id = $question_id['id'];

            if ($question_options) {
                foreach ($question_options as $option) {
                    $option_query_insert = "INSERT INTO option(id, text, question_id) VALUES (NULL, '$option', $question_id)";
                    $option_query_insert = mysqli_query($link, $option_query_insert);
                }
            }

            $question_options = [];
        }
    }


