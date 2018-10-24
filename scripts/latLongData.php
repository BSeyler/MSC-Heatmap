<?php
/* Bradley Seyler
 * 10/16/2018
 *
 * latLongData.php
 *
 * This file takes post data from index.php
 * and queries it with LocationIQ to get lat/long
 * coordinates
 */
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //Address from the post in index.php
    $address = $_POST['search'];

    //Remove spaces and replace with %20
    $spacelessAddress = str_replace(' ', '%20', $address);

    //Create the query string to use with get
    //**It should be noted our groups LocationIQ key is dda269b42e7d9c**
    //This is REQUIRED to make the query return JSON data
    $url = 'https://us1.locationiq.com/v1/search.php?key=dda269b42e7d9c&format=json&q=' . $spacelessAddress;

    //Get the JSON data
    $response = file_get_contents($url);
    //$responseYahoo = file_get_contents($yahooUrl);

    //Decode it
    $decoded = json_decode($response, true);

    //Take the latitude and longitude from the decoded JSON file
    $latitude = $decoded[0]['lat'];
    $longitude = $decoded[0]['lon'];

    //Display the data
    echo '<p>Address: ' . $address . '</p>';

    $string = '<p>latitude/longitude: '   . $latitude . ',' . $longitude . '</p>';
    echo($string);
}
?>