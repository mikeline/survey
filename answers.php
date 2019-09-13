<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        require_once('db_connect.php');

        $survey_id = $_GET["survey_id"];
        $title = $_GET["title"];
        $desc = $_GET["desc"];
        $indices = array();

        $query = "SELECT DISTINCT completed_survey.id FROM completed_survey WHERE completed_survey.id_survey = $survey_id";
        $query_result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_array($query_result))
        {
            $indices[] = +$row["id"];
        }
?>
<!Doctype html>
<head>
    <title>Survey | View answers</title>
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="styles/mdb.min.css">
    <link type="text/css" rel="stylesheet" href="styles/answers.css"/>
    <script>
        let indices = {};

        function initIndices(result) {
            indices = result;
        }

        function subtractOne()
        {
            let answerID = document.getElementsByName("answer_id")[0];
            if (answerID.value > 1)
            {
                let value = +answerID.value;
                answerID.value = value - 1;
            }
        }

        function addOne()
        {
            let maxID = document.getElementById("maxID");
            maxID = +maxID.innerText;
            let answerID = document.getElementsByName("answer_id")[0];
            if (answerID.value < maxID)
            {
                let value = +answerID.value;
                answerID.value = value + 1;
            }
        }

        function loadAnswer() {
            let answerID = document.getElementsByName("answer_id")[0];
            answerID = +answerID.value;
            let actualAnswerID = indices[answerID - 1];

            let xhr = new XMLHttpRequest();
            let params = "answer_id=" + actualAnswerID;
            xhr.open("POST", 'load_answer.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let data = JSON.parse(xhr.response);
                    console.log(data);
                    renderAnswer(data);
                }
            }

            // Send the request
            xhr.send(params);
        }

        function renderAnswer(data) {
            let answerSector = document.getElementById("answerSector");
            answerSector.innerHTML = "";
            let username = data[0];
            delete data[0];
            Object.keys(data).forEach(function(key) {
                    let label = key;
                    let type = data[key][0];
                    let answerText = data[key][1];
                    switch (type) {
                        case "r":
                            var questionArea = document.createElement('div');
                            questionArea.innerHTML = '<h4 style="margin-top: 10px; margin-bottom: 10px;">' + label + '</h4>' +
                                '<span style="margin-left: 20px; font-size: 1.1em; border: 1px solid gray; background-color: rgb(220,220,220, 0.3); border-radius: 5px; padding: 5px;">' + answerText + '</span>';
                            answerSector.appendChild(questionArea);
                            break;
                        case "c":
                            var questionArea = document.createElement('div');
                            questionArea.innerHTML = '<h4 style="margin-top: 10px; margin-bottom: 10px;">' + label + '</h4>';
                            answerText.forEach(function (element) {
                                questionArea.innerHTML += '<span style="margin-left: 20px; font-size: 1.1em; border: 1px solid gray; background-color: rgb(220,220,220, 0.3); border-radius: 5px; padding: 5px;">' + element + '</span>';
                            })
                            answerSector.appendChild(questionArea);
                            break;
                        case "t":
                            var questionArea = document.createElement('div');
                            questionArea.innerHTML = '<h4 style="margin-top: 10px; margin-bottom: 10px;">' + label + '</h4>' +
                                '<span style="margin-left: 20px; font-size: 1.1em; border: 1px solid gray; background-color: rgb(220,220,220, 0.3); border-radius: 5px; padding: 5px;">' + answerText + '</span>';
                            answerSector.appendChild(questionArea);
                            break;
                    }
                }
            );
            let submitter = document.createElement('div');
            submitter.innerHTML = '<p style="margin-top: 20px;">By <span style="color: dodgerblue; cursor: default;">' + username + '</span></p>'
            answerSector.appendChild(submitter);
            answerSector.style.display = "block";
        }
    </script>
</head>
<body onload="initIndices(<?php echo json_encode($indices); ?>)">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
        <a class="navbar-back" href="account_panel.php">Back to account panel</a>
    </nav>
    <div class="container">
        <div class="card" style="margin-top: 20px; padding: 20px;">
            <div class="survey-info-header">
                <h3><?php echo $title; ?></h3><br>
                <p><?php echo $desc; ?></p><br>
            </div>
            <hr>
            <div class="answer-toggle">
                <i onclick="subtractOne()" class="arrow-left"></i>
                <input name="answer_id" type="number" value="1">
                <span>from <span id="maxID"><?php echo count($indices); ?></span></span>
                <i onclick="addOne()" class="arrow-right"></i>
                <button style="margin-left: 15px;" class="btn btn-info" type="button" onclick="loadAnswer()" style="display: inline-block; margin-left: 10px;">Show</button>
            </div>
        </div>
        <div class="card" id="answerSector" style="margin-top: 10px; padding: 20px; display: none;">

        </div>
    </div>
</body>
<?php } ?>
