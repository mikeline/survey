<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];

        require_once('db_connect.php');
        $basename = basename(__FILE__);

        $survey_id = $_GET['id'];

        $query = "SELECT survey.id, survey.name, survey.description, question.id AS question_id, question.type, question.label_of_the_question AS label, survey.option.text AS option_text FROM survey INNER JOIN question ON survey.id = question.survey_id LEFT JOIN survey.option ON question.id = survey.option.question_id WHERE survey.id = $survey_id";
        $query_result = mysqli_query($link, $query);
        $data = array();
        $data["questions"] = array();
        while ($row = mysqli_fetch_array($query_result))
        {
            if (!isset($data["survey_id"]) && !isset($data["survey_name"]) && !isset($data["survey_desc"]))
            {
                $data["survey_id"] = $row["id"];
                $data["survey_name"] = $row["name"];
                $data["survey_desc"] = $row["description"];
            }

            if (!isset($data["questions"][$row["question_id"]]))
            {
                if ($row["type"] != "t" || $row["type"] != 3)
                {
                    $data["questions"][$row["question_id"]] = array(
                        "type" => $row["type"],
                        "label" => $row["label"],
                        "options" => array($row["option_text"])
                    );
                }
                else
                {
                    $data["questions"][$row["question_id"]] = array(
                        "type" => $row["type"],
                        "label" => $row["label"]
                    );
                }
            }
            else
            {
                $data["questions"][$row["question_id"]]["options"][] = $row["option_text"];
            }

        }
?>
<!DOCTYPE html>
<head>
    <title>Survey | <?php echo $data["survey_name"]?></title>
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="styles/mdb.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
        <a class="navbar-back" href="index.php">Back</a>
    </nav>
    <div class="card container" style="margin-top: 20px; width: 50%;">
        <div class="card-header" style="border: 1px solid lightgray; margin-top: 10px;">
            <h1><?php echo $data["survey_name"]?></h1>
            <p><?php echo $data["survey_desc"]?></p>
        </div>
        <div class="card-body" style="width: 70%;">
            <form class="form-group" action="add_completed_survey.php" method="post">
            <?php
            foreach($data["questions"] as $question_id => $question)
            {
                $type = $question["type"];
                $label = $question["label"];
                if (isset($question["options"]))
                {
                    $options = $question["options"];
                }

            ?>
                <div>
                    <h4>
                        <?php
                            echo $label;
                        ?>
                    </h4>
                    <?php
                    switch ($type)
                    {
                        case "r":
                            $j = 1;
                            foreach ($options as $option)
                            {
                                ?>
                                <div class="custom-control custom-radio">
                                    <input id="radioOption<?php echo $question_id; ?>_<?php echo $j; ?>" class="custom-control-input" style="margin-left: 20px;" value="<?php echo $option; ?>" name="question_option_radio<?php echo $question_id; ?>" type="radio">
                                    <label style="margin-left: 10px;" class="custom-control-label" for="radioOption<?php echo $question_id; ?>_<?php echo $j; ?>"><?php echo $option; ?></label>
                                </div> <br>
                            <?php ++$j; }
                            break;
                        case "c":
                            $j = 1;
                            foreach ($options as $option)
                            {
                                ?>
                                <div class="custom-control custom-checkbox">
                                    <input id="checkboxOption<?php echo $question_id; ?>_<?php echo $j; ?>" class="custom-control-input" style="margin-left: 20px;" value="<?php echo $option; ?>" name="question_option_checkbox<?php echo $question_id; ?>[]" type="checkbox">
                                    <label style="margin-left: 10px;" class="custom-control-label" for="checkboxOption<?php echo $question_id; ?>_<?php echo $j; ?>"><?php echo $option; ?></label>
                                </div> <br>
                            <?php ++$j; }
                            break;
                        case "t":
                            ?>
                            <div class="md-form"><textarea class="md-textarea form-control" style="margin-left: 20px;" name="question_answer<?php echo $question_id; ?>" cols="30" rows="10" placeholder="Type here"></textarea></div>
                            <?php
                            break;
                    }
                    ?>
                </div>
            <?php } ?>
                <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>">
                <button style="margin-top: 20px;" class="btn btn-success" type="submit" name="submit_button" id="submitButton">Send</button>
            </form>
        </div>
    </div>
</body>
<?php }
else
{
    echo '<script>window.location.href = "index.php";</script>';
}
?>