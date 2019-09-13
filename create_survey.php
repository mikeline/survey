<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        require_once('db_connect.php');
        $basename = basename(__FILE__);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Survey | Create survey</title>
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="styles/mdb.min.css">
    <link type="text/css" rel="stylesheet" href="styles/create_survey_styles.css">
    <script>

        let count = 1;

        function createQuestionTypeSelect() {
            let myID = ++count;
            let concreteSector = document.getElementById("concreteQuestionSector" + myID);
			let addButton = document.getElementById("buttonAddNewQuestion");
			if(addButton) {
                concreteSector.removeChild(addButton);
			}
            concreteSector.innerHTML += '<hr>' +
                '<h4 class="question-number">Question №' + myID + '</h4>' +
                '<label class="my-1 mr-2" for="questionTypeSelect" id="questionTypeCovering"> Choose question type </label>\n' +
                '<select id="questionTypeSelect" class="custom-select my-1 mr-sm-2" name="questionType" size="3">\n' +
                '    <option value="1" selected>Multiple choice answers</option>\n' +
                '    <option value="2">Single choice answers</option>\n' +
                '    <option value="3">Text answers</option>\n' +
                '</select>\n' +
                '<button style="margin-top: 10px;" class="btn btn-primary" type="button" id="questionTypeApplyButton" onclick="createQuestionContent()">Apply</button>       '

        }
        function createQuestionContent() {
            let concreteSector = document.getElementById("concreteQuestionSector" + count);
			let questionType = Number(document.getElementsByName("questionType")[0].value);
            concreteSector.removeChild(document.getElementById("questionTypeCovering"));
            concreteSector.removeChild(document.getElementById("questionTypeSelect"));
            concreteSector.removeChild(document.getElementById("questionTypeApplyButton"));

			
            switch (questionType) {
                case 1:

                    concreteSector.innerHTML += '<div style="margin-bottom: 10px;"><span style="color: gray; margin-bottom: 10px;">Multiple choice answers</span></div><form class="form-group" name="' + 'question' + count + '">\n' +
                                    '<input name="question_type' + count + '" type="hidden" value="1">\n' +
                        '            <label class="my-1 mr-2" for="questionText">Question body: </label>\n' +
                        '            <div class="md-form"><textarea class="md-textarea form-control" name="question_text' + count + '" id="questionText" cols="20" rows="2" placeholder="Enter question text"></textarea></div>\n' +
                        '            <label class="my-1 mr-2">Option: ' +
                        '            <div class="md-form"><textarea class="md-textarea form-control" name="question_option' + count + '" cols="20" rows="2" placeholder="Enter question option"></textarea></div>\n' +
                        '            </label>\n' +
                        '<div id="addOptionButton' + count + '"><button class="btn btn-blue-grey" type="button" onclick="addOption(' + count + ')">Add option</button></div>'
                        '        </form>';
                    break;
                case 2:
                    concreteSector.innerHTML += '<div style="margin-bottom: 10px;"><span style="color: gray;">Single choice answers</span></div><form class="form-group" name="' + 'question' + count + '">\n' +
                        '<input name="question_type' + count + '" type="hidden" value="2">\n' +
                        '            <label class="my-1 mr-2" for="questionText">Question body: </label>\n' +
                        '            <div class="md-form"><textarea class="md-textarea form-control" name="question_text' + count + '" id="questionText" cols="20" rows="2" placeholder="Enter question text"></textarea></div>\n' +
                        '            <label class="my-1 mr-2">Option: ' +
                        '            <div class="md-form"><textarea class="md-textarea form-control" name="question_option' + count + '" cols="20" rows="2" placeholder="Enter question option"></textarea></div>\n' +
                        '            </label>\n' +
                        '<div id="addOptionButton' + count + '"><button class="btn btn-blue-grey" type="button" onclick="addOption(' + count + ')">Add option</button></div>'
                    '        </form>';
                    break;
                case 3:
                    concreteSector.innerHTML += '<div style="margin-bottom: 10px;"><span style="color: gray; margin-bottom: 10px;">Text answers</span></div><form class="form-group" name="' + 'question' + count + '">\n' +
                        '            <input name="question_type' + count + '" type="hidden" value="3">\n' +
                        '            <label class="my-1 mr-2" for="questionText">Question body: </label>\n' +
                        '            <div class="md-form"><textarea class="md-textarea form-control" name="question_text' + count + '" id="questionText" cols="20" rows="2" placeholder="Enter question text"></textarea></div>\n' +
                    '        </form>';
                    break;
                default:
                    break;
            }

            concreteSector.outerHTML += '<div id="concreteQuestionSector' + (count + 1) + '">' +
                '<button class="btn btn-primary" type="button" id="buttonAddNewQuestion" onclick="createQuestionTypeSelect()">Add another question</button></div>';
        }
        function addOption(formID) {
            let targetForm = document.getElementsByName("question" + formID)[0];
			let addOptionButton = document.getElementById("addOptionButton" + formID);
            let newLabel = document.createElement('label');
            newLabel.className = 'my-1 mr-2'
            let newOption = 'Option: <div class="md-form"><textarea class="md-textarea form-control" name="question_option' + formID + '" cols="20" rows="2" placeholder="Enter question option"></textarea></div>\n';
            newLabel.innerHTML += newOption;
            targetForm.insertBefore(newLabel, addOptionButton);
        }
        function submitAllQuestions() {

            // Prepare data
            let data = {};

            // Get general survey data
            let formToSubmit = document.getElementsByName("survey-data")[0];
            let surveyName = formToSubmit.getElementsByTagName("input")[0];
            let surveyDesc = formToSubmit.getElementsByTagName("textarea")[0];
            data[surveyName.name] = surveyName.value;
            data[surveyDesc.name] = surveyDesc.value;


            // Use loop to iterate through all existing forms
            for (let i = 1; i <= count; i++)
            {
                formToSubmit = document.getElementsByName("question" + i)[0];
                let questionType = formToSubmit.getElementsByTagName("input")[0];
                let bodyAndOptions = formToSubmit.getElementsByTagName("textarea");
                data[questionType.name] = questionType.value;
                data[bodyAndOptions[0].name] = bodyAndOptions[0].value;
                if (questionType.value != 3) {
                    let arrayOptions = [];

                    // Use nested loop to prepare array of options
                    for (let i = 1; i < bodyAndOptions.length; i++) {
                        arrayOptions.push(bodyAndOptions[i].value);
                    }
                    data[bodyAndOptions[1].name] = arrayOptions;
                }
            }


            // Create HttpRequest manually
            let xhr = new XMLHttpRequest();

            // Convert dictionary of survey data to HTTP parameters
            let params = dictToURI(data);



            // Set HTTP method and headers
            xhr.open("POST", 'add_survey.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    window.location.href = "index.php";
                }
            }

            // Send the request
            xhr.send(params);

        }

        function dictToURI(dict) {
            let str = [];
            for (let p in dict){
                if (Array.isArray(dict[p])) {
                    for (let value in dict[p]) {
                        str.push(encodeURIComponent(p) + "[]" + "=" + encodeURIComponent(dict[p][value]));
                    }
                }
                else {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(dict[p]));
                }
            }
            str.push(encodeURIComponent("question_count") + "=" + encodeURIComponent(count));
            return str.join("&");
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
    <a class="navbar-back" href="index.php">Back</a>
