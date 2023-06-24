<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="./assets/style.css">
        <link rel="stylesheet" href="./assets/sidebar.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include_once __DIR__ . "/assets/header.html"; ?>
        <div class="container p-10">
            <h3>Servicer username</h3>
            <button type="button" class="btn btn-link" style="color:black;">Service</button>
            <button type="button" class="btn btn-link">Posts</button>
                <div class="d-flex flex-wrap"  style="gap:2em;" >
                    <div class="card" style="width:20rem;">
                        <img src="./photos/party.jpg" alt="Something" class="card-img-top">
                        <div class="card-body">
                           <h5 class="card-title">Event Name</h5>
                        </div>
                    </div>
                    <div class="card" style="width:20rem;">
                        <img src="./photos/party.jpg" alt="Something" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">Event Name</h5>
                        </div>
                    </div>
                    <div class="card" style="width:20rem;">
                        <img src="./photos/party.jpg" alt="Something" class="card-img-top">
                        <div class="card-body">
                           <h5 class="card-title">Event Name</h5>
                        </div>
                    </div>
                    <div class="card" style="width:20rem;">
                       <img src="./photos/party.jpg" alt="Something" class="card-img-top">
                       <div class="card-body">
                            <h5 class="card-title">Event Name</h5>
                        </div>
                    </div>
            </div>
            <div class="d-inline float-right">
                <button type="button" class="btn btn-dark ">Next</button>
                <button type="button" class="btn btn-dark ">Prev</button>
            </div>

        </div>
    </body>
</html>