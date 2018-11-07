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
//loads geojson file and adds markers respectively
$.getJSON("../../MSC/map.geojson",function(data){
    L.geoJson(data,{
        pointToLayer: function(feature,latlng){
            let marker = L.marker(latlng);
            marker.bindPopup(feature.properties.PROVNAME + '<br/>' + feature.properties.ADDRESS
                + '<br/>' + '<strong>Rating: ' + feature.properties.SCOPE + '</strong>');
            return marker;
        }
    }).addTo(mymap);
});
//search function added to map
var controlSearch = new L.Control.Search({
    position:'topright',
    layer: markersLayer,
    initial: false,
    zoom: 12,
    marker: false
});

mymap.addControl( controlSearch );

/*
var markersLayer = new L.LayerGroup();	//layer contain searched elements

mymap.addLayer(markersLayer);

var data = [
    {"loc":[47.595011, -122.298211], "title":"Paramount Rehabilitation And Nursing"},
    {"loc":[47.653131, -117.261482], "title":"Gardens On University, The"},
    {"loc":[47.439403, -122.213203], "title":"Talbot Center For Rehab & Healthcare"},
    {"loc":[48.215684, -122.682917], "title":"Careage Of Whidbey"},
    {"loc":[47.748060, -122.346510], "title":"The Oaks At Forest Bay"}
];


for(i in data) {
    var title = data[i].title,	//value searched
        loc = data[i].loc,		//position found
        marker = new L.Marker(new L.latLng(loc), {title: title} );//se property searched
    marker.bindPopup('title: '+ title );
    markersLayer.addLayer(marker);
} */



var myJSON = '{"name":"John", "age":31, "city":"New York"}';
var myObj = JSON.parse(myJSON);
//document.getElementById("demo").innerHTML = myObj.city;

$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});