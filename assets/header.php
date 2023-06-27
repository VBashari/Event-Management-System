<?php
$user = require_once __DIR__ . '/../auth.php';
?>

<nav class="navbar navbar-expand-lg">
    <a id="homepage" class="navbar-brand ml-4" href="../index.php">
        <img src="../photos/frontend/logo.png" alt="Logo" width="80">
    </a>

    <div class="col-5">
        <form method="GET" action="../frontend/service_search.php" class="form-inline">
            <input class="form-control rounded-pill col-12" name="q" type="text" placeholder="Find vendors and event organizers" aria-label="Search">
            
            <span class="input-group-append">
                <button class="btn rounded-pill border-0 ml-n5" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </form>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse d-flex flex-row-reverse mx-5" id="navbarSupportedContent">
        <ul class="nav navbar-nav">
        <?php if ($user !== null): ?>
            <li class="nav-item dropdown">
                <a id="profile" class="nav-link dropdown-toggle font-weight-bold mx-3 px-5" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i>&nbsp;&nbsp;My Profile
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="dashboard.php">Dashboard</a>
                    <a class="dropdown-item" href="/" onclick="signOut();">Sign out</a>
                </div>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a id="signup" class="nav-link font-weight-bold mx-5" href="../signup.html">Sign up</a>
            </li>

            <li class="nav-item">
                <a id="login" class="nav-link" href="../login.html">Log in</a>
            </li>
        <?php endif; ?>
        </ul>
    </div>
</nav>