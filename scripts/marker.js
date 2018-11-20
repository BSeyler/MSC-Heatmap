/*
Bigger Four
marker.js
Script that puts the map on the page, adds markers to the map and handles the markers events.
 */

//create the map and sets the view of washington
var mymap = L.map('mapid').setView([47.75, -120.74], 7);
let geoData = new L.geoJson();
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiY29ubmVybGVkYmV0dGVyIiwiYSI6ImNqbjVsYms5cTA1eTMzeGxrZTdjbWt0cDYifQ.W6Nphh44Guwtt9wX6pa6uA'
}).addTo(mymap);

function addMarkersToMap(data,mymap){
    //create new geojson object
    geoData = new L.geoJson(data,{
        pointToLayer: function(feature,latlng) {
            let marker = L.marker(latlng);
            //when the marker is clicked on, displays corresponding
            //name , address and rating
            marker.bindPopup(feature.properties.PROVNAME + '<br/>' + feature.properties.ADDRESS
                + '<br/>' + '<strong>Rating: ' + feature.properties.SCOPE + '</strong>');
            return marker;
        }
    });
    //create new searchControl to search by name
    var searchControl = new L.Control.Search({
        layer: geoData,
        propertyName: 'PROVNAME',
        marker: false,
        moveToLocation: function (latlng) {
            mymap.setView(latlng, 12);
        }
    });
    mymap.addControl( searchControl);
    geoData.addTo(mymap);
}
//add layer
mymap.addLayer(geoData);

//link geojson file using jquery and call the addMarkersToMap
$.getJSON("../MSC/map.geojson", function(data) { addMarkersToMap(data, mymap); });
