<!--
Bigger Four
index.php
The homepage of the website
-->
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Linked style sheets that are necessary for this page -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/global.css?v=<?php echo rand()?>" />
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">
    <link href="styles/leaflet.awesome-markers.css" rel="stylesheet">
    <link href="styles/leaflet-geojson-selector.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
    <!-- Leaflet API script linked so it can be used -->
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="leaflet.ajax.min.js"></script>
    <script src="src/leaflet-search.js"></script>
    <link rel="stylesheet" href="src/leaflet-search.css" />
    <script src="scripts/leaflet-geojson-selector.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Muli%7CPoppins" rel="stylesheet">
    <title>MSC Heatmap</title>
</head>
<body>
<!-- MAIN CONTAINER -->
<div class="container-fluid ">
    <!-- TOP HIDDEN NAVBAR WHEN SCREEN IS SMALL -->
    <div class="row navbar-row d-block d-sm-none">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-light">
                <img class="navbar-brand" alt="logo" src="images/logo.png" id="logo-hidden">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="https://www.waombudsman.org/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="https://www.waombudsman.org/resources/">Resources</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="https://www.waombudsman.org/staff/">Staff</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="https://www.waombudsman.org/donate/">Donate</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="https://www.waombudsman.org/contact/">Contact</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav my-2 my-lg-0 sort-help-button">
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="#">Sort</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-hide" href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!-- END HIDDEN NAVBAR -->

    <!-- MAIN CONTENT -->
    <div class="row main-row">

        <!-- SIDE NAV BAR -->
        <div class="col-2 d-none d-sm-block">
            <div>
                <img alt="logo" src="images/logo.png" id="logo">
            </div>
            <u></u>
            <!-- Sidebar -->
            <div class="sidebar-nav">

                <div class="row help-row">
                    <div class="col-5">
                        <img src="images/square-click.png" id="marker-image">
                    </div>
                    <div class="col-7">
                        <h5>Click a Marker!</h5>
                        <p id="help-text">
                            Click on a marker to view more information about the nursing home, like fines, rating, and number of fines.
                        </p>
                    </div>
                </div>
                <div class="row help-row">
                    <div class="col-5">
                        <img src="images/square-search.png" id="marker-image">
                    </div>
                    <div class="col-7">
                        <h5>Search For a Home!</h5>
                        <p id="help-text">
                            Click the search button and lookup a nursing home by it's name to find it on the map!
                        </p>
                    </div>
                </div>
                <div class="row help-row">
                    <div class="col-5">
                        <img src="images/square-colors.png" id="marker-image">
                    </div>
                    <div class="col-7">
                        <h5>Pay attention to the Colors!</h5>
                        <p id="help-text">
                            <strong>Red: </strong> I - L
                            <strong>Orange:</strong> E - H
                            <strong>Green</strong> A - D
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <!-- END OF SIDE NAVBAR -->

        <!-- MAP DIV-->
        <div class="col-12 col-sm-10">
            <div id="mapid"></div>
        </div>
        <!-- END OF THE DIV MAP -->
    </div>

    <!-- Bootstrap footer to assist with navigation -->
    <footer class="page-footer font-small indigo d-none d-sm-block">
        <div class="row text-center d-flex justify-content-center pt-5 mb-3">
            <div class="col-md-2 mb-3">
                <h6 class="text-uppercase font-weight-bold">
                    <a href="https://www.waombudsman.org/">Home</a>
                </h6>
            </div>
            <div class="col-md-2 mb-3">
                <h6 class="text-uppercase font-weight-bold">
                    <a href="https://www.waombudsman.org/resources/">Resources</a>
                </h6>
            </div>
            <div class="col-md-2 mb-3">
                <h6 class="text-uppercase font-weight-bold">
                    <a href="https://www.waombudsman.org/staff/">Staff</a>
                </h6>
            </div>
            <div class="col-md-2 mb-3">
                <h6 class="text-uppercase font-weight-bold">
                    <a href="https://www.waombudsman.org/donate/">Donate</a>
                </h6>
            </div>
            <div class="col-md-2 mb-3">
                <h6 class="text-uppercase font-weight-bold">
                    <a href="https://www.waombudsman.org/contact/">Contact</a>
                </h6>
            </div>
            <!-- Disclaimer about MSC -->
            <hr class="rgba-white-light" style="margin: 0 15%;">
            <div class="row d-flex text-center justify-content-center mb-md-0 mb-4">
                <div class="col-md-8 col-12 mt-5">
                    <p style="line-height: 1.7rem">The Washington State Long-Term Care Ombudsman advocates for residents of nursing homes, adult family homes, and assisted living facilities. Our purpose is to protect and promote the Resident Rights guaranteed these residents under Federal and State law and regulations.

                        We are trained to receive complaints and resolve problems in situations involving quality of care, use of restraints, transfer and discharge, abuse and other aspects of resident dignity and rights.</p>
                </div>
            </div>
            <!-- Bootstrap row with links to the MSC's facebook, twitter, google and youtube along with their logos -->
            <div class="row">
                <div class="col-md-12" id="icons">
                    <div class="mb-5 flex-center">
                        <a class="fb-ic" href="http://www.facebook.com/WAOmbudsman?fref=ts">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="tw-ic" href="https://twitter.com/WA_LTCOmbuds">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="gplus-ic" href="">
                            <i class="fab fa-google-plus-g"></i>
                        </a>
                        <a class="ins-ic" href="https://www.youtube.com/watch?v=20rzmCSDXU0&feature=youtu.be&list=UUKSYXVbT1luXSYyERphv-nQ">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- Scripts linked which are necessary -->
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<script src="http://code.jquery.com/jquery.min.js"></script>
<script src="scripts/marker.js"></script>
<script src="scripts/leaflet.awesome-markers.js"></script>

<!-- More scripts linked -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>