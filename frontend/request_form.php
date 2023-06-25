<!DOCTYPE html>
<html>
    <head>
        <title>Send a request</title>

        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        <style>
            h3 { color: var(--palette-black); }
            p { color: lightslategrey; }
        </style>
    </head>
    <body>
        <?php include_once __DIR__ . '/../assets/header.php'; ?>

        <div class="m-5 pl-5 w-50">
            <h3 class="font-weight-bold">Send a request</h3>

            <form method="POST" action="">
                <div class="d-flex justify-content-between" style="gap: 2em;">
                    <div class="flex-fill">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" required type="text" class="form-control" placeholder="Enter a quick & short title describing your event">

                        <div id="title-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>
                    </div>
                    
                    <div>
                        <label for="date" class="form-label">Date</label>
                        <input id="date" required type="date" class="form-control">
                    </div>

                    <div>
                        <label for="time" class="form-label">Time</label>
                        <input id="time" required type="time" class="form-control">
                    </div>
                </div>

                <div id="scheduled_date-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>
                
                <br>
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-control" rows="8" cols="80"
                    placeholder="What's the event about? Who's the audience? What are the details of the service your looking for?"></textarea>
                
                <div id="description-feedback" class="invisible alert alert-danger d-inline-block py-1 mt-1" role="alert"></div>

                <input hidden name="servicer_id" value="<?php echo $_POST['id']; ?>">
                <button type="submit" class="btn btn-dark float-right mt-4 px-4">Submit</button>
            </form>
        </div>

        <script src="../scripts/utility.js"></script>
        <script src="../scripts/request_form.js"></script>
    </body>
</html>