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
    <body style="background-color:lightgray;">
        <?php include_once __DIR__ . "/assets/header.html"; ?>
        <div class="d-flex">
            <?php include_once __DIR__ . "/assets/sidebar.html"; ?>
            <div class="db-content m-5 p-3 w-70 h-100">
                <h2>Sent Requests</h2>
                <div class="card p-3 m-2" style="width: 75rem;">
                    <div class="card-body">
                        <h5 class="card-title"><b>Request to:</b> ServerviceUsername</h5>
                        <h6 class="card-subtitle mb-2 "><b>RequestTitle</b></h6>
                        <p class="card-text">This text is just a placeholder for testing and its supposed to hold the request description! Im just writting nonsense so it fills up the space neccesary for testing so dont pay much attention to it boys pls and thank you</p>
                        <div class=" flex-wrap">
                            <p><b style="color:cyan;">Status: </b>status</p>
                            <button type="button" class="btn btn-dark float-right">Edit</button>
                            <button type="button" class="btn btn-danger float-right">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="card p-3 m-2" style="width: 75rem;">
                    <div class="card-body">
                        <h5 class="card-title"><b>Request to:</b> ServerviceUsername</h5>
                        <h6 class="card-subtitle mb-2 "><b>RequestTitle</b></h6>
                        <p class="card-text">This text is just a placeholder for testing and its supposed to hold the request description! Im just writting nonsense so it fills up the space neccesary for testing so dont pay much attention to it boys pls and thank you</p>
                        <div class=" flex-wrap">
                            <p><b style="color:cyan;">Status: </b>status</p>
                            <button type="button" class="btn btn-dark float-right">Edit</button>
                            <button type="button" class="btn btn-danger float-right">Delete</button>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                <button type="button" class="btn btn-dark">Prev</button>
                <button type="button" class="btn btn-dark">Next</button>
                </div>
            </div>
        </div>
    </body>
</html>