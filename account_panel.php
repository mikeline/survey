<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        $id_login = $_SESSION['id_login'];
        require_once('db_connect.php');

        $query = "SELECT survey.user.name AS first_name, survey.user.last_name AS last_name, survey.survey.id AS survey_id, survey.survey.name AS survey_name, survey.survey.description AS survey_description, survey.survey.date_created AS survey_date_created FROM account INNER JOIN survey.user ON account.user_id = survey.user.id INNER JOIN survey.survey ON survey.survey.creator_id = account.id WHERE account.id = $id_login";
        $query_result = mysqli_query($link, $query);

        $user_data = array();
        $survey_data = array();

        $i = 0;
        while ($row = mysqli_fetch_array($query_result))
        {
            if (!isset($user_data["first_name"])&&!isset($user_data["last_name"]))
            {
                $user_data["first_name"] = $row["first_name"];
                $user_data["last_name"] = $row["last_name"];
            }
            $survey_data[] = array();
            $survey_data[$i]["survey_id"] = $row["survey_id"];
            $survey_data[$i]["survey_name"] = $row["survey_name"];
            $survey_data[$i]["survey_description"] = $row["survey_description"];
            $survey_data[$i]["survey_date_created"] = $row["survey_date_created"];
            ++$i;
        }
        ?>
<!DOCTYPE html>
<html>
<head>
    <title>Survey | My account</title>
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="styles/mdb.min.css">
    <link type="text/css" rel="stylesheet" href="styles/account_panel_styles.css"/>
    <script>
        function deleteSurvey(survey_id) {
            if (confirm("Are you sure, you want to delete the survey?"))
            {
                let xhr = new XMLHttpRequest();
                let params = "survey_id=" + survey_id;
                xhr.open("POST", 'delete_survey.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        window.location.reload();
                    }
                }

                // Send the request
                xhr.send(params);
            }
        }
    </script>
</head>
<body style="box-sizing: border-box;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
        <a class="navbar-back" href="index.php">Back</a>
    </nav>
    <div class="card container" style="margin-top: 20px;">
        <div class="card-header" style="border: 1px solid lightgray; margin-top: 10px;">
            <h3>Account info</h3>
            <p>Username: <span><?php echo $login; ?></span></p>
            <p>Name: <span><?php echo $user_data["first_name"]; ?></span></p>
            <p>Last name: <span><?php echo $user_data["last_name"]; ?></span></p>
        </div>
        <div class="card-body">
            <div>
                <ul id="surveyList" class="list-group">
                    <div style="text-align: center; margin-bottom: 10px;">
                        <h4>Your surveys</h4>
                    </div>
                    <?php
                    for ($j = 0; $j < count($survey_data); $j++)
                    {
                        $survey_id = $survey_data[$j]["survey_id"];
                        $desc_final = "";
                        if (strlen($survey_data[$j]["survey_description"]) > 50)
                        {
                            $desc_final = substr($survey_data[$j]["survey_description"], 0, 50) . "...";
                        }
                        else
                        {
                            $desc_final = $survey_data[$j]["survey_description"];
                        }
                        $desc_final = trim($desc_final);
                    ?>
                    <div class="item-container">
                        <li class="list-group-item survey-item" style="border-radius: 0;">
                            <span style="font-size: 1.2em; color: #1d2124;"><?php echo $survey_data[$j]["survey_name"]; ?></span><br>
                            <span style="color: #434343;"><?php echo $desc_final; ?></span><br>
                            <span style="color: lightgray;"><?php echo $survey_data[$j]["survey_date_created"]; ?></span>
                            <a href="javascript:deleteSurvey(<?php echo $survey_id; ?>)" class="delete"></a>
                        </li>
                        <?php
                        if ($j == count($survey_data) - 1)
                        {?>
                        <a href="answers.php?survey_id=<?php echo $survey_id; ?>&title=<?php echo $survey_data[$j]["survey_name"]; ?>&desc=<?php echo $survey_data[$j]["survey_description"]; ?>">
                            <li class="list-group-item show-answer" style="border-radius: 0; border-bottom: 1px solid rgba(0,43,255,0.7);">
                                Show answers
                            </li>
                        </a>
                        <?php }
                        else
                            { ?>
                        <a href="answers.php?survey_id=<?php echo $survey_id; ?>&title=<?php echo $survey_data[$j]["survey_name"]; ?>&desc=<?php echo $survey_data[$j]["survey_description"]; ?>">
                            <li class="list-group-item show-answer" style="border-radius: 0;">
                                Show answers
                            </li>
                        </a>
                         <?php } ?>
                    </div>
                    <?php }?>
                </ul>

            </div>
        </div>
    </div>
</body>
</html>
<?php } ?>