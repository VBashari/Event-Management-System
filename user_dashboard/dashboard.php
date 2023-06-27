<?php
$user = require_once __DIR__ . '/../auth.php';
if (!$user) {
    header('Location: ../login.html');
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/sidebar.css">
    
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        <style>
            body { background-color: var(--palette-grey) !important; }
            
            #events { background-color: white; }            
            .event { background-color: #D6F7F7; }

            h5, .fa-calendar { color: var(--palette-blue); }
        </style>
    </head>
    <body>
        <?php include __DIR__ . '/../assets/header.php'; ?>

        <div class="d-flex">
            <?php include __DIR__ . '/../assets/sidebar.php'; ?>

            <!-- Events container -->
            <div id="events" class="db-content m-5 p-3 w-75 h-100">

                <h2 class="mb-0">Month_name</h2>
                <p class="font-italic">Here are your events for the month:</p>

                <!-- Event elements go here -->
            </div>
        </div>

        <script>const userID = <?php echo $user['user_id'] ?></script>
        <script src="../scripts/utility.js"></script>
        <script src="../scripts/dashboard.js"></script>
        <script src="../scripts/login.js"></script>
    </body>
</html>