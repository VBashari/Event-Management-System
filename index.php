<?php
require_once __DIR__ . '/api/utils/utils.php';
require_once __DIR__ . '/api/utils/errors.php';

// load sensitive info
loadEnv(__DIR__ . '/api/.env');

require_once __DIR__ . '/api/controllers/AuthController.php';

$user = AuthController::getUser();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/homepage.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
        <script src="scripts/login.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="navbar-brand d-flex align-items-center ml-4">
                <img src="photos/frontend/logo.png" alt="Logo" width="130">
                <h2 class="font-weight-bold font-italic">Event Management</h2>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Change to "my profile" link if logged in-->
            <div class="collapse navbar-collapse d-flex flex-row-reverse mx-5" id="navbarSupportedContent">
                <ul class="nav navbar-nav">
                <?php if ($user !== null): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn btn-dark font-weight-bold mx-3 px-5" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i>&nbsp;&nbsp;My Profile
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="user_dashboard/dashboard.php">Dashboard</a>
                            <a class="dropdown-item" href="" onclick="signOut();">Sign out</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-dark font-weight-bold mx-3 px-5" href="./signup.html">Sign up</a>
                    </li>
        
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-dark font-weight-bold px-5" href="./login.html">Log in</a>
                    </li>
                <?php endif; ?>
                </ul>
            </div>
        </nav>

        <div class="py-5"></div>
        <div class="py-5"></div>

        <div class="m-auto w-50">
            <h1 id="title" class="text-center font-weight-bold">We make event planning <span class="font-weight-normal font-italic">easy</span></h1>

            <form method="GET" action="./frontend/service_search.php" class="d-flex">
                <input name="q" class="form-control form-control-lg rounded-pill" type="search" placeholder="What event are you hosting?" aria-label="Search">
                
                <span class="input-group-append">
                    <button class="btn rounded-pill border-0 font-weight-bold ml-n5 px-5" type="submit">Submit</button>
                </span>
            </form>
        </div>
    </body>
</html>