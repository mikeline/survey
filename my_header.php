<nav class="navbar navbar-expand-lg navbar-light bg-light" style="width: 100%;">
    <a class="navbar-brand" href="index.php">Survey Editor</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item<?php echo $basename == 'index.php' ? ' active' : '' ?>">
                <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
            </li>
			<?php 
				if(isset($login))
				{
			?>
            <li class="nav-item<?php echo $basename == 'create_survey.php' ? ' active' : '' ?>">
                <a class="nav-link" href="create_survey.php">Create survey<span class="sr-only">(current)</span></a>
            </li>
				<?php } ?>
            <?php
            if (isset($login))
            {
              ?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Log out</a>
            </li>
            <?php } else {?>
            <li class="nav-item<?php echo $basename == 'login.html' ? ' active' : '' ?>">
                <a id="navbarLoginLink" class="nav-link" onclick="openForm()">Log in<span class="sr-only">(current)</span></a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <?php
                if (isset($is_allowed_to_register))
                { ?>
                    <a href="register.php" class="nav-link" id="nav-register">
                        <?php  echo "Register new user"; ?>
                    </a>
                <?php } ?>
            </li>
            <li class="nav-item">
                <?php
                if (isset($login))
                { ?>
                <a href="account_panel.php" class="nav-link" id="nav-username">
                      <?php  echo "My account"; ?>
                </a>
                <?php } ?>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="get" action="search.php">
            <input id="searchInput" class="form-control mr-sm-2" name="search_string" type="text" placeholder="Search" aria-label="Search">
            <button onclick="searchSurveys()" class="btn btn-outline-success my-2 my-sm-0" type="button">Search</button>
        </form>
    </div>
</nav>