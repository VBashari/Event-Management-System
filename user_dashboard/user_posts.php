<!DOCTYPE html>
<html>
    <head>
        <title>My posts</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="../assets/sidebar.css">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    
        <style>
            body { background-color: var(--palette-grey) !important; }

            .card { width: 20em; }

            .card-img {
                object-fit: cover;
                object-position: center;
                width: 100%;
                max-height: 200px;
            }
        </style>
    </head>
    <body>
        <?php include_once __DIR__ . '/../assets/header.html'; ?>

        <div class="d-flex">
            <?php include_once __DIR__ . '/../assets/sidebar.html'; ?>

            <div class="db-content m-5 p-3 w-75 h-100">
                <h2>My posts</h2>

                <div id="posts" class="d-flex flex-wrap" style="gap:2em;">
                    <!-- Post element -->
                    <div class="card rounded-4" data-id="post-id">
                        <!-- Images carousel -->
                        <div id="carouselControls" class="carousel slide carousel-fade">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../../photos/frontend/party.jpg" alt="example1" class="d-block card-img">
                                </div>

                                <div class="carousel-item">
                                    <img src="../../photos/frontend/no_image.jpg" alt="example1" class="d-block card-img">
                                </div>
                            </div>

                            <!-- Carousel controls -->
                            <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                        <!-- Post body -->
                        <div class="card-body">
                            <h5>Post title goes right here siree</h5>
                            
                            <div class="d-inline float-right">
                                <button class="btn btn-outline-secondary btn-sm" data-id="post-id">Edit</button>
                                <button class="btn btn-outline-danger btn-sm" data-id="post-id">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>