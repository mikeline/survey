<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        $is_allowed_to_register = $_SESSION['is_allowed_to_register'];
    }
    require_once('db_connect.php');
    require_once('Tools.php');
    $basename = basename(__FILE__);

    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Survey | Home</title>
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css"/>
	<link type="text/css" rel="stylesheet" href="styles/my_styles.css?<?php echo time(); ?>"/>
    <script>
        let offset = 12;
        function loadMore() {

            let xhr = new XMLHttpRequest();
            let params = "offset=" + offset;
            xhr.open("POST", 'load_more.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let data = JSON.parse(xhr.response);
                    addCards(data);
                }
            }

            // Send the request
            xhr.send(params);

        }
        function addCards(data) {
            let targetDiv = document.getElementById("surveyRow");
            let loginSpan = document.getElementById("loginSpan");

            let j = 0;
            data.forEach(function(survey) {
                let newCell = document.createElement("div");
                newCell.className = "col-8 col-sm-6 col-md-3 col-lg-3";
                let newCard = document.createElement("div");
                newCard.className = "card survey-card";
                if (loginSpan) {
                    newCard.innerHTML = '<a href="fill_survey.php?id=' + survey[0] + '"><h5 class="survey-name">' + survey[1] + '</h5></a>' +
                        '                <p class="survey-description">' + survey[2] + '</p>' +
                        '                <span class="survey-time-created">' + survey[3] + '</span>'
                }
                else {
                    newCard.innerHTML = '<a href=""><h5 class="survey-name">' + survey[1] + '</h5></a>' +
                        '                <p class="survey-description">' + survey[2] + '</p>' +
                        '                <span class="survey-time-created">' + survey[3] + '</span>'
                }
                newCell.appendChild(newCard);
                targetDiv.appendChild(newCell);
                ++j;
            }
            );

            offset += j;
        }

        function searchSurveys() {
            let searchInput = document.getElementById("searchInput");
            let searchString = searchInput.value;
            if (searchString) {
                let xhr = new XMLHttpRequest();
                let params = "search_string=" + searchString;
                xhr.open("POST", 'search.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        let data = JSON.parse(xhr.response);
                        renderFoundSurveys(data);
                    }
                }

                // Send the request
                xhr.send(params);
            }
        }

        function renderFoundSurveys(data) {
            let container = document.getElementById("mainContainer");
            let surveyRow = document.getElementById("surveyRow");
            let loadMoreText = document.getElementById("loadMoreText");
            let loginSpan = document.getElementById("loginSpan");
            let login = loginSpan.value;

            container.removeChild(loadMoreText);
            surveyRow.innerHTML = "";

            Object.keys(data).forEach(function(i){
                if (login) {
                    surveyRow.innerHTML += '<div class="col-8 col-sm-6 col-md-3 col-lg-3">\n' +
                        '                <div class="card survey-card">' +
                        '                    <a href="fill_survey.php?id=' + data[i].id + '"><h5 class="survey-name">' + data[i].name + '</h5></a>' +
                        '                    <p class="survey-description">' + data[i].desc + '</p>' +
                        '                    <span class="survey-time-created">' + data[i].delta_time + '</span>' +
                        '                </div>' +
                        '            </div>'

                }
                else
                {
                    surveyRow.innerHTML += '<div class="col-8 col-sm-6 col-md-3 col-lg-3">\n' +
                        '                <div class="card survey-card">' +
                        '                    <a href="#"><h5 class="survey-name">' + data[i].name + '</h5></a>' +
                        '                    <p class="survey-description">' + data[i].desc + '</p>' +
                        '                    <span class="survey-time-created">' + data[i].delta_time + '</span>' +
                        '                </div>' +
                        '            </div>'
                }
            });
            let newDiv = document.createElement('div');
            newDiv.style.textAlign = "center";
            let backToTopSurveys = document.createElement('a');
            backToTopSurveys.href = "";
            backToTopSurveys.innerText = "Back to top surveys";
            newDiv.appendChild(backToTopSurveys);
            container.appendChild(newDiv);
        }
    </script>
</head>
<body>
    <?php include("my_header.php");?>
    <div class="survey-list-title">
        <h3 class="display-4">Choose the survey</h3>
    </div>
	<div id="mainContainer" class="container survey-container">
		<div id="surveyRow" class="row survey-row">
        <?php	
            $query_survey = "SELECT * FROM survey ORDER BY date_created DESC LIMIT 12";
            $survey_result = mysqli_query($link, $query_survey);
            if($survey_result)
            {
                while($survey = mysqli_fetch_array($survey_result)) 
				{
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
        ?>
		
            <div class="col-8 col-sm-6 col-md-3 col-lg-3">
                <div class="card survey-card">
                    <?php
                    if (isset($login))
                    { ?>
                    <a href="fill_survey.php?id=<?php echo $id; ?>"><h5 class="survey-name"><?php echo $name; ?></h5></a>
                    <?php }
                    else
                    { ?>
                    <a href="#"><h5 class="survey-name"><?php echo $name; ?></h5></a>
                    <?php } ?>

                    <p class="survey-description"><?php echo $desc_final; ?></p>
                    <span class="survey-time-created"><?php echo $delta_time; ?></span>
                </div>
            </div>
        <?php }
			}
            else 
            {
                echo "Failed to connect";
            } ?>
		</div>
        <div id="loadMoreText" style="text-align: center;">
            <span class="load-more" style="font-size: 1.1em;" onclick="loadMore()">Load more</span>
        </div>
    </div>
    <span id="loginSpan" style="visibility: hidden;">
        <?php
            if(isset($login))
            {
                echo $login;
            }
        ?>
    </span>
    <div class="form-popup" id="myForm">
        <form action="login.php" method="post" class="form-container">
            <h1>Login</h1>

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <button type="submit" class="btn">Login</button>
            <button type="submit" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
    </div>
	<script>
		function openForm() 
		{
			document.getElementById("myForm").style.display = "block";
		}

		function closeForm() 
		{
			document.getElementById("myForm").style.display = "none";
		}
	</script>
</body>
</html>