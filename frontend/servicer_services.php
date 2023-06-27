<!DOCTYPE html> 
<html>
    <head>
        <title>Servicer's services</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/sidebar.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        <style>
            .card-img {
                object-fit: cover;
                object-position: center;
                width: 100%;
                max-height: 200px;
            }
        </style>
    </head>
    <body style="background-color:white;">
        <?php include_once __DIR__ . "/../assets/header.php"; ?>

        <div class="container p-10 mt-5">
            <h3 id="servicer-name-h3"></h3>
            
            <button type="button" class="btn btn-link">Services</button>
            <button type="button" class="btn btn-link"><a id="servicer-posts-a" href="./servicer_posts.php" style="color:black;">Posts</a></button>

            <div id="services" class="d-flex flex-wrap"  style="gap:2em;" >
            </div>

            <div class="d-inline float-right">
                <button id="prev" type="button" class="btn btn-dark" disabled>Prev</button>
                <button id="next" type="button" class="btn btn-dark">Next</button>
            </div>

        </div>

        <script>const userID = <?php echo $user['user_id']; ?></script>
        <script src="../scripts/utility.js"></script>
        <script src="../scripts/ajax.js"></script>
        <script src="../scripts/servicer_services.js"></script>
        <script src="../scripts/login.js"></script>
    </body>
</html>