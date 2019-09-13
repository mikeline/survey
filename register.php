<?php
    session_start();
    if(isset($_SESSION['login']))
    {
        $login = $_SESSION['login'];
        $is_allowed_to_register = $_SESSION['is_allowed_to_register'];
        if ($is_allowed_to_register)
        {
            require_once('db_connect.php');
?>
<!Doctype html>
<html>
<head>
    <title>Survey | Register new user</title>
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="styles/mdb.min.css">
    <link type="text/css" rel="stylesheet" href="styles/register.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
        <a class="navbar-back" href="index.php">Back</a>
    </nav>
    <div style="margin-top: 20px;" class="container">
        <div style="padding: 20px; width: 70%; margin: 0 auto;" class="card">
            <h3>Registering new user</h3>
            <form class="form-group" action="add_user.php" method="post">
                <div>
                    <div class="form-row-element" style="margin-right: 5px;">
                        <label class="label" for="usernameInput">Username <span style="color: red;">*</span></label>
                        <input class="form-control" id="usernameInput" type="text" name="user_name" required>
                    </div>
                    <div class="form-row-element" style="margin-right: 5px;">
                        <label class="label" for="emailInput">Email <span style="color: red;">*</span></label>
                        <input class="form-control" id="emailInput" type="email" name="user_email" required>
                    </div>
                    <div class="form-row-element">
                        <label class="label" for="passwordInput">Password <span style="color: red;">*</span></label>
                        <input class="form-control"  id="passwordInput" type="password" name="psw" required>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div class="form-row-element" style="margin-right: 5px;">
                        <label class="label" for="firstNameInput">First name <span style="color: red;">*</span></label>
                        <input class="form-control" id="firstNameInput" type="text" name="first_name" required>
                    </div>
                    <div class="form-row-element" style="margin-right: 5px;">
                        <label class="label" for="lastNameInput">Last name <span style="color: red;">*</span></label>
                        <input class="form-control" id="lastNameInput" type="text" name="last_name" required>
                    </div>
                    <div class="form-row-element">
                        <label class="label" for="birthDateInput">Birth date</label>
                        <input class="form-control" id="birthDateInput" type="date" name="birth_date">
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div class="form-row-element" style="margin-right: 5px;">
                        <label class="label" for="occupationSelect">Occupation</label>
                        <select class="form-control" name="occupation" id="occupationSelect">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="another employee">Another employee</option>
                        </select>
                    </div>
                    <div class="form-row-element">
                        <label class="label" for="isAllowedToRegisterSelect">Allow to register</label>
                        <select class="form-control" name="is_allowed_to_register" id="isAllowedToRegisterSelect">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-success" style="margin-top:10px; display: block;" type="submit">Add user</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php } } ?>