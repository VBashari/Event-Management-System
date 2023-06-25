<!DOCTYPE html>
<html>
    <head>
        <title>Create a post</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="../assets/sidebar.css">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    
        <style>
            body { background-color: var(--palette-grey) !important; }

            #post-form { background-color: white; }
            #post-form button { 
                background-color: var(--palette-blue) !important; 
                color: white;
            }
        </style>
    </head>
    <body>
        <?php include_once __DIR__ . '/../assets/header.php'; ?>

        <div class="d-flex">
            <?php include_once __DIR__ . '/../assets/sidebar.html'; ?>

            <!-- Events container -->
            <div class="db-content m-5 p-3 w-50 h-100">
                <h2>Create a new post</h2>

                <form id="post-form" method="POST" action="" class="px-5 py-4">
                    <label for="title" class="form-label">Title</label>
                    <input id="title" type="text" required placeholder="Insert a title for your post" class="form-control w-50">

                    <br>
                    <label for="photos[]" class="form-label">Images:</label>
                    <input id="photos[]" type="file" required multiple>

                    <br>
                    <div class="text-right">
                        <button type="submit" class="btn mt-5 px-5">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>