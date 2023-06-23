<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="../assets/sidebar.css">
    
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

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
        <?php include_once __DIR__ . '/../assets/header.html'; ?>

        <div class="d-flex">
            <?php include_once __DIR__ . '/../assets/sidebar.html'; ?>

            <!-- Events container -->
            <div id="events" class="db-content m-5 p-3 w-75 h-100">

                <h2 class="mb-0">Month_name</h2>
                <p class="font-italic">Here are your events for the month:</p>

                <!-- Event element -->
                <div class="event row d-flex align-items-center m-4 p-2" data-id="event-id">
                    <i class="fa fa-calendar fa-2x col-auto" ></i>
                    
                    <div class="text-center">
                        <h5 class="mb-0 pl-0">DD-MM-YYYY</h5>
                        <p class="my-0">HH:MM:SS</p>
                    </div>
                    
                    <div class="col-auto ml-4">
                       <p class="m-0 font-weight-bold">Event title</p>
                       <p class="m-0 font-italic">Organizer: organizer_username</p> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>