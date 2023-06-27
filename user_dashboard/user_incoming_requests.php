<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/sidebar.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    </head>
    <body style="background-color:lightgray;">
        <?php include_once __DIR__ . "/../assets/header.php"; ?>

        <div class="d-flex">
            <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>

            <div class="db-content m-5 p-3 w-75 h-100">
                <h2>My Requests</h2>
                
                <div id="requests"></div>

                <div class="text-right mt-3">
                    <button id="prev" type="button" class="btn btn-dark" disabled>Prev</button>
                    <button id="next" type="button" class="btn btn-dark">Next</button>
                </div>
            </div>
        </div>

        <script>const userID = <?php echo $user['user_id']; ?></script>
        <script src="../scripts/utility.js"></script>
        <script src="../scripts/user_incoming_requests.js"></script>
        <script src="../scripts/login.js"></script>
    </body>
</html>
