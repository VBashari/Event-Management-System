<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/sidebar.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    </head>
    <body style="background-color:lightgray;">
        <?php include_once __DIR__ . "/../assets/header.php"; ?>

        <div class="d-flex">
            <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>
            <div class="container m-5 p-4" style="background-color:white;">
                <h3 class="font-weight-bold">Create a service</h3>

            <form id="service-form" method="POST" action="" class="w-75">
                <div class="d-flex justify-content-between" style="gap: 1em;">
                    <div class="flex-fill">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" required type="text" class="form-control" placeholder="Enter title">

                        <div id="title-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>
                    </div>

                    <br>
                    <div class="flex-fill">
                        <label for="price">Average price</label>
                        <input type="number" id="avg_price" placeholder="Enter the average price for this service" required class="form-control">

                        <div id="avg_price-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>
                    </div>
                    
                </div>
                
                <br>
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-control" rows="8" cols="80" placeholder="Enter a description about the service, what it entails, and its details"></textarea>
                <div id="description-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>

                <br>
                <label for="photos[]" class="form-label">Select files:</label>
                <input type="file" id="photos[]" required multiple>
                <div id="photos-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>

                <div class="text-right">
                    <button type="submit" class="btn btn-dark px-5">Submit</button>
                </div>
            </form>
        </div>
        </div>

        <script>const userID = <?php echo $user['user_id']; ?></script>
        <script src="../scripts/utility.js"></script>
        <script src="../scripts/service_form.js"></script>
        <script src="../scripts/ajax.js"></script>
        <script src="../scripts/login.js"></script>
    </body>
</html>