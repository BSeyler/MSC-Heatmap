<!--
Bigger Four
index.php
The homepage of the website
-->
<html lang="en">

<head>

    <!-- Linked style sheets that are necessary for this page -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/global.css" />
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">
    <link href="styles/leaflet.awesome-markers.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
    <!-- Leaflet API script linked so it can be used -->
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="leaflet.ajax.min.js"></script>
    <script src="src/leaflet-search.js"></script>
    <link rel="stylesheet" href="src/leaflet-search.css" />
    <link href="https://fonts.googleapis.com/css?family=Muli%7CPoppins" rel="stylesheet">
    <title>MSC Heatmap</title>
</head>
<body>
<div class="row">
    <div class="col-2">
        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <a class="navbar-brand" href="#"><img alt="logo" src="images/logo.png" id="logo"></a>
                    </li>
                    <li id="first-part">
                        <a href="">Upload<i class="fas fa-file-upload"></i></a>
                    </li>
                    <li>
                        <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Sort By<i class="fas fa-sort-alpha-down"></i>
                        </a>

                    </li>
                    <li>
                        <div class="collapse multi-collapse" id="collapseExample">
                            <div class="card card-body">

                                <!--
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sort:
                                </a>
                                 An internal dropdown menu
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item">Health Rating</a>
                                    <a class="dropdown-item">Safety Fines</a>
                                    <a class="dropdown-item">Name</a>
                                </div>
                                -->
                                <div class="col-6">
                                    <div class="dropdown show">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Select Rating to View:
                                        </button>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item">A</a>
                                            <a class="dropdown-item">B</a>
                                            <a class="dropdown-item">C</a>
                                            <a class="dropdown-item">D</a>
                                            <a class="dropdown-item">E</a>
                                            <a class="dropdown-item">F</a>
                                            <a class="dropdown-item">G</a>
                                            <a class="dropdown-item">H</a>
                                            <a class="dropdown-item">I</a>
                                            <a class="dropdown-item">J</a>
                                            <a class="dropdown-item">K</a>
                                            <a class="dropdown-item">L</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="">Drop a Marker<i class="fas fa-map-marker-alt"></i></a>
                    </li>
                    <li>
                        <a data-toggle="collapse" href="#multiCollapseExample2" role="button" aria-expanded="false" aria-controls="multiCollapseExample2">
                            Help<i class="fas fa-info-circle"></i>
                        </a>
                    </li>
                    <li>
                        <div class="collapse multi-collapse" id="multiCollapseExample2">
                            <div class="card card-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                                Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Div for the map itself -->
    <div class="col-10">
        <div id="mapid"></div>
    </div>

</div>
<!-- Bootstrap footer to assist with navigation -->
<footer class="page-footer font-small indigo">
    <div class="container">
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
        <hr class="clearfix d-md-none rgba-white-light" style="margin: 10% 15% 5%;">
        <div class="row pb-3">
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