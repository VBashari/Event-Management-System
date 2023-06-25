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
            <div class="container m-5 p-4" style="background-color:white;">
                <h3 class="font-weight-bold">Create a service</h3>

            <form method="POST" action="" class="w-75">
                <div class="d-flex justify-content-between">
                    <div class="flex-fill">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" required type="text" class="form-control" placeholder="Enter title">
                    </div>
                </div>
                <br>
                <br>
                <label for="myfile">Select a file:</label>
                <input type="file" id="myfile" name="myfile">
                <br>
                <br>
                <button id="submit-req" type="submit" class="float-right btn btn-primary  mt-4 px-4">Submit</button>
            </form>
        </div>
        </div>
    </body>
</html>