</nav>
<div class="container create-scope">
    <div class="card survey-general-data">
        <form name="survey-data">
            <h4 class="survey-heading">Title</h4>
            <input id="surveyName" type="text" name="survey_name" placeholder="Enter your survey title" required>
            <div class="md-form">
                <h5 class="survey-heading" style="margin-top: 10px;">Description</h5>
                <textarea class="md-textarea form-control" name="survey_desc" id="surveyDesc" cols="30" rows="5" placeholder="Enter your survey desc" required></textarea>
            </div>
        </form>
    </div>
    <div id="questionSector" class="card question-sector">
        <div id="concreteQuestionSector1">
            <h4 class="question-number">Question №1</h4>
            <label class="my-1 mr-2" for="questionTypeSelect" id="questionTypeCovering"> Choose question type </label>
            <select id="questionTypeSelect" class="custom-select my-1 mr-sm-2" name="questionType" size="3">
               <option value="1" selected>Multiple choice answers</option>
               <option value="2">Single choice answers</option>
               <option value="3">Text answers</option>
            </select>
            <button style="margin-top: 10px;" class="btn btn-primary" type="button" id="questionTypeApplyButton" onclick="createQuestionContent()">Apply</button>
        </div>
    </div>
    <div class="submit-sector">
        <button style="margin-top: 0px; margin-left: 0px; width: 100%; border-top-left-radius: 0; border-top-right-radius: 0;" class="btn btn-success" type="submit" onclick="submitAllQuestions()">Add</button>
    </div>
</div>
</body>
</html>
<?php } ?>