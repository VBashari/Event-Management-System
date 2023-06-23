<!DOCTYPE html>
<html>
    <head>
        <title>Post</title>

        <link rel="stylesheet" href="./style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

        <style>
            body { background-color: var(--palette-grey); }

            #content { 
                background-color: white;
                padding-left: 10em;
                padding-right: 10em;
            }

            #service { gap: 3em; }

            #service > img {
                object-fit: contain;
                max-width: 700px;
                min-width: 600px;
            }

            h4 { color: lightslategrey; }

            #send-req {
                background-color: var(--palette-blue);
                color: white;
            }

            #more {
                color: lightslategrey;
                border: 1px solid var(--palette-blue);
            }
        </style>
    </head>
    <body>
        <?php include_once __DIR__ . '/assets/header.html'; ?>

        <div id="content" class="my-5 py-5">
            <div id="service" class="d-flex">
                <img src="../photos/frontend/party.jpg">

                <div>
                    <div class="d-flex justify-content-between">
                        <h3 class="font-weight-bold">Service title</h3>
                        <h4 class="font-italic">$56</h4>
                    </div>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Integer malesuada nunc vel risus commodo viverra maecenas. Id ornare 
                        arcu odio ut sem nulla pharetra diam sit. Fusce id velit ut tortor 
                        pretium viverra suspendisse. Tristique et egestas quis ipsum suspendisse. 
                        Potenti nullam ac tortor vitae purus faucibus ornare suspendisse. Elit 
                        eget gravida cum sociis natoque penatibus et magnis. Nisl suscipit adipiscing 
                        bibendum est. Nisl suscipit adipiscing bibendum est ultricies integer quis 
                        auctor. Eget nulla facilisi etiam dignissim diam quis. Ullamcorper a lacus 
                        vestibulum sed arcu non odio euismod. Amet luctus venenatis lectus magna 
                        fringilla urna. Malesuada fames ac turpis egestas integer eget aliquet. Sed 
                        augue lacus viverra vitae congue eu.
                    </p>
                    
                    <button id="send-req" data-id="" type="button" class="float-right btn font-weight-bold px-4 py-2">Send request</button>
                </div>
            </div>

            <button id="more" data-servicer-id="" type="button" class="btn font-weight-bold text-uppercase mt-3 px-5">More from this servicer</button>
        </div>

        <script>
            document.getElementById('homepage').setAttribute('href', './homepage.html');
            document.getElementById('signup').setAttribute('href', './signup.html');
            document.getElementById('login').setAttribute('href', './login.html');
        </script>
    </body>
</html>