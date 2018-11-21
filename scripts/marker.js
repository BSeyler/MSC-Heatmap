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
    //open popup when search is successful
    searchControl.on('search:locationfound', function(e){
        if(e.layer._popup)
        {
            e.layer.openPopup();
        }
    });
    mymap.addControl( searchControl);
    geoData.addTo(mymap);
}
//function that adds circlemarkers, the color is determined by the rating
function addCircleMarkers(data,mymap) {
    //create new layer
    geoDataTwo = new L.geoJson(data,{
        pointToLayer: function(feature,latlng) {
            //colors assigned to feature.properties.SCOPE
            var colors = {
                "A": 'green',
                "B": 'green',
                "C": 'green',
                "D": 'green',
                "E": 'orange',
                "F": 'orange',
                "G": 'orange',
                "H": 'red',
                "I": 'red',
                "J": 'red',
                "K": 'red',
                "L": 'red'
            }
            //return circle marker
            return new L.CircleMarker(latlng,{
                radius:7,
                fillColor: colors[feature.properties.SCOPE],
                color: colors[feature.properties.SCOPE],
                weight: 1.2,
                opacity: 1,
                fillOpacity: 0.5,
                clickable: true
            })
        }
    });
    //add new layer to the map
    geoDataTwo.addTo(mymap);
}
//add layer
mymap.addLayer(geoData);

//link geojson file using jquery and call the addMarkersToMap and addCircleMarkersToMap
$.getJSON("../MSC/map.geojson", function(data) { addMarkersToMap(data, mymap); });
$.getJSON("../MSC/map.geojson", function(data) { addCircleMarkers(data, mymap); });
