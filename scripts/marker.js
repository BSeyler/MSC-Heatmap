/*
Bigger Four
marker.js
Script that puts the map on the page, adds markers to the map and handles the markers events.
 */

//This fixes an issue with malformed XML parsing in firefox specifically
$.ajaxSetup({beforeSend: function(xhr){
        if (xhr.overrideMimeType)
        {
            xhr.overrideMimeType("application/json");
        }
    }
});

//create the map and sets the view of washington
var mymap = L.map('mapid').setView([47.75, -120.74], 7);
let geoData = new L.geoJson();
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiY29ubmVybGVkYmV0dGVyIiwiYSI6ImNqbjVsYms5cTA1eTMzeGxrZTdjbWt0cDYifQ.W6Nphh44Guwtt9wX6pa6uA'
}).addTo(mymap);

//add layer
mymap.addLayer(geoData);

//Open the json that contains the regions from washington
$.getJSON("../MSC-Heatmap/washington.geojson", function(json) {

    var geoList = new L.Control.GeoJSONSelector(L.geoJson(json), {
        zoomToLayer: true,
        activeListFromLayer: true,
        //activeLayerFromList: true,
        //listOnlyVisibleLayers: true,
        position: 'bottomright',
        collapsed: false
    }).addTo(mymap);

    geoList.on('selector:change', function(e) {
        var jsonObj = $.parseJSON(JSON.stringify(e.layers[0].feature.properties.NAME) );
        //JSON.parse(jsonObj);
        //console.log(e.layers[1].feature.properties.NAME);
        console.log(jsonObj);
        //console.log(jsonObj);
        //console.log(feature.properties.name); */
        var html = 'Selection:<br /><table border="1">';
        $.each(jsonObj, function(key, value){
            html += '<tr>';
            html += '<td>' + key.replace(":", " ") + '</td>';
            html += '<td>' + value + '</td>';
            html += '</tr>';
        });
        html += '</table>';

        $('.selection').html(html);
    });

    mymap.addControl(function() {
        var c = new L.Control({position:'bottomright'});
        c.onAdd = function(mymap) {
            return L.DomUtil.create('pre','selection');
        };
        return c;
    }());
    //mymap.addControl(geoList);

});

//link geojson file using jquery and call the addMarkersToMap and addCircleMarkersToMap
$.getJSON("../MSC-Heatmap/map.geojson", function(data) { addMarkersToMap(data, mymap); });

/**
 * This function populates the maps markers into the map object
 *
 * @param data This is
 * @param mymap This is the map object that all data is stored in.
 */
function addMarkersToMap(data,mymap){
    //create new geojson object
    geoData = new L.geoJson(data,{
        pointToLayer: function(feature,latlng) {
            //variable assigned to rating
            let rating = feature.properties.SCOPE;
            let marker;
            //array of colors assigned to each rating
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
            //variable to store the color of the marker to be passed in the function below
            let colorOfMarker = colors[feature.properties.SCOPE];
            marker =
                new L.marker(latlng,
                    {icon: L.AwesomeMarkers.icon({icon:'home',prefix:'fa',markerColor: colorOfMarker}) });
            marker.bindPopup(feature.properties.PROVNAME + '<br/>' + feature.properties.ADDRESS
                + '<br/>' + '<strong>Rating: ' + feature.properties.SCOPE + '</strong>');
            return marker;
        }
    });

    // JQuery for removing list that does not populate county names
    $( ".geojson-list-group" ).remove();
    $( ".geojson-list" ).remove();

    //create new searchControl to search by name
    var searchControl = new L.Control.Search({
        layer: geoData,
        propertyName: 'PROVNAME',
        marker: false,
        moveToLocation: function (latlng) {
            mymap.setView(latlng, 15);
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
//add layer
mymap.addLayer(geoData);

$.getJSON("../MSC-Heatmap/washington.geojson", function(json) {
   
    var geoList = new L.Control.GeoJSONSelector(L.geoJson(json), {
        zoomToLayer: true,
        activeListFromLayer: true,
        //activeLayerFromList: true,
        //listOnlyVisibleLayers: true,
        position: 'bottomright',
        collapsed: false
    }).addTo(mymap);

    geoList.on('selector:change', function(e) {
        var jsonObj = $.parseJSON(JSON.stringify(e.layers[0].feature.properties.NAME) );
        //JSON.parse(jsonObj);
        //console.log(e.layers[1].feature.properties.NAME);
        console.log(jsonObj);
        //console.log(jsonObj);
        //console.log(feature.properties.name); */
        var html = 'Selection:<br /><table border="1">';
        $.each(jsonObj, function(key, value){
            html += '<tr>';
            html += '<td>' + key.replace(":", " ") + '</td>';
            html += '<td>' + value + '</td>';
            html += '</tr>';
        });
        html += '</table>';

        $('.selection').html(html);
    });

    mymap.addControl(function() {
        var c = new L.Control({position:'bottomright'});
        c.onAdd = function(mymap) {
            return L.DomUtil.create('pre','selection');
        };
        return c;
    }());
    //mymap.addControl(geoList);

});

//link geojson file using jquery and call the addMarkersToMap and addCircleMarkersToMap
$.getJSON("../MSC-Heatmap/map.geojson", function(data) { addMarkersToMap(data, mymap); });
//$.getJSON("../MSC-Heatmap/washington.geojson", function(data) { addRegionsToMap(data, mymap); });
//$.getJSON("../MSC-Heatmap/map.geojson", function(data) { addCircleMarkers(data, mymap); });
