<?php
    require_once('db_connect.php');
    require_once('Tools.php');

    $offset = $_POST["offset"];
    $response = array();

    $query_survey = "SELECT * FROM survey ORDER BY date_created DESC LIMIT $offset, 12";
    $survey_result = mysqli_query($link, $query_survey);
    if ($survey_result)
    {
        $i = 0;
        while($survey = mysqli_fetch_array($survey_result))
        {
            $response[] = array();
            $id = $survey['id'];
            $name = $survey['name'];
            $desc = $survey['description'];
            $date_created = $survey['date_created'];

            $desc_final = "";
            if (strlen($desc) > 100)
            {
                $desc_final = substr($desc, 0, 60) . "...";
            }
            else
            {
                $desc_final = $desc;
            }
            $desc_final = trim($desc_final);

            $now = date("y-m-d h:i:s", time());
            $now = Tools::date_to_array($now);
            $date_created = Tools::date_to_array($date_created);
            $delta_time = Tools::count_delta_time($now, $date_created);

            array_push($response[$i], $id, $name, $desc_final, $delta_time);
            ++$i;
        }
        echo json_encode($response);
    }
    else
    {
        echo 'Failed to connect';
    }