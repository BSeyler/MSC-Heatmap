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
//let geoData = new L.geoJson();
let streets = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiY29ubmVybGVkYmV0dGVyIiwiYSI6ImNqbjVsYms5cTA1eTMzeGxrZTdjbWt0cDYifQ.W6Nphh44Guwtt9wX6pa6uA'
}).addTo(mymap);
//initialize control layers
let controlLayers = L.control.layers().addTo(mymap);
//initialize geojson data as layer group
let geoData = L.layerGroup();


function addMarkersToMap(data,mymap){

    let categories = {},
        Rating;
    //create new geojson object
    let geoData = new L.geoJson(data,{
        pointToLayer: function(feature,latlng) {
            //variable assigned to rating
            //let rating = feature.properties.SCOPE;
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
                "H": 'orange',
                "I": 'red',
                "J": 'red',
                "K": 'red',
                "L": 'red'
            }
            //variable to store the color of the marker to be passed in the function below
            let colorOfMarker = colors[feature.properties.scope];
            marker =
                new L.marker(latlng,
                    {icon: L.AwesomeMarkers.icon({icon:'home',prefix:'fa',markerColor: colorOfMarker}) });
            marker.bindPopup(feature.properties.name + '<br/>' + feature.properties.address
                + '<br/>' + '<strong>Rating: ' + feature.properties.scope + '</strong>'
                + '<br/>' + 'Number of fines:' + feature.properties.num_fines
                + '<br/>' + 'Fine amount: ' + feature.properties.fine_amount);
            return marker;
        },
        onEachFeature: function(feature,layer){
            layer.bindPopup(feature.properties.name + '<br/>' + feature.properties.address
                + '<br/>' + '<strong>Rating: ' + feature.properties.scope + '</strong>'
                + '<br/>' + 'Number of fines: ' + feature.properties.num_fines
                + '<br/>' + 'Fine amount: $' + feature.properties.fine_amount);
            Rating = feature.properties.scope;
            //for each feature initialize the array
            if(typeof categories[Rating] === "undefined"){
                categories[Rating] = [];
            }
            //add layer for each feature
            categories[Rating].push(layer);
        }
    });


    //create new searchControl to search by nursing home name
    var searchControl = new L.Control.Search({
        layer: geoData,
        propertyName: 'name',
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

    let baseLayers = {
        "Filter by Rating": geoData,
    };
    //create overlay to filter the layers by rating
    let overlaysObj = {},
        RatingName,
        RatingArray,
        RatingLG;

    //loop through categories and for each layer in this case its by the rating(scope)
    //and populate the control
    for(RatingName in categories){
        RatingArray = categories[RatingName];
        RatingLG = L.layerGroup(RatingArray).addTo(mymap);
        RatingLG.RatingName = RatingName;
        overlaysObj[RatingName]= RatingLG;
        //console.log(overlaysObj[RatingName]);
    }
    //create control with the overlay created above
    //and add it to the map
    let control = L.control.layers(baseLayers, overlaysObj,{
        collapsed: false
    }).addTo(mymap);
}
//initialize layer group for the counties
let countyLayer = L.layerGroup();

//array of county colors mapped to their correct regions
//for example Southeast WA region is color #d43ef2
var countyColors = {
    "Asotin": '#d43ef2',
    "Columbia": '#d43ef2',
    "Garfield": '#d43ef2',
    "Walla Walla": '#d43ef2',
    "Benton": '#d43ef2',
    "Franklin": '#d43ef2',
    "Clark": '#87c4ff',
    "Klickitat": '#87c4ff',
    "Skamania": '#87c4ff',
    "Wahkiakum": '#87c4ff',
    "Cowlitz": '#87c4ff',
    "Adams" : '#3a7cff',
    "Chelan": '#3a7cff',
    "Douglas": '#3a7cff',
    "Grant": '#3a7cff',
    "Lincoln" : '#3a7cff',
    "Okanogan": '#3a7cff',
    "Ferry": 'blue',
    "Pend Oreille": 'blue',
    "Spokane": 'blue',
    "Stevens": 'blue',
    "Whitman": 'blue',
    "Island": '#CC6666',
    "San Juan": '#CC6666',
    "Skagit": '#CC6666',
    "Whatcom": '#CC6666',
    "Lewis": '#33FF33',
    "Mason": '#33FF33',
    "Thurston": '#33FF33',
    "Grays Harbor": '#42eef4',
    "Pacific": '#42eef4',
    "Clallam": '#41bbf4',
    "Jefferson": '#41bbf4',
    "Kittitas": '#816dff',
    "Yakima": '#816dff',
    "King": '#336699',
    "Snohomish": '#00CC66',
    "Kitsap": '#CCCC00',
    "Pierce": '#0b68ea'

}
//link geojson file of counties
jQuery.getJSON("../MSC-Heatmap/washington.geojson", function( json){
    L.geoJSON( json, {
        style: function(feature){
            let colorOfRegion = countyColors[feature.properties.NAME];
            let fillColor;
            //return regions colored in with their corresponding color
            return{color: 'blue', weight: 1, fillColor: colorOfRegion, fillOpacity: .6};
        },
        //for each feature a layer is called in this case it's a county
        onEachFeature: addCountyData,
    })
});
//helper function to add the county layer
function addCountyData(feature,layer){
    countyLayer.addLayer(layer)
    let colorOfRegion = countyColors[feature.properties.NAME]
    //bind popup to show the corresponding region when clicked on based on color
    if(colorOfRegion === '#d43ef2')
    {
        let region = "Southeast WA Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#87c4ff'){
        let region = "Southwest WA Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#3a7cff'){
        let region = "Central WA Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === 'blue'){
        let region = "Eastern WA Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#CC6666'){
        let region = "Northwest WA Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#33FF33'){
        let region = "Lewis, Mason, Thurston Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#42eef4'){
        let region = "Grays Harbor & Pacific Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#41bbf4'){
        let region = "Clallam & Jefferson Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#41bbf4'){
        let region = "Clallam & Jefferson Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#816dff'){
        let region = "Kittitas & Yakima Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#336699'){
        let region = "King Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#CCCC00'){
        let region = "Kitsap Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#0b68ea'){
        let region = "Pierce Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }
    if(colorOfRegion === '#00CC66'){
        let region = "Snohomish Region";
        layer.bindPopup('County: ' + feature.properties.NAME + '<br/>'
            + region);
    }

}
//add control overlay to toggle the regions on and off
controlLayers.addOverlay(countyLayer, 'Show Regions');

//link geojson file of the nursing homes which calls the function that adds the markers to the map,
//the search control to search within the nursing home layer
$.getJSON("../MSC-Heatmap/finalmap1.geojson", function(data) { addMarkersToMap(data, mymap); });


