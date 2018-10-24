/*
Bigger Four
marker.js
Script that puts the map on the page, adds markers to the map and handles the markers events.
 */

//create the map and sets the view of washington
var mymap = L.map('mapid').setView([47.75, -120.74], 7);

L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiY29ubmVybGVkYmV0dGVyIiwiYSI6ImNqbjVsYms5cTA1eTMzeGxrZTdjbWt0cDYifQ.W6Nphh44Guwtt9wX6pa6uA'
}).addTo(mymap);

//create five markers with their specific coordinates
let markerOne = L.marker([47.595011, -122.298211]);
let markerTwo = L.marker([47.653131, -117.261482]);
let markerThree = L.marker([47.439403, -122.213203]);
let markerFour = L.marker([48.215684, -122.682917]);
let markerFive = L.marker([47.748060, -122.346510]);

//add all marker objects to the map
markerTwo.addTo(mymap);
markerOne.addTo(mymap);
markerThree.addTo(mymap);
markerFour.addTo(mymap);
markerFive.addTo(mymap);
//functions that handle the marker click event, displays the data to that marker when it is clicked on.
function onMarkerClick(e) {


    markerOne.bindPopup("<b>Paramount Rehabilitation And Nursing</b><br>Severity Score:<b>(J)</b></b><br>2611 SOUTH DEARBORN, SEATTLE, WA 98144").openPopup();
    closePopup();
}
function onMarkerClickTwo(e) {

    markerTwo.bindPopup("<b>Gardens On University, The</b><br>Severity Score:<b>(J)</b></b><br>414 S UNIVERSITY RD, SPOKANE, WA 99206 ").openPopup();
    closePopup();
}
function onMarkerClickThree(e) {
    markerThree.bindPopup("<b>Talbot Center For Rehab & Healthcare</b><br>Severity Score:<b>(G)</b></b><br>4430 TALBOT ROAD SOUTH, RENTON, WA 98055  ").openPopup();
    closePopup();
}
function onMarkerClickFour(e) {


    markerFour.bindPopup("<b>Careage Of Whidbey</b><br>Severity Score:<b>(G)</b></b><br>311 NORTHEAST 3RD STREET, COUPEVILLE, WA 98239").openPopup();
    closePopup();
}
function onMarkerClickFive(e) {


    markerFive.bindPopup("<b>The Oaks At Forest Bay</b><br>Severity Score:<b>(D)</b></b><br>16357 AURORA AVENUE NORTH, SHORELINE, WA 98133 ").openPopup();
    closePopup();
}

//jquery function thhat handles the click event for all the markers.
$(document).ready(function(){

    markerOne.on('click', onMarkerClick);
    markerTwo.on('click', onMarkerClickTwo);
    markerThree.on('click', onMarkerClickThree);
    markerFour.on('click', onMarkerClickFour);
    markerFive.on('click', onMarkerClickFive);
});

var myJSON = '{"name":"John", "age":31, "city":"New York"}';
var myObj = JSON.parse(myJSON);
document.getElementById("demo").innerHTML = myObj.city;

$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});