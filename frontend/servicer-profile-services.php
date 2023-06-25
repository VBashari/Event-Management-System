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
            <div class="container m-2">
                <h3>My Services</h3>
                <div class="card mb-3" style="max-width: 1100px">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="./photos/party.jpg" class="img-fluid rounded-start" alt="..." width="1000">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Service Tittle</h5>
                                <p>
                                <span class="badge badge-pill badge-info">Infoafsdf</span>
                                <span class="badge badge-pill badge-info">Info</span>
                                <span class="badge badge-pill badge-info">Info</span>

                                </p>
                                <p class="card-text">This text is for description purposes. asdihf uias i dshaf hasdi fiuash fiuasfh aisud fasiu fasiudf hasiuf hasiuf haisuf hiusa fhuias fhiuasfh iuash fiuasf huias hfuiash fuiash fiuasd fhuias fhauisf hasiu fhasui hasui hasui hsdauifh auisdhfiuash fiu asd koasj oasj iosdaj oidsaj oisaj ioasj oais jasoi jasdoi jsadoi jasoi jasoi jiosadj oiasdj oiasj ioasdj oiasj ioasjd oiasdj ioas jasoid joiasj ioasdj oiasj oijsioaj aoisdj oiajsd oiaj oia djasdoi jaosi joi</p>
                                <div class="float-right">
                                    <button class="btn btn-dark">Edit</button>
                                    <button class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3" style="max-width: 1100px">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="./photos/party.jpg" class="img-fluid rounded-start" alt="..." width="1000">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Service Tittle</h5>
                                <p>
                                <span class="badge badge-pill badge-info">Infoafsdf</span>
                                <span class="badge badge-pill badge-info">Info</span>
                                <span class="badge badge-pill badge-info">Info</span>

                                </p>
                                <p class="card-text">This text is for description purposes. asdihf uias i dshaf hasdi fiuash fiuasfh aisud fasiu fasiudf hasiuf hasiuf haisuf hiusa fhuias fhiuasfh iuash fiuasf huias hfuiash fuiash fiuasd fhuias fhauisf hasiu fhasui hasui hasui hsdauifh auisdhfiuash fiu asd koasj oasj iosdaj oidsaj oisaj ioasj oais jasoi jasdoi jsadoi jasoi jasoi jiosadj oiasdj oiasj ioasdj oiasj ioasjd oiasdj ioas jasoid joiasj ioasdj oiasj oijsioaj aoisdj oiajsd oiaj oia djasdoi jaosi joi</p>
                                <div class="float-right">
                                    <button class="btn btn-dark">Edit</button>
                                    <button class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <button class="btn btn-dark">Prev</button>
                    <button class="btn btn-dark">Next</button>
                </div>
            </div>
        </div>
    </body>
</html>