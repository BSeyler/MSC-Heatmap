<!--
Bigger Four
index.php
The homepage of the website
-->
<html lang="en">

<head>

    <!-- Linked style sheets that are necessary for this page -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/ed_global.css?v=<?php echo rand()?>" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
    <!-- Leaflet API script linked so it can be used -->
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="scripts/leaflet.awesome-markers.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="leaflet.ajax.min.js"></script>
    <script src="src/leaflet-search.js"></script>
    <link rel="stylesheet" href="src/leaflet-search.css" />
    <link href="https://fonts.googleapis.com/css?family=Muli%7CPoppins" rel="stylesheet">
    <title>MSC Heatmap</title>
</head>
<body>

<div class="container-fluid">
    <!-- TOP NAVBAR -->
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <img class="navbar-brand" alt="logo" src="images/logo.png" id="logo">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Resources</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Staff</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Donate</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav my-2 my-lg-0 sort-help-button">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sort</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!-- MAIN CONTAINER -->
    <div class="row map-row">
        <div class="col-md-12">
            <div id="mapid"></div>
        </div>
    </div>
    <!-- FOTOER -->
    <div class="row footer-row">
        <div class="col-md-12">
            <footer class="page-footer font-small blue">

                <!-- Copyright -->
                <div class="footer-copyright text-center py-3">
                    <p>The Washington State Long-Term Care Ombudsman advocates for residents of nursing homes, adult family homes, and assisted living facilities. Our purpose is to protect and promote the Resident Rights guaranteed these residents under Federal and State law and regulations.

                        We are trained to receive complaints and resolve problems in situations involving quality of care, use of restraints, transfer and discharge, abuse and other aspects of resident dignity and rights.</p>
                </div>
                <!-- Copyright -->

            </footer>
        </div>
    </div>
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