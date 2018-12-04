<?php
    /*
     * Bradley Seyler
     *
     * automaticUpdate.php
     *
     * This file contains relevant functions to download and
     * combine CSVs with relevant information
     */

    //Log current time
    $date = new DateTime();
    $date = $date->format("m-d-y h:i:s");
    $success = false;

    //If the zip folder doesn't exist
    if (!file_exists('/home/bseylerg/public_html/geocoding_test/zip/')) {
        mkdir('/home/bseylerg/public_html/geocoding_test/zip/', 0777, true);
    }

    //Download new CSVs
    file_put_contents("nursingHomeCSVs.zip", fopen("https://data.medicare.gov/views/bg9k-emty/files/bddc3db6-a697-400e-b2f8-38d604baa268?content_type=application%2Fzip%3B%20charset%3Dbinary&filename=NursingHomeCompare_Revised_Flatfiles.zip", 'r'));

    //Move to a zip folder to keep organization incase someone cuts off the page before unzipping
    rename("nursingHomeCSVs.zip", "/home/bseylerg/public_html/geocoding_test/zip/nursingHomeCSVs.zip");

    //Create a new zip archive
    $zip = new ZipArchive;

    //Open the downloaded zip with CSVs
    $res = $zip->open('/home/bseylerg/public_html/geocoding_test/zip/fullstatement.zip');

    //If the files were successfully opened
    if ($res === TRUE) {
        //Extract all the CSVs to folder csv
        $zip->extractTo('csv/');

        //Close the file,
        $zip->close();

        //Combine the CSVs
        combineCSV();

        $success = true;
    }
    else
    {
        $success = false;
    }

    //Clean up the CSV directory
    cleanUp();


    //Run convertCsvtoGeoJSON.py
    $command = escapeshellcmd('python /home/bseylerg/public_html/msc/MSC-Heatmap/csv-updater/convertCsvtoGeoJSON.py');
    $output =  popen($command, "r");

    //Close python file
    pclose($output);

    //Open the log file,
    $file=fopen("LOG.txt","a+") or exit("Unable to open file!");

    //Write a line with the date on it,
    fwrite($file, $date . "\n");

    //And then based on the event, log to the log file
    if($success)
    {
        fwrite($file, "Message: Successfully Downloaded files");
    }
    else
    {
        fwrite($file, "Message: Failed to download files");
    }

    //End first event with a new line,
    fwrite($file, "\n");

    fwrite($file, "Message: Attempted to convert to convert CSV to GeoJSON!");


    //End with a new line,
    fwrite($file, "\n");

    //And close the file
    fclose($file);

    /**
     * This function cleans up the CSV directory after the output CSV is made
     */
    function cleanUp()
    {
        unlink('csv/DataMedicareGov_MetadataAllTabs_v17.xlsx');
        unlink('csv/FireSafetyDeficiencies_Download.csv');
        unlink('csv/HealthDeficiencies_Download.csv');
        unlink('csv/Ownership_Download.csv');
        unlink('csv/Penalties_Download.csv');
        unlink('csv/ProviderInfo_Download.csv');
        unlink('csv/QualityMsrClaims_Download.csv');
        unlink('csv/QualityMsrMDS_Download.csv');
        unlink('csv/StateAverages_Download.csv');
        unlink('csv/SurveySummary_Download.csv');
    }

    /**
     * This function reads in and combines the miscellaneous CSV files downloaded by the script
     */
    function combineCSV()
    {
        //Start the row at row zero
        $row = 1;

        //Create an array to store loaded CSV data
        $csvData = array();

        //Start counted number of rows at 0
        $counter = 0;

        //Set this to true if geocoding is necessary, false if now
        $geocoding = true;

        //This opens the penalties csv in read mode
        if (($handle = fopen("csv/Penalties_Download.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;

                //Make sure the home is in washington
                if ($data[4] == 'WA') {
                    $exists = false;
                    //$outOfState = false;

                    //Check to see if the row already exists
                    foreach ($csvData as $row2) {
                        if ($row2[1] == $data[1]) {
                            $exists = true;
                        }
                    }

                    //If this is a new row
                    if (!$exists) {
                        $csvData[$counter] = $data;
                        $csvData[$counter][12] = 1; //Set initial number of penalties to 1

                        $counter++;
                    } else {
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
        if (($handle = fopen("csv/HealthDeficiencies_Download.csv", "r")) !== FALSE) {
            while (($data2 = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;

                //Make sure the home is in washington
                if ($data2[4] == 'WA') {
                    $exists = false;
                    //$outOfState = false;

                    $foundRow = 0; //This is used to keep track of the

                    //Check to see if the row already exists
                    foreach ($csvData as $row2) {
                        //If the name in the csvData array matches the new data's name, AND the name is not
                        //already in the alreadySeen array,
                        if ($row2[1] == $data2[1] && !in_array($data2[1], $alreadySeen)) {

                            //Set exists to true
                            $exists = true;

                            $alreadySeen[$counter] = $row2[1]; //Add the name to the seen array
                            break;
                        }
                        //Increment found row if not found, this will be used later when assigning scopes
                        $foundRow++;
                    }

                    //If this row exists
                    if ($exists) {
                        $csvData[$foundRow][13] = $data2[12]; //add the scope to index 13
                    }
                }
            }
            fclose($handle);
        }

        //This contains nursing home name, address, and  lat/lon coordinates
        $nursingHomeData = array();

        for ($counter = 0; $counter < sizeof($csvData); $counter++) {
            //Get current row
            $row3 = $csvData[$counter];

            //If geocoding is set,
            if ($geocoding) {
                //Find the address to geocode
                $addressToQuery = $row3[2] . ' ' . $row3[3] . ', ' . $row3[4] . ' ' . $row3[5];
                $spacelessAddressToQuery = str_ireplace(' ', '%20', $addressToQuery);

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

        //Create or open nursingHomeData.csv to write to
        $fp = fopen('csv/nursingHomeData.csv', 'w');

        //Create header row
        $header = array('LATITUDE', 'LONGITUDE', 'ADDRESS', 'NAME', 'FINE_AMOUNT', 'NUM_FINES', 'SCOPE');

        //Add it to the csv
        fputcsv($fp, $header);

        //For all nursing homes,
        foreach ($nursingHomeData as $nursinghome) {
            $addressString = $nursinghome['address'] . ', ' . $nursinghome['city'] . ' ' . $nursinghome['zip'] . ', ' . ' WA';
            //If geocoding is on, add latitude and longitude to output fille
            if (!$geocoding) {
                $line = array('lat', 'long', $addressString, $nursinghome['name'], $nursinghome['fines'], $nursinghome['penalties'], $nursinghome['scope']);
            } //Else, add lat/long as placeholders
            else {
                $line = array($nursinghome['latitude'], $nursinghome['longitude'], $addressString, $nursinghome['name'], $nursinghome['fines'], $nursinghome['penalties'], $nursinghome['scope']);
            }

            //Add line to the CSV
            fputcsv($fp, $line);
        }

        //Close the file
        fclose($fp);
    }


    /**
     * This function requests data from the API for geocoding
     * @param $info String This parameter is the address to be queried
     * @return array This return array contains the latitude and longitude of the geocoded address
     */
    function requestInfo($info)
    {

        //This is the URL for the
        //$oldurl = 'https://us1.locationiq.com/v1/search.php?key=dda269b42e7d9c&format=json&q=' . $info;

        //$url = "https://api.tomtom.com/search/2/geocode/".$info .".JSON?key=64lfstO1f0yVenePkjxEK9TVGKxgDGTz";

        //This is my bing maps API key.
        $key = "An1e6-bYWG_6j5ldYQoR_unXBlgrZl4sXTaggj_7ApW5FuoCxLnsuGahd_rILA-f";

        $url = "http://dev.virtualearth.net/REST/v1/Locations/" . $info . '?output=xml&key=' . $key;

        //This is used to set the options for the file_get_contents function
        $opts = array(
            'http' => array('ignore_errors' => true)
        );

        $context = stream_context_create($opts);

        //This requests the data from the server
        $response = file_get_contents($url, false,$context);

        $responseDecoded = new SimpleXMLElement($response);

        //This decodes the JSON data
        //$decoded = json_decode($response, true);

        //This is the geocoded data
        $latitude = $responseDecoded->ResourceSets->ResourceSet->Resources->Location->Point->Latitude;
        $longitude = $responseDecoded->ResourceSets->ResourceSet->Resources->Location->Point->Longitude;

        //Create the return array
        $returnArray = array();

        //Set data for the return array
        $returnArray[0] = $latitude;
        $returnArray[1] = $longitude;

        //Return the return data
        return $returnArray;
    }
?>



