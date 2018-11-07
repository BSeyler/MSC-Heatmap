<?php
    /*
     * Bradley Seyler
     * 11/4/2018
     * csvCombiner.php
     *
     * This file can read and write CSV's, as well as geocode
     * data to be used in the CSV
     */

    //Start the row at row zero
    $row = 1;

    //Create an array to store loaded CSV data
    $csvData = array();

    //Start counted number of rows at 0
    $counter = 0;

    //Set this to true if geocoding is necessary, false if now
    $geocoding = false;

    //This opens the penalties csv in read mode
    if (($handle = fopen("Penalties_Download.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $row++;

            //Make sure the home is in washington
            if($data[4] == 'WA'){
                $exists = false;
                //$outOfState = false;

                //Check to see if the row already exists
                foreach($csvData as $row2){
                    if($row2[1] == $data[1]){
                        $exists = true;
                    }
                }

                //If this is a new row
                if(!$exists){
                    $csvData[$counter] = $data;
                    $csvData[$counter][12] = 1; //Set initial number of penalties to 1

                    $counter++;
                }else{
                    //Cast the new variables to ints so they can be added
                    $curTotal = (int)($csvData[$counter - 1][8]);
                    $newAmount = (int)($data[8]);

                    //Add the variables
                    $newTotal = $curTotal + $newAmount;

                    $csvData[$counter - 1][8] = $newTotal; //Set the total

                    $csvData[$counter - 1][12]++; //Increment number of penalties
                }
            }
        }
        fclose($handle);
    }

    //Reset the counter to be used for the alreadySeen array
    $counter = 0;

    //This is used to keep track of indexes that are already seen
    $alreadySeen = array();

    //This opens the health deficiencies csv in read mode
    if (($handle = fopen("HealthDeficiencies_Download.csv", "r")) !== FALSE) {
        while (($data2 = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data2);
            $row++;

            //Make sure the home is in washington
            if($data2[4] == 'WA'){
                $exists = false;
                //$outOfState = false;

                $foundRow = 0; //This is used to keep track of the

                //Check to see if the row already exists
                foreach($csvData as $row2){
                    //If the name in the csvData array matches the new data's name, AND the name is not
                    //already in the alreadySeen array,
                    if($row2[1] == $data2[1] && !in_array($data2[1], $alreadySeen)){

                        //Set exists to true
                        $exists = true;

                        $alreadySeen[$counter] = $row2[1]; //Add the name to the seen array
                        break;
                    }
                    //Increment found row if not found, this will be used later when assigning scopes
                    $foundRow++;
                }

                //If this row exists
                if($exists){
                    $csvData[$foundRow][13] = $data2[12]; //add the scope to index 13
                }
            }
        }
        fclose($handle);
    }

    //This contains nursing home name, address, and  lat/lon coordinates
    $nursingHomeData = array();

    $counter = 0;


    for($counter=0; $counter< sizeof($csvData); $counter++){
        //Get current row
        $row3 = $csvData[$counter];

        //If geocoding is set,
        if($geocoding){
            //Find the address to geocode
            $addressToQuery = $row3[3] . ', ' . $row3[4] . ' ' . $row3[5];
            $spacelessAddressToQuery = str_replace(' ', '%20', $addressToQuery);

            //Request the address to the API
            $info = requestInfo($spacelessAddressToQuery);

            //Save the variables to the associative array
            $nursingHomeData[$counter]['latitude'] = $info[0];
            $nursingHomeData[$counter]['longitude'] = $info[1];
        }

        //Set all data to the associative array
        $nursingHomeData[$counter]['name'] = $row3[1];
        $nursingHomeData[$counter]['address'] = $row3[2];
        $nursingHomeData[$counter]['city'] = $row3[3];
        $nursingHomeData[$counter]['zip'] = $row3[5];
        $nursingHomeData[$counter]['fines'] = $row3[8];
        $nursingHomeData[$counter]['penalties'] = $row3[12];
        $nursingHomeData[$counter]['scope'] = $row3[13];
    }

    //Create or open file.csv to write to
    $fp = fopen('file.csv', 'w');

    //Create header row
    $header = array('name', 'address', 'city', 'zip', 'state', 'latitude', 'longitude', 'fine_amount', 'num_fines', 'scope');

    //Add it to the csv
    fputcsv($fp, $header);

    //For all nursing homes,
    foreach ($nursingHomeData as $nursinghome) {
        //If geocoding is on, add latitude and longitude to output fille
        if(!$geocoding){
            $line = array ($nursinghome['name'],$nursinghome['address'],$nursinghome['city'],$nursinghome['zip'],'WA', 'lat', 'long', $nursinghome['fines'], $nursinghome['penalties'], $nursinghome['scope']);
       }
       //Else, add lat/long as placeholders
       else{
            $line = array ($nursinghome['name'],$nursinghome['address'],$nursinghome['city'],$nursinghome['zip'],'WA', $nursinghome['latitude'], $nursinghome['longitude'], $nursinghome['fines'], $nursinghome['penalties']);
        }

        //Add line to the CSV
        fputcsv($fp, $line);
    }

    //Close the file
    fclose($fp);

    /**
     * This function requests data from the API for geocoding
     * @param $info String This parameter is the address to be queried
     * @return array This return array contains the latitude and longitude of the geocoded address
     */
    function requestInfo($info)
    {

        //This is the URL for the
        //$oldurl = 'https://us1.locationiq.com/v1/search.php?key=dda269b42e7d9c&format=json&q=' . $info;

        $url = "https://api.tomtom.com/search/2/geocode/".$info .".JSON?key=64lfstO1f0yVenePkjxEK9TVGKxgDGTz";


        //This is used to set the options for the file_get_contents function
        $opts = array(
            'http' => array('ignore_errors' => true)
        );

        $context = stream_context_create($opts);

        //This requests the data from the server
        $response = file_get_contents($url, false,$context);


        /*Enable below if CURL is set in your servers options
        //$newUrl = "https://api.tomtom.com/search/2/geocode/Seattle,Washington.JSON?key=64lfstO1f0yVenePkjxEK9TVGKxgDGTz";
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);*/

        //This decodes the JSON data
        $decoded = json_decode($response, true);

        //This is the geocoded data
        $latitude = $decoded['results'][1]["position"]['lat'];
        $longitude = $decoded['results'][1]["position"]['lon'];

        //Create the return array
        $returnArray = array();

        //Set data for the return array
        $returnArray[0] = $latitude;
        $returnArray[1] = $longitude;

        //Return the return data
        return $returnArray;
    }
?>

<!--This section is exclusively used for the download button-->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
</head>

<body>
<a href="file.csv">Download CSV</a>
</body>
</html>